<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        // Fetch all orders from Firebase
        $allOrders = $this->firebase->getOrders();

        // Filter orders
        $orders = $allOrders->filter(function ($order) {
            return in_array($order['status'], ['submitted', 'in_progress']);
        });

        $today = now()->format('Y-m-d');
        $todays_completed = $allOrders->filter(function ($order) use ($today) {
            return $order['status'] === 'completed' && str_starts_with($order['updated_at'], $today);
        });

        $completed_orders = $todays_completed->sortByDesc('updated_at')->take(10);
        $today_sales = $todays_completed->sum('total_amount');

        return view('staff.orders.index', compact('orders', 'completed_orders', 'today_sales'));
    }

    public function update(Request $request, $reference)
    {
        $request->validate([
            'status' => 'required|in:submitted,in_progress,completed,canceled'
        ]);

        $this->firebase->updateOrderStatus($reference, $request->status);

        return redirect()->back()
            ->with('success', 'Order #' . $reference . ' updated to ' . ucwords(str_replace('_', ' ', $request->status)));
    }

    public function history(Request $request)
    {
        $allOrders = $this->firebase->getOrders();

        // Filter for archived orders (completed/canceled)
        $history = $allOrders->filter(function ($order) {
            return in_array($order['status'], ['completed', 'canceled']);
        });

        // 1. Search by Reference
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $history = $history->filter(function ($order) use ($search) {
                return str_contains(strtolower($order['reference']), $search);
            });
        }

        // 2. Date Filtering
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

        $history = $history->sortByDesc('updated_at');

        return view('staff.orders.history', compact('history', 'filter'));
    }
}
