<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;

class FirebaseService
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
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
}
