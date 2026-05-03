<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Display the main Order Dashboard for staff.
     * Fetches all active orders from Firebase and calculates today's metrics.
     */
    public function index()
    {
        // 1. Fetch all orders from Firebase Realtime Database
        $allOrders = $this->firebase->getOrders();

        // 2. Filter for "Active" orders that need staff attention
        $orders = $allOrders->filter(function ($order) {
            return in_array($order['status'], ['submitted', 'in_progress']);
        });

        // 3. Calculate today's sales and completed orders count
        $today = now()->format('Y-m-d');
        $todays_completed = $allOrders->filter(function ($order) use ($today) {
            return $order['status'] === 'completed' && str_starts_with($order['updated_at'], $today);
        });

        $completed_orders = $todays_completed->sortByDesc('updated_at')->take(10); // Show last 10 completed
        $today_sales = $todays_completed->sum('total_amount'); // Calculate total revenue for today

        return view('staff.orders.index', compact('orders', 'completed_orders', 'today_sales'));
    }

    /**
     * Update the status of an order in Firebase.
     * Triggers the real-time listener on the customer's tracking page.
     */
    public function update(Request $request, $reference)
    {
        $request->validate([
            'status' => 'required|in:submitted,in_progress,completed,canceled'
        ]);

        // Sync change back to Firebase RTDB
        $this->firebase->updateOrderStatus($reference, $request->status);

        return redirect()->back()
            ->with('success', 'Order #' . $reference . ' updated to ' . ucwords(str_replace('_', ' ', $request->status)));
    }

    /**
     * Display the Cashier view — shows all completed orders awaiting payment.
     */
    public function cashier()
    {
        $allOrders = $this->firebase->getOrders();

        // Only show orders that are completed but not yet paid
        $pendingPayment = $allOrders->filter(function ($order) {
            return $order['status'] === 'completed'
                && ($order['payment_status'] ?? 'unpaid') !== 'paid';
        })->sortBy('updated_at');

        $totalPending = $pendingPayment->sum('total_amount');

        return view('staff.orders.cashier', compact('pendingPayment', 'totalPending'));
    }

    /**
     * Mark a specific order as paid.
     */
    public function markAsPaid($reference)
    {
        $this->firebase->updateOrderPayment($reference, 'paid');

        return redirect()->back()
            ->with('success', 'Order #' . $reference . ' has been marked as paid.')
            ->with('print_receipt', $reference);
    }

    /**
     * Display a printable receipt for a specific order.
     */
    public function receipt($reference)
    {
        $order = $this->firebase->getOrder($reference);
        if (!$order) {
            abort(404);
        }
        return view('staff.orders.receipt', compact('order'));
    }

    /**
     * View the History of archived (completed/canceled) orders.
     * Includes search, status filtering, and date range filtering.
     */
    public function history(Request $request)
    {
        $allOrders = $this->firebase->getOrders();

        // 1. Filter for archived orders (completed/canceled)
        $history = $allOrders->filter(function ($order) {
            return in_array($order['status'], ['completed', 'canceled']);
        });

        // 2. Apply Search Filter by Reference Code
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $history = $history->filter(function ($order) use ($search) {
                return str_contains(strtolower($order['reference'] ?? ''), $search);
            });
        }

        // 3. Apply Status Filter (Completed vs Canceled)
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;
            $history = $history->filter(function ($order) use ($status) {
                return ($order['status'] ?? '') === $status;
            });
        }

        // 4. Apply Date Preset Filtering (Today, Yesterday, Month, Custom)
        $filter = $request->get('filter', 'today');
        $startDate = null;
        $endDate = null;

        if ($filter === 'today') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($filter === 'yesterday') {
            $startDate = now()->subDay()->startOfDay();
            $endDate = now()->subDay()->endOfDay();
        } elseif ($filter === 'month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($filter === 'custom' && $request->filled('date')) {
            $startDate = \Carbon\Carbon::parse($request->date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->date)->endOfDay();
        }

        if ($startDate && $endDate) {
            $history = $history->filter(function ($order) use ($startDate, $endDate) {
                $orderDate = \Carbon\Carbon::parse($order['updated_at']);
                return $orderDate->between($startDate, $endDate);
            });
        }

        // 5. Sort by most recent updates first
        $history = $history->sortByDesc('updated_at');

        return view('staff.orders.history', compact('history', 'filter'));
    }

    /**
     * Generate a PDF Sales Report for a specific period.
     */
    public function generateReport(Request $request)
    {
        $allOrders = $this->firebase->getOrders();
        $type = $request->get('type', 'monthly'); // monthly or yearly
        
        $startDate = null;
        $endDate = null;
        $filename = "report.pdf";
        $title = "";
        $period = "";

        // Determine date range for the PDF report
        if ($type === 'monthly') {
            $month = $request->get('month', now()->format('Y-m'));
            $startDate = \Carbon\Carbon::parse($month)->startOfMonth();
            $endDate = \Carbon\Carbon::parse($month)->endOfMonth();
            $filename = "Monthly_Report_{$month}.pdf";
            $title = "Monthly Sales Report";
            $period = \Carbon\Carbon::parse($month)->format('F Y');
        } else {
            $year = $request->get('year', now()->format('Y'));
            $startDate = \Carbon\Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = \Carbon\Carbon::createFromDate($year, 12, 31)->endOfDay();
            $filename = "Yearly_Report_{$year}.pdf";
            $title = "Yearly Sales Report";
            $period = $year;
        }

        // Fetch data matching the criteria
        $reportData = $allOrders->filter(function ($order) use ($startDate, $endDate, $request) {
            $orderDate = \Carbon\Carbon::parse($order['updated_at']);
            $matchesDate = $orderDate->between($startDate, $endDate);
            $status = $request->get('status', 'completed');
            $matchesStatus = ($status === 'all') 
                ? in_array($order['status'], ['completed', 'canceled']) 
                : $order['status'] === $status;

            return $matchesDate && $matchesStatus;
        })->sortBy('updated_at');

        // Append status to report title for clarity
        if ($request->get('status') && $request->status !== 'all') {
            $title .= " (" . ucfirst($request->status) . ")";
        } else {
            $title .= " (All Archived)";
        }

        $totalSales = $reportData->sum('total_amount');

        // Use Barryvdh\DomPDF to generate PDF from a Blade view
        $pdf = Pdf::loadView('staff.reports.sales', compact('reportData', 'title', 'period', 'totalSales'));
        
        return $pdf->download($filename);
    }
}
