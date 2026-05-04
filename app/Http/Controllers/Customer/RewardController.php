<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $rewards = $this->firebase->getRewards()->where('is_active', true);
        $userPoints = auth()->check() ? (auth()->user()->loyalty_points ?? 0) : 0;

        return view('customer.rewards', compact('rewards', 'userPoints'));
    }

    public function redeem(Request $request, $id)
    {
        $user = auth()->user();
        $reward = $this->firebase->getReward($id);

        if (!$reward || !($reward['is_active'] ?? true)) {
            return redirect()->back()->with('error', 'This reward is currently unavailable.');
        }

        $currentPoints = $user->loyalty_points ?? 0;
        $pointsRequired = (int) $reward['points_required'];

        if ($currentPoints < $pointsRequired) {
            return redirect()->back()->with('error', 'You do not have enough Boss Points for this reward.');
        }

        // Deduct points
        $newPoints = $currentPoints - $pointsRequired;
        $this->firebase->saveUser($user->id, array_merge($user->toArray(), ['loyalty_points' => $newPoints]));

        // Record negative points history for redemption
        $database = app('firebase.database');
        $historyRef = $database->getReference('points_history/' . $user->id)->push();
        $historyRef->set([
            'id' => $historyRef->getKey(),
            'points' => -$pointsRequired,
            'reward_id' => $id,
            'reward_name' => $reward['name'],
            'status' => 'redeemed',
            'created_at' => now()->toIso8601String()
        ]);

        // Add a voucher to the user's account
        $voucherRef = $database->getReference('vouchers/' . $user->id)->push();
        $voucherRef->set([
            'id' => $voucherRef->getKey(),
            'reward_id' => $id,
            'reward_name' => $reward['name'],
            'is_used' => false,
            'created_at' => now()->toIso8601String()
        ]);

        return redirect()->route('customer.rewards')->with('success', 'You have successfully redeemed: ' . $reward['name'] . '! The voucher has been added to your account.');
    }
}
