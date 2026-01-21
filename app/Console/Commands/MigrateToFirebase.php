<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Hash;

class MigrateToFirebase extends Command
{
    protected $signature = 'db:migrate-to-firebase';
    protected $description = 'Migrate data from SQLite to Firebase Realtime Database';

    public function handle(FirebaseService $firebase)
    {
        $this->info('Starting migration to Firebase...');

        // Migrate Categories
        $categories = Category::all();
        $this->info('Migrating ' . $categories->count() . ' categories...');
        foreach ($categories as $cat) {
            $firebase->saveCategory($cat->id, [
                'id' => (string) $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'sort_order' => $cat->sort_order,
            ]);
        }

        // Migrate Products
        $products = Product::all();
        $this->info('Migrating ' . $products->count() . ' products...');
        foreach ($products as $prod) {
            $firebase->saveProduct($prod->id, [
                'id' => (string) $prod->id,
                'category_id' => (string) $prod->category_id,
                'name' => $prod->name,
                'description' => $prod->description,
                'price' => (float) $prod->price,
                'image_path' => $prod->image_path,
                'is_available' => (bool) $prod->is_available,
                'is_featured' => (bool) $prod->is_featured,
                'order_count' => (int) $prod->order_count,
            ]);
        }

        // Migrate Users
        $users = User::all();
        $this->info('Migrating ' . $users->count() . ' users...');
        foreach ($users as $user) {
            $firebase->saveUser($user->id, [
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password, // Already hashed
                'role' => $user->role,
            ]);
        }

        $this->info('Migration completed successfully!');
    }
}
