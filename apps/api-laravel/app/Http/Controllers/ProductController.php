<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ==================== PUBLIC ====================

    /**
     * Show all active products on katalog index
     */
    public function katalogIndex()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();

        return view('katalog', compact('products', 'settings'));
    }

    /**
     * Show products in a category
     */
    public function katalogCategory(string $slug)
    {
        $category = ProductCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $category->activeProducts()->get();

        $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();

        return view('katalog-category', compact('category', 'products', 'settings'));
    }

    /**
     * Get single product (API for order page)
     */
    public function show(string $id)
    {
        $product = Product::with('category')->where('is_active', true)->findOrFail($id);
        return response()->json($product);
    }

    /**
     * List all active products with category (public, no auth)
     */
    public function publicProducts(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order');

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->get());
    }

    // ==================== ADMIN CRUD ====================

    /**
     * List all categories (admin)
     */
    public function adminCategories(Request $request)
    {
        $query = ProductCategory::withCount('products')->orderBy('sort_order');

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json($query->get());
    }

    /**
     * Store a new category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $category = ProductCategory::create($validated);

        return response()->json($category, 201);
    }

    /**
     * Update a category
     */
    public function updateCategory(Request $request, string $id)
    {
        $category = ProductCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'slug' => 'string|max:255|unique:product_categories,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $category->update($validated);

        return response()->json($category->fresh());
    }

    /**
     * Delete a category
     */
    public function destroyCategory(string $id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true]);
    }

    /**
     * List all products (admin), optionally filtered by category
     */
    public function adminProducts(Request $request)
    {
        $query = Product::with('category')->orderBy('sort_order');

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json($query->get());
    }

    /**
     * Store a new product
     */
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'min_order' => 'integer|min:1',
            'specs' => 'nullable|array',
            'images' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load('category'), 201);
    }

    /**
     * Update a product
     */
    public function updateProduct(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'exists:product_categories,id',
            'name' => 'string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'integer|min:0',
            'min_order' => 'integer|min:1',
            'specs' => 'nullable|array',
            'images' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $product->update($validated);

        return response()->json($product->fresh()->load('category'));
    }

    /**
     * Delete a product
     */
    public function destroyProduct(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => true]);
    }
}
