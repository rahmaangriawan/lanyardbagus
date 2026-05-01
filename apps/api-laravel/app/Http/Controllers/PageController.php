<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Page::latest();

        if ($request->boolean('all')) {
            return response()->json($query->select('id', 'title', 'slug', 'parent_id')->get());
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'slug'               => ['nullable', 'string', 'max:191', 'unique:pages'],
            'content'            => ['required', 'string'],
            'is_published'       => ['boolean'],
            'is_indexed'         => ['boolean'],
            'parent_id'          => ['nullable', 'exists:pages,id'],
            'seo_title'          => ['nullable', 'string', 'max:255'],
            'meta_description'   => ['nullable', 'string', 'max:500'],
            'featured_image_id'  => ['nullable', 'exists:media,id'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        return response()->json(Page::create($data), 201);
    }

    public function show(Page $page): JsonResponse
    {
        return response()->json(
            $page->load('parent:id,title,slug', 'children:id,title,slug,parent_id', 'featuredImage:id,url,file_name')
        );
    }

    public function update(Request $request, Page $page): JsonResponse
    {
        $data = $request->validate([
            'title'              => ['sometimes', 'string', 'max:255'],
            'slug'               => ['sometimes', 'string', 'max:191', 'unique:pages,slug,' . $page->id],
            'content'            => ['sometimes', 'string'],
            'is_published'       => ['boolean'],
            'is_indexed'         => ['boolean'],
            'parent_id'          => ['nullable', 'exists:pages,id'],
            'seo_title'          => ['nullable', 'string', 'max:255'],
            'meta_description'   => ['nullable', 'string', 'max:500'],
            'featured_image_id'  => ['nullable', 'exists:media,id'],
        ]);

        // Prevent circular parent reference
        if (!empty($data['parent_id']) && (int)$data['parent_id'] === $page->id) {
            unset($data['parent_id']);
        }

        $page->update($data);

        return response()->json($page->load('children:id,title,slug,parent_id'));
    }

    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return response()->json(['ok' => true]);
    }

    /** Public show by slug, only if published. */
    public function publicShow(string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->with('featuredImage:id,url,file_name')
            ->firstOrFail();

        return response()->json($page);
    }
}
