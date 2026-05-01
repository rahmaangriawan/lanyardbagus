<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of active banners for public view.
     */
    public function index()
    {
        try {
            $banners = Banner::where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();
            return response()->json($banners);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of all banners for admin.
     */
    public function adminIndex()
    {
        try {
            $banners = Banner::orderBy('order', 'asc')->get();
            return response()->json($banners);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'image_url' => 'required|string',
            'card_image_url' => 'nullable|string',
            'link_url' => 'nullable|string',
            'button_text' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $banner = Banner::create($validated);
        return response()->json($banner, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'image_url' => 'required|string',
            'card_image_url' => 'nullable|string',
            'link_url' => 'nullable|string',
            'button_text' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $banner->update($validated);
        return response()->json($banner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return response()->json(null, 204);
    }
}
