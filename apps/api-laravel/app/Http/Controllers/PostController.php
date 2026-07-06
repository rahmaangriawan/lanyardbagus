<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $isAdmin = $request->user()?->isAdmin();
        
        // Cache public posts for 1 hour
        if (! $isAdmin) {
            $posts = \Illuminate\Support\Facades\Cache::remember('posts_public_list', 3600, function () {
                return Post::select('id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'created_at', 'featured_image_id', 'category_id')
                    ->with(['category:id,name', 'featuredImage:id,url'])
                    ->where('status', 'PUBLISHED')
                    ->latest()
                    ->get();
            });
            return response()->json($posts);
        }

        // Admin gets paginated data to avoid loading the whole post table at once.
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);
        $query = Post::select('id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'created_at', 'featured_image_id', 'category_id')
            ->with(['category:id,name', 'featuredImage:id,url'])
            ->latest();

        $posts = $query->paginate($perPage);

        return response()->json([
            'data' => $posts->items(),
            'links' => [
                'first' => $posts->url(1),
                'last' => $posts->url($posts->lastPage()),
                'prev' => $posts->previousPageUrl(),
                'next' => $posts->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'slug'               => ['nullable', 'string', 'max:191', 'unique:posts'],
            'excerpt'            => ['nullable', 'string'],
            'content'            => ['required', 'string'],
            'status'             => ['in:DRAFT,PUBLISHED'],
            'published_at'       => ['nullable', 'date'],
            'category_id'        => ['nullable', 'exists:categories,id'],
            'seo_title'          => ['nullable', 'string', 'max:255'],
            'meta_description'   => ['nullable', 'string', 'max:500'],
            'featured_image_id'  => ['nullable', 'exists:media,id'],
            'tag_ids'            => ['nullable', 'array'],
            'tag_ids.*'          => ['exists:tags,id'],
        ]);

        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $data['slug']      = $data['slug'] ?? Str::slug($data['title']);
        $data['author_id'] = $request->user()->id;

        if (($data['status'] ?? 'DRAFT') === 'PUBLISHED') {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        $post = Post::create($data);
        $post->tags()->sync($tagIds);

        \Illuminate\Support\Facades\Cache::forget('posts_public_list');

        return response()->json($post->load('tags:id,name,slug', 'featuredImage:id,url,file_name'), 201);
    }

    public function show(Post $post, Request $request): JsonResponse
    {
        if ($request->user()?->isAdmin()) {
            return response()->json(
                $post->load('author:id,email', 'category:id,name,slug', 'featuredImage:id,url,file_name', 'tags:id,name,slug')
            );
        }

        $cacheKey = "post_show_{$post->slug}";
        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($post) {
            return $post->load('author:id,email', 'category:id,name,slug', 'featuredImage:id,url,file_name', 'tags:id,name,slug');
        });

        return response()->json($data);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $data = $request->validate([
            'title'              => ['sometimes', 'string', 'max:255'],
            'slug'               => ['sometimes', 'string', 'max:191', 'unique:posts,slug,' . $post->id],
            'excerpt'            => ['nullable', 'string'],
            'content'            => ['sometimes', 'string'],
            'status'             => ['in:DRAFT,PUBLISHED'],
            'published_at'       => ['nullable', 'date'],
            'category_id'        => ['nullable', 'exists:categories,id'],
            'seo_title'          => ['nullable', 'string', 'max:255'],
            'meta_description'   => ['nullable', 'string', 'max:500'],
            'featured_image_id'  => ['nullable', 'exists:media,id'],
            'tag_ids'            => ['nullable', 'array'],
            'tag_ids.*'          => ['exists:tags,id'],
        ]);

        $tagIds = $data['tag_ids'] ?? null;
        unset($data['tag_ids']);

        if (isset($data['status']) && $data['status'] === 'PUBLISHED' && $post->status === 'DRAFT') {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        $post->update($data);
        if ($tagIds !== null) $post->tags()->sync($tagIds);

        \Illuminate\Support\Facades\Cache::forget('posts_public_list');
        \Illuminate\Support\Facades\Cache::forget("post_show_{$post->slug}");

        return response()->json($post->load('tags:id,name,slug', 'featuredImage:id,url,file_name'));
    }

    public function destroy(Post $post): JsonResponse
    {
        \Illuminate\Support\Facades\Cache::forget('posts_public_list');
        \Illuminate\Support\Facades\Cache::forget("post_show_{$post->slug}");
        
        $post->delete();

        return response()->json(['ok' => true]);
    }
}
