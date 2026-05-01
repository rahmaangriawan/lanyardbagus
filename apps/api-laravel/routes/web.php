<?php

use App\Models\SiteSetting;
use App\Models\Banner;
use App\Models\Post;

Route::get('/', function () {
    $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();
    $banners = Banner::where('is_active', true)->orderBy('order')->get();
    $posts = Post::with(['featuredImage', 'category'])->published()->latest('published_at')->take(3)->get();
    
    return view('home', compact('settings', 'banners', 'posts'));
});

// ============================================================
// BLOG ARCHIVE ROUTES — must come BEFORE /blog/{slug}
// ============================================================

// Blog Archive (Semua Artikel)
Route::get('/blog', function () {
    $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();
    $query = Post::with(['featuredImage', 'category', 'author'])->published();
    
    $search = request('q');
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
        $archiveTitle = 'Hasil Pencarian: "' . $search . '"';
        $archiveDescription = 'Menampilkan hasil pencarian untuk "' . $search . '".';
    } else {
        $archiveTitle = 'Semua Artikel';
        $archiveDescription = 'Kumpulan artikel, panduan, dan wawasan terbaru seputar identitas profesional dari Lanyardbagus.';
    }

    $posts = $query->latest('published_at')->paginate(6);
    
    if (request()->ajax() || request('ajax')) {
        return response()->json([
            'posts_html'      => view('partials.posts-grid', compact('posts'))->render(),
            'pagination_html' => view('partials.pagination-numbers', compact('posts'))->render(),
        ]);
    }
    return view('blog', compact('settings', 'posts', 'archiveTitle', 'archiveDescription', 'search'));
});

// Blog Archive by Category
Route::get('/blog/category/{slug}', function ($slug) {
    $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();
    $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
    $posts = Post::with(['featuredImage', 'category', 'author'])->where('category_id', $category->id)->published()->latest('published_at')->paginate(6);
    $archiveTitle = 'Kategori: ' . $category->name;
    $archiveDescription = $category->description ?? 'Menampilkan artikel dalam kategori ' . $category->name . '.';
    if (request()->ajax() || request('ajax')) {
        return response()->json([
            'posts_html'      => view('partials.posts-grid', compact('posts'))->render(),
            'pagination_html' => view('partials.pagination-numbers', compact('posts'))->render(),
        ]);
    }
    return view('blog', compact('settings', 'posts', 'archiveTitle', 'archiveDescription', 'category'));
});

// Jenis Lanyard Landing Page
Route::get('/jenis', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('jenis', compact('settings'));
});

// Produk Landing Page
Route::get('/produk', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('produk', compact('settings'));
});

// Harga Landing Page
Route::get('/harga', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('harga', compact('settings'));
});

// FAQ Landing Page
Route::get('/faq', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('faq', compact('settings'));
});

// Static Pages
Route::get('/kontak', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('kontak', compact('settings'));
});

Route::get('/tentang', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('tentang', compact('settings'));
});

Route::get('/pengiriman', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('pengiriman', compact('settings'));
});

Route::get('/kebijakan', function () {
    $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
    return view('kebijakan', compact('settings'));
});

// Blog Archive by Tag
Route::get('/blog/tag/{slug}', function ($slug) {
    $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();
    $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
    $posts = $tag->posts()->with(['featuredImage', 'category', 'author'])->published()->latest('published_at')->paginate(6);
    $archiveTitle = 'Tag: ' . $tag->name;
    $archiveDescription = 'Menampilkan artikel dengan tag ' . $tag->name . '.';
    if (request()->ajax() || request('ajax')) {
        return response()->json([
            'posts_html'      => view('partials.posts-grid', compact('posts'))->render(),
            'pagination_html' => view('partials.pagination-numbers', compact('posts'))->render(),
        ]);
    }
    return view('blog', compact('settings', 'posts', 'archiveTitle', 'archiveDescription', 'tag'));
});

// ============================================================
// Authors List (Index)
// ============================================================
Route::get('/author', function () {
    $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();
    // Fetch users who have a username, with their post count and role
    $authors = \App\Models\User::whereNotNull('username')
        ->with('role')
        ->withCount(['posts' => function($query) {
            $query->published();
        }])
        ->orderBy('posts_count', 'desc')
        ->get();

    return view('authors', compact('settings', 'authors'));
});

// ============================================================
// Author Archive (standalone route, NOT under /blog/)
// ============================================================
Route::get('/author/{slug}', function ($slug) {
    $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();
    // Match by slugified username (spaces → dashes)
    $author = \App\Models\User::all()
        ->first(fn ($u) => \Illuminate\Support\Str::slug($u->username ?? '') === $slug);
    abort_if(!$author, 404);
    $posts = Post::with(['featuredImage', 'category', 'author'])->where('author_id', $author->id)->published()->latest('published_at')->paginate(6);
    if (request()->ajax() || request('ajax')) {
        return response()->json([
            'posts_html'      => view('partials.posts-grid', compact('posts'))->render(),
            'pagination_html' => view('partials.pagination-numbers', compact('posts'))->render(),
        ]);
    }
    return view('author', compact('settings', 'posts', 'author'));
});

// ============================================================
// Single Post — MUST be LAST to avoid swallowing the routes above
// ============================================================
Route::get('/blog/{slug}', function ($slug) {
    $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();
    $post = Post::with(['featuredImage', 'author', 'category', 'tags'])->where('slug', $slug)->firstOrFail();

    return view('post', compact('settings', 'post'));
});
