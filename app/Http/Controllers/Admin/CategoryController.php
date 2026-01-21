<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $categories = $this->firebase->getCategories();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sort_order' => (int) $request->sort_order,
        ];

        $id = $this->firebase->saveCategory(null, $data);
        // Also add the ID to the record itself for convenience
        $this->firebase->saveCategory($id, array_merge($data, ['id' => $id]));

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = $this->firebase->getCategory($id);
        if (!$category)
            abort(404);

        return view('admin.categories.edit', compact('category', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $data = [
            'id' => $id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sort_order' => (int) $request->sort_order,
        ];

        $this->firebase->saveCategory($id, $data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $this->firebase->deleteCategory($id);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
