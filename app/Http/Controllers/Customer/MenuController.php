<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MenuController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        // Fetch all categories and products from Firebase
        $categories = $this->firebase->getCategories();
        $allProducts = $this->firebase->getProducts();

        // Group products by category and filter only available ones
        $categoriesWithProducts = $categories->map(function ($category, $id) use ($allProducts) {
            $category['products'] = $allProducts->where('category_id', $id)
                ->where('is_available', true)
                ->toArray();
            return $category;
        });

        // Get featured/trending products
        $featuredProducts = $allProducts->where('is_available', true)
            ->where('is_featured', true)
            ->toArray();

        // Sort trending by order count (overall)
        $trendingProducts = $allProducts->where('is_available', true)
            ->sortByDesc('order_count')
            ->take(5)
            ->toArray();

        // New Real-time Trending Logic from Orders
        $allOrders = $this->firebase->getOrders();
        $completedOrders = $allOrders->filter(fn($o) => $o['status'] === 'completed');

        $monthlyTop3Ids = $this->getTopProductIds($completedOrders->filter(fn($o) => Carbon::parse($o['updated_at'])->isCurrentMonth()));
        $yearlyTop3Ids = $this->getTopProductIds($completedOrders->filter(fn($o) => Carbon::parse($o['updated_at'])->isCurrentYear()));

        $monthlyTrending = $allProducts->only($monthlyTop3Ids)->where('is_available', true)->toArray();
        $yearlyChoice = $allProducts->only($yearlyTop3Ids)->where('is_available', true)->toArray();

        return view('customer.menu', compact(
            'categoriesWithProducts',
            'featuredProducts',
            'trendingProducts',
            'monthlyTrending',
            'yearlyChoice'
        ));
    }

    protected function getTopProductIds($orders)
    {
        $sales = [];
        foreach ($orders as $order) {
            if (!isset($order['items']) || !is_array($order['items']))
                continue;
            foreach ($order['items'] as $item) {
                if (!isset($item['product_id']))
                    continue;
                $pId = $item['product_id'];
                $sales[$pId] = ($sales[$pId] ?? 0) + ($item['quantity'] ?? 0);
            }
        }
        arsort($sales);
        return array_keys(array_slice($sales, 0, 3, true));
    }
}
