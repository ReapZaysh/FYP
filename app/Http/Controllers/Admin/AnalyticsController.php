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

    public function index()
    {
        $allOrders = $this->firebase->getOrders();

        $completedOrders = $allOrders->filter(function ($order) {
            return $order['status'] === 'completed';
        })->map(function ($order) {
            $order['date'] = Carbon::parse($order['updated_at']);
            return $order;
        });

        // 1. Daily Sales (Last 7 Days)
        $dailySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $dailySales[$dateStr] = $completedOrders->filter(function ($o) use ($dateStr) {
                return $o['date']->format('Y-m-d') === $dateStr;
            })->sum('total_amount');
        }

        // 2. Weekly Sales (Last 4 Weeks)
        $weeklySales = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            $label = 'Week ' . $startOfWeek->format('d/m');
            $weeklySales[$label] = $completedOrders->filter(function ($o) use ($startOfWeek, $endOfWeek) {
                return $o['date']->between($startOfWeek, $endOfWeek);
            })->sum('total_amount');
        }

        // 3. Monthly Sales (Last 6 Months)
        $monthlySales = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStr = $date->format('M Y');
            $monthlySales[$monthStr] = $completedOrders->filter(function ($o) use ($date) {
                return $o['date']->format('Y-m') === $date->format('Y-m');
            })->sum('total_amount');
        }

        // 4. Yearly Sales (Current Year)
        $yearlySales = $completedOrders->filter(function ($o) {
            return $o['date']->year === Carbon::now()->year;
        })->sum('total_amount');

        // 5. Top Sellers Aggregation
        $monthlyTopSellers = $this->getTopSellers($completedOrders->filter(fn($o) => $o['date']->isCurrentMonth()));
        $yearlyTopSellers = $this->getTopSellers($completedOrders->filter(fn($o) => $o['date']->isCurrentYear()));

        return view('admin.analytics', compact('dailySales', 'weeklySales', 'monthlySales', 'yearlySales', 'completedOrders', 'monthlyTopSellers', 'yearlyTopSellers'));
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
                        'quantity' => 0,
                        'total' => 0
                    ];
                }
                $sales[$pId]['quantity'] += ($item['quantity'] ?? 0);
                $sales[$pId]['total'] += (($item['price'] ?? 0) * ($item['quantity'] ?? 0));
            }
        }

        return collect($sales)->sortByDesc('quantity')->take(3);
    }
}
