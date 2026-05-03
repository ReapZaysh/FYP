<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index(Request $request)
    {
        $products = collect($this->firebase->getProducts());
        $categories = $this->firebase->getCategories();

        // Apply search filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $products = $products->filter(function ($product) use ($search) {
                return str_contains(strtolower($product['name'] ?? ''), $search);
            });
        }

        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $category = $request->category;
            $products = $products->filter(function ($product) use ($category) {
                return ($product['category_id'] ?? '') === $category;
            });
        }

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = $this->firebase->getCategories();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => (float) $request->price,
            'is_available' => $request->has('is_available'),
            'is_featured' => $request->has('is_featured'),
            'order_count' => 0,
        ];

        if ($request->hasFile('image')) {
            $url = $this->firebase->uploadImage($request->file('image'));
            $data['image_path'] = $url;
        }

        $id = $this->firebase->saveProduct(null, $data);
        $this->firebase->saveProduct($id, array_merge($data, ['id' => $id]));

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = $this->firebase->getProduct($id);
        if (!$product)
            abort(404);

        $categories = $this->firebase->getCategories();
        return view('admin.products.edit', compact('product', 'categories', 'id'));
    }

    public function update(Request $request, $id)
    {
        $product = $this->firebase->getProduct($id);
        if (!$product)
            abort(404);

        $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'id' => $id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => (float) $request->price,
            'is_available' => $request->has('is_available'),
            'is_featured' => $request->has('is_featured'),
            'order_count' => $product['order_count'] ?? 0,
            'image_path' => $product['image_path'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if (!empty($product['image_path'])) {
                // Determine if it's a local file or firebase url
                if (str_starts_with($product['image_path'], 'http')) {
                    $this->firebase->deleteImage($product['image_path']);
                } else {
                    Storage::disk('public')->delete($product['image_path']);
                }
            }
            $url = $this->firebase->uploadImage($request->file('image'));
            $data['image_path'] = $url;
        }

        $this->firebase->saveProduct($id, $data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = $this->firebase->getProduct($id);
        if ($product && !empty($product['image_path'])) {
            if (str_starts_with($product['image_path'], 'http')) {
                $this->firebase->deleteImage($product['image_path']);
            } else {
                Storage::disk('public')->delete($product['image_path']);
            }
        }

        $this->firebase->deleteProduct($id);
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
