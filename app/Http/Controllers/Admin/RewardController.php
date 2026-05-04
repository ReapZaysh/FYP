<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RewardController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $rewards = $this->firebase->getRewards();
        return view('admin.rewards.index', compact('rewards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048'
        ]);

        $id = Str::uuid()->toString();
        $imageUrl = null;

        if ($request->hasFile('image')) {
            $imageUrl = $this->firebase->uploadImage($request->file('image'), 'rewards');
        }

        $rewardData = [
            'id' => $id,
            'name' => $request->name,
            'description' => $request->description,
            'points_required' => (int) $request->points_required,
            'image_url' => $imageUrl,
            'is_active' => true,
            'created_at' => now()->toIso8601String()
        ];

        $this->firebase->saveReward($id, $rewardData);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048'
        ]);

        $reward = $this->firebase->getReward($id);
        if (!$reward) {
            abort(404);
        }

        $imageUrl = $reward['image_url'] ?? null;

        if ($request->hasFile('image')) {
            if ($imageUrl) {
                $this->firebase->deleteImage($imageUrl);
            }
            $imageUrl = $this->firebase->uploadImage($request->file('image'), 'rewards');
        }

        $rewardData = [
            'id' => $id,
            'name' => $request->name,
            'description' => $request->description,
            'points_required' => (int) $request->points_required,
            'image_url' => $imageUrl,
            'is_active' => $request->has('is_active'),
            'updated_at' => now()->toIso8601String()
        ];

        // Merge to keep created_at
        $rewardData = array_merge($reward, $rewardData);

        $this->firebase->saveReward($id, $rewardData);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward updated successfully!');
    }

    public function destroy($id)
    {
        $reward = $this->firebase->getReward($id);
        if ($reward && !empty($reward['image_url'])) {
            $this->firebase->deleteImage($reward['image_url']);
        }
        
        $this->firebase->deleteReward($id);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward deleted successfully!');
    }
}
