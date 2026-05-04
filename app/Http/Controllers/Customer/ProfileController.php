<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Fetch all orders and filter by the current user's ID
        $allOrders = $this->firebase->getOrders();
        
        // Assuming orders have a 'customer_id' we can match. 
        // We will need to ensure new orders from logged-in users have their customer_id saved.
        $userOrders = $allOrders->filter(function($order) use ($user) {
            return ($order['customer_id'] ?? null) === $user->id;
        })->sortByDesc('created_at')->take(5); // Get latest 5 orders

        return view('customer.profile', [
            'user' => $user,
            'recentOrders' => $userOrders,
            'loyaltyPoints' => $user->loyalty_points ?? 0
        ]);
    }
}
