<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;

class FirebaseService
{
    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }


    // --- Users ---

    public function getUser($id)
    {
        return $this->database->getReference('users/' . $id)->getValue();
    }

    public function getUserByEmail($email)
    {
        $users = $this->database->getReference('users')->getValue();
        if (!$users)
            return null;

        foreach ($users as $user) {
            if (($user['email'] ?? '') === $email) {
                return $user;
            }
        }
        return null;
    }

    public function saveUser($id, $data)
    {
        $this->database->getReference('users/' . $id)->set($data);
    }

    // --- Categories ---

    public function getCategories()
    {
        $data = $this->database->getReference('categories')->getValue();
        return $data ? collect($data)->filter()->sortBy('sort_order') : collect([]);
    }

    public function getCategory($id)
    {
        return $this->database->getReference('categories/' . $id)->getValue();
    }

    public function saveCategory($id, $data)
    {
        if (!$id) {
            $id = (string) Str::uuid();
        }
        $this->database->getReference('categories/' . $id)->set($data);
        return $id;
    }

    public function deleteCategory($id)
    {
        $this->database->getReference('categories/' . $id)->remove();
    }

    // --- Products ---

    public function getProducts($categoryId = null)
    {
        $data = $this->database->getReference('products')->getValue();
        $products = $data ? collect($data)->filter() : collect([]);

        if ($categoryId) {
            return $products->where('category_id', $categoryId);
        }

        return $products;
    }

    public function getProduct($id)
    {
        return $this->database->getReference('products/' . $id)->getValue();
    }

    public function saveProduct($id, $data)
    {
        if (!$id) {
            $id = (string) Str::uuid();
        }
        $this->database->getReference('products/' . $id)->set($data);
        return $id;
    }

    public function deleteProduct($id)
    {
        $this->database->getReference('products/' . $id)->remove();
    }

    // --- Orders ---

    public function syncOrder($orderData)
    {
        $ref = $orderData['reference'];
        $this->database->getReference('orders/' . $ref)->set($orderData);
    }

    public function getOrders($status = null)
    {
        $data = $this->database->getReference('orders')->getValue();
        $orders = $data ? collect($data)->filter() : collect([]);

        if ($status) {
            return $orders->where('status', $status);
        }

        return $orders;
    }

    public function getOrder($reference)
    {
        return $this->database->getReference('orders/' . $reference)->getValue();
    }

    public function updateOrderStatus($reference, $status)
    {
        $this->database->getReference('orders/' . $reference)
            ->update([
                'status' => $status,
                'updated_at' => now()->toIso8601String(),
            ]);
    }

    public function updateOrderPayment($reference, $paymentStatus)
    {
        $this->database->getReference('orders/' . $reference)
            ->update([
                'payment_status' => $paymentStatus,
                'paid_at' => now()->toIso8601String(),
            ]);
    }

    // --- Reviews ---

    public function getReviews($productId)
    {
        $data = $this->database->getReference('reviews/' . $productId)->getValue();
        return $data ? collect($data)->filter()->sortByDesc('created_at') : collect([]);
    }

    public function saveReview($productId, $data)
    {
        $reviewCode = 'REV-' . strtoupper(Str::random(5));
        $data['code'] = $reviewCode;
        $data['created_at'] = now()->toIso8601String();
        
        $this->database->getReference('reviews/' . $productId . '/' . $reviewCode)->set($data);
        return $reviewCode;
    }

    public function getAllReviews()
    {
        $data = $this->database->getReference('reviews')->getValue();
        $allReviews = collect([]);
        
        if ($data && (is_array($data) || is_object($data))) {
            foreach ($data as $productId => $reviews) {
                if ($reviews && (is_array($reviews) || is_object($reviews))) {
                    foreach ($reviews as $code => $review) {
                        $review['product_id'] = $productId;
                        $review['code'] = $code;
                        $allReviews->push($review);
                    }
                }
            }
        }
        
        return $allReviews->sortByDesc('created_at');
    }
    
    public function deleteReview($productId, $reviewCode)
    {
        $this->database->getReference('reviews/' . $productId . '/' . $reviewCode)->remove();
    }

    // --- Storage ---

    public function uploadImage($file, $pathPrefix = 'products')
    {
        $bucket = $this->storage->getBucket();
        $filename = $pathPrefix . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        $bucket->upload(
            fopen($file->getPathname(), 'r'),
            [
                'name' => $filename,
                'predefinedAcl' => 'publicRead' // Makes the file publicly accessible
            ]
        );

        // Return the public URL
        $bucketName = $bucket->name();
        return "https://firebasestorage.googleapis.com/v0/b/{$bucketName}/o/" . urlencode($filename) . "?alt=media";
    }

    public function deleteImage($url)
    {
        if (empty($url) || !str_contains($url, 'firebasestorage.googleapis.com')) {
            return;
        }

        // Extract the path from the URL
        $bucketName = $this->storage->getBucket()->name();
        $pattern = "/https:\/\/firebasestorage\.googleapis\.com\/v0\/b\/{$bucketName}\/o\/(.*?)\?alt=media/";
        
        if (preg_match($pattern, $url, $matches)) {
            $path = urldecode($matches[1]);
            $this->storage->getBucket()->object($path)->delete();
        }
    }
}
