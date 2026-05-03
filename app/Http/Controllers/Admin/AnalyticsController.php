<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index(Request $request)
    {
        $range = $request->get('range', 7);
        $allOrders = $this->firebase->getOrders();

        $completedOrders = $allOrders->filter(function ($order) {
            return ($order['payment_status'] ?? '') === 'paid';
        })->map(function ($order) {
            $order['date'] = Carbon::parse($order['updated_at']);
            return $order;
        });

        // 1. Daily Sales (Based on selected Range)
        $dailySales = [];
        $iterations = (int)$range - 1;
        for ($i = $iterations; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $key = $date->format('Y-m-d');
            $label = $date->format('d M');
            $dailySales[$label] = round($completedOrders->filter(function ($o) use ($key) {
                return $o['date']->format('Y-m-d') === $key;
            })->sum('total_amount'), 2);
        }

        // 2. Weekly Sales (Last 4 Weeks)
        $weeklySales = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            $label = 'Week ' . $startOfWeek->format('d/m');
            $weeklySales[$label] = round($completedOrders->filter(function ($o) use ($startOfWeek, $endOfWeek) {
                return $o['date']->between($startOfWeek, $endOfWeek);
            })->sum('total_amount'), 2);
        }

        // 3. Monthly Sales (Last 6 Months)
        $monthlySales = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStr = $date->format('M Y');
            $monthlySales[$monthStr] = round($completedOrders->filter(function ($o) use ($date) {
                return $o['date']->format('Y-m') === $date->format('Y-m');
            })->sum('total_amount'), 2);
        }

        // 4. Yearly Sales (Current Year)
        $yearlySales = round($completedOrders->filter(function ($o) {
            return $o['date']->year === Carbon::now()->year;
        })->sum('total_amount'), 2);

        // 5. Top Sellers Aggregation
        $monthlyTopSellers = $this->getTopSellers($completedOrders->filter(fn($o) => $o['date']->isCurrentMonth()));
        $yearlyTopSellers = $this->getTopSellers($completedOrders->filter(fn($o) => $o['date']->isCurrentYear()));

        return view('admin.analytics', compact('dailySales', 'weeklySales', 'monthlySales', 'yearlySales', 'completedOrders', 'monthlyTopSellers', 'yearlyTopSellers', 'range'));
    }

    public function export(Request $request)
    {
        $allOrders = $this->firebase->getOrders();
        $categories = $this->firebase->getCategories();
        
        $completedOrders = $allOrders->filter(function ($order) {
            return ($order['payment_status'] ?? '') === 'paid';
        })->map(function ($order) {
            $order['date'] = Carbon::parse($order['updated_at']);
            return $order;
        });

        // Current Month Stats
        $thisMonthOrders = $completedOrders->filter(fn($o) => $o['date']->isCurrentMonth());
        $totalRevenue = round($thisMonthOrders->sum('total_amount'), 2);
        $totalOrders = $thisMonthOrders->count();
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

        // Category Breakdown
        $categoryData = [];
        foreach ($categories as $catId => $cat) {
            $catRevenue = $thisMonthOrders->sum(function ($order) use ($catId) {
                return collect($order['items'] ?? [])->where('category_id', $catId)->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 0));
            });
            if ($catRevenue > 0) {
                $categoryData[$cat['name']] = round($catRevenue, 2);
            }
        }
        arsort($categoryData);

        // Top Sellers
        $topSellers = $this->getTopSellers($thisMonthOrders);

        $period = Carbon::now()->format('F Y');
        $title = "Business Performance Report - " . $period;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.analytics', compact(
            'totalRevenue', 
            'totalOrders', 
            'avgOrderValue', 
            'categoryData', 
            'topSellers', 
            'period', 
            'title'
        ));

        return $pdf->download("Business_Report_{$period}.pdf");
    }

    protected function getTopSellers($orders)
    {
        $sales = [];
        foreach ($orders as $order) {
            if (!isset($order['items']) || !is_array($order['items']))
                continue;
            foreach ($order['items'] as $item) {
                if (!isset($item['product_id']))
                    continue;
                $pId = $item['product_id'];
                if (!isset($sales[$pId])) {
                    $sales[$pId] = [
                        'name' => $item['name'] ?? 'Unknown Product',
                        'image' => $item['image'] ?? null,
                        'quantity' => 0,
                        'total' => 0
                    ];
                }
                $sales[$pId]['quantity'] += ($item['quantity'] ?? 0);
                $sales[$pId]['total'] += round(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2);
            }
        }

        return collect($sales)->sortByDesc('quantity')->take(3);
    }
}
