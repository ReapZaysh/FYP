<?php

namespace App\Http\Controllers\Admin;

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

    public function index()
    {
        $reviews = $this->firebase->getAllReviews();
        $products = collect($this->firebase->getProducts());
        
        // Attach product names to reviews
        $reviews = $reviews->map(function ($review) use ($products) {
            $product = $products->get($review['product_id']);
            $review['product_name'] = $product ? $product['name'] : 'Unknown Product';
            return $review;
        });

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy($productId, $code)
    {
        $this->firebase->deleteReview($productId, $code);
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
