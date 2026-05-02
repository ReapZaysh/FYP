<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|boolean',
            'customer_name' => 'nullable|string|max:100'
        ]);

        $isAnonymous = $request->boolean('is_anonymous');
        
        $data = [
            'rating' => (int) $request->rating,
            'comment' => $request->comment,
            'is_anonymous' => $isAnonymous,
            'customer_name' => $isAnonymous ? 'Anonymous' : ($request->customer_name ?: 'Guest'),
        ];

        $reviewCode = $this->firebase->saveReview($productId, $data);

        return redirect()->back()->with('success', 'Thank you for your review! Reference code: ' . $reviewCode);
    }
}
