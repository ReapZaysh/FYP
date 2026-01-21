<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@bossku.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Staff
        User::factory()->create([
            'name' => 'Staff User',
            'username' => 'staff',
            'email' => 'staff@bossku.com',
            'role' => 'staff',
            'password' => bcrypt('password'),
        ]);

        // Categories
        $western = \App\Models\Category::create(['name' => 'Western Food', 'slug' => 'western-food', 'sort_order' => 1]);
        $local = \App\Models\Category::create(['name' => 'Local Delights', 'slug' => 'local-delights', 'sort_order' => 2]);
        $beverage = \App\Models\Category::create(['name' => 'Beverages', 'slug' => 'beverages', 'sort_order' => 3]);

        // Products
        \App\Models\Product::create([
            'category_id' => $western->id,
            'name' => 'Chicken Chop',
            'description' => 'Grilled chicken chop with black pepper sauce and fries.',
            'price' => 15.90,
            'is_available' => true,
            'is_featured' => true,
        ]);

        \App\Models\Product::create([
            'category_id' => $western->id,
            'name' => 'Fish and Chips',
            'description' => 'Crispy fried fish fillet with tartar sauce.',
            'price' => 14.90,
            'is_available' => true,
        ]);

        \App\Models\Product::create([
            'category_id' => $local->id,
            'name' => 'Nasi Lemak Special',
            'description' => 'Coconut milk rice with fried chicken, anchovies, peanuts, and sambal.',
            'price' => 12.50,
            'is_available' => true,
            'is_featured' => true,
        ]);

        \App\Models\Product::create([
            'category_id' => $beverage->id,
            'name' => 'Iced Milo',
            'description' => 'Chilled chocolate malt drink.',
            'price' => 4.50,
            'is_available' => true,
        ]);

        \App\Models\Product::create([
            'category_id' => $beverage->id,
            'name' => 'Fresh Orange Juice',
            'description' => 'Freshly squeezed orange juice.',
            'price' => 6.00,
            'is_available' => true,
        ]);
    }
}
