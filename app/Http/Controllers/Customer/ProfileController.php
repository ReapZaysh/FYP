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

        // Fetch vouchers
        $vouchers = $this->firebase->getVouchers($user->id)->filter(function($v) { 
            return !($v['is_used'] ?? false); 
        })->sortByDesc('created_at');

        return view('customer.profile', [
            'user' => $user,
            'recentOrders' => $userOrders,
            'vouchers' => $vouchers,
            'loyaltyPoints' => $user->loyalty_points ?? 0
        ]);
    }

    public function edit(Request $request)
    {
        return view('customer.profile-edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = $request->user();
        
        // Check for email uniqueness in Firebase
        if ($validated['email'] !== $user->email) {
            $existingUser = $this->firebase->getUserByEmail($validated['email']);
            if ($existingUser && $existingUser['id'] !== $user->id) {
                return back()->withErrors(['email' => 'The email has already been taken.']);
            }
        }

        $userData = $this->firebase->getUser($user->id);
        if (!$userData) {
            return back()->withErrors(['error' => 'User not found.']);
        }

        $userData['name'] = $validated['name'];
        $userData['email'] = $validated['email'];
        $userData['phone'] = $validated['phone'];
        $userData['updated_at'] = now()->toIso8601String();

        $this->firebase->saveUser($user->id, $userData);

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $userData = $this->firebase->getUser($user->id);
        
        if (!$userData) {
            return back()->withErrors(['error' => 'User not found.']);
        }

        $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $userData['updated_at'] = now()->toIso8601String();

        $this->firebase->saveUser($user->id, $userData);

        return back()->with('status', 'password-updated');
    }
}
