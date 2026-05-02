<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index(Request $request)
    {
        $range = $request->get('range', 7); // Default to 7 days
        $allOrders = $this->firebase->getOrders();
        $products = $this->firebase->getProducts();
        $categories = $this->firebase->getCategories();

        $completedOrders = $allOrders->filter(function ($order) {
            return ($order['status'] ?? '') === 'completed';
        })->map(function ($order) {
            $order['date'] = Carbon::parse($order['updated_at']);
            return $order;
        });

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. Summary Stats (Always compared to yesterday)
        $todayOrders = $completedOrders->filter(fn($o) => $o['date']->isToday());
        $yesterdayOrders = $completedOrders->filter(fn($o) => $o['date']->isYesterday());

        $todayRevenue = round($todayOrders->sum('total_amount'), 2);
        $yesterdayRevenue = round($yesterdayOrders->sum('total_amount'), 2);
        $revenueGrowth = $yesterdayRevenue > 0 ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 2) : 0;

        $stats = [
            'revenue' => [
                'value' => $todayRevenue,
                'growth' => $revenueGrowth,
                'trend' => $revenueGrowth >= 0 ? 'up' : 'down'
            ],
            'orders' => [
                'value' => $todayOrders->count(),
                'growth' => $yesterdayOrders->count() > 0 ? (($todayOrders->count() - $yesterdayOrders->count()) / $yesterdayOrders->count()) * 100 : 0,
                'trend' => $todayOrders->count() >= $yesterdayOrders->count() ? 'up' : 'down'
            ],
            'avg_order' => [
                'value' => $todayOrders->count() > 0 ? round($todayRevenue / $todayOrders->count(), 2) : 0,
                'growth' => 0, // Simplified
                'trend' => 'up'
            ],
            'active_products' => [
                'value' => $products->where('is_available', true)->count(),
                'low_stock' => $products->where('is_available', true)->count() < 5 ? 3 : 0 // Mocking low stock for UI
            ]
        ];

        // 2. Daily Sales (Based on selected Range)
        $dailySales = [];
        $iterations = (int)$range - 1;
        for ($i = $iterations; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('M d');
            $dailySales[$dateStr] = round($completedOrders->filter(fn($o) => $o['date']->isSameDay($date))->sum('total_amount'), 2);
        }

        // 3. Top Products
        $topProducts = $this->getTopSellers($completedOrders)->take(5);

        // 4. Category Performance
        $categoryData = [];
        foreach ($categories as $catId => $cat) {
            $catRevenue = $completedOrders->sum(function ($order) use ($catId) {
                return round(collect($order['items'] ?? [])->where('category_id', $catId)->sum(fn($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 0)), 2);
            });
            if ($catRevenue > 0) {
                $categoryData[$cat['name']] = $catRevenue;
            }
        }
        arsort($categoryData);

        // 5. Recent Orders
        $recentOrders = $allOrders->sortByDesc('updated_at')->take(5);

        return view('admin.dashboard', compact('stats', 'dailySales', 'topProducts', 'categoryData', 'recentOrders', 'range'));
    }

    protected function getTopSellers($orders)
    {
        $sales = [];
        foreach ($orders as $order) {
            foreach ($order['items'] ?? [] as $item) {
                $pId = $item['product_id'] ?? 'unknown';
                if (!isset($sales[$pId])) {
                    $sales[$pId] = [
                        'name' => $item['name'] ?? 'Unknown',
                        'quantity' => 0,
                        'total' => 0,
                        'image' => $item['image'] ?? null
                    ];
                }
                $sales[$pId]['quantity'] += ($item['quantity'] ?? 0);
                $sales[$pId]['total'] += round(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2);
            }
        }
        return collect($sales)->sortByDesc('total');
    }
}
