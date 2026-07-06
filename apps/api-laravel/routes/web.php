<?php

use App\Models\SiteSetting;
use App\Models\Banner;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    $banners = Cache::remember('homepage_active_banners', 3600, function () {
        return Banner::where('is_active', true)->orderBy('order')->get();
    });
    $posts = Cache::remember('homepage_latest_posts', 3600, function () {
        return Post::select('id', 'title', 'slug', 'excerpt', 'published_at', 'featured_image_id', 'category_id')
            ->with(['featuredImage:id,url,file_name', 'category:id,name'])
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();
    });
    
    return view('home', compact('banners', 'posts'));
});

// TEMPORARY: Route to run migrations on shared hosting without SSH
Route::get('/migrate-db-production', function () {
    abort_unless(app()->environment('local'), 404);

    $output = '<h3>Migration Results:</h3><br>';
    $files = \Illuminate\Support\Facades\File::files(database_path('migrations'));
    
    // Sort files by name so they run in the correct order
    $filenames = array_map(function($file) { return $file->getFilename(); }, $files);
    sort($filenames);
    
    foreach ($filenames as $filename) {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--path' => 'database/migrations/' . $filename,
                '--force' => true
            ]);
            $res = \Illuminate\Support\Facades\Artisan::output();
            if (trim($res) !== 'Nothing to migrate.') {
                $output .= "<span style='color:green'>Migrated: $filename</span><br>";
            }
        } catch (\Exception $e) {
            $output .= "<span style='color:orange'>Skipped (Already exists/Failed): $filename</span><br>";
        }
    }
    return $output . '<br><strong>Done! Silakan cek kembali fitur yang sebelumnya error.</strong>';
});

// TEMPORARY: Create storage symlink (run once on production)
Route::get('/storage-link-production', function () {
    abort_unless(app()->environment('local'), 404);

    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Storage link created: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return 'Storage link: ' . $e->getMessage();
    }
});

// TEMPORARY: Sync media files from storage into DB (untuk recover media lama)
Route::get('/sync-media-production', function () {
    abort_unless(app()->environment('local'), 404);

    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    $files = $disk->files('media');
    $count = 0;
    $skipped = 0;
    $output = '<h3>Media Sync Results:</h3><br>';

    foreach ($files as $path) {
        $filename = basename($path);
        // Skip if already in DB
        if (\App\Models\Media::where('file_name', $filename)->exists()) {
            $skipped++;
            continue;
        }
        $url = asset('storage/' . $path);
        $mime = 'image/webp';
        if (str_ends_with($filename, '.jpg') || str_ends_with($filename, '.jpeg')) $mime = 'image/jpeg';
        elseif (str_ends_with($filename, '.png')) $mime = 'image/png';
        elseif (str_ends_with($filename, '.gif')) $mime = 'image/gif';
        elseif (str_ends_with($filename, '.pdf')) $mime = 'application/pdf';
        $size = $disk->size($path);
        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $altText = ucwords(str_replace(['-', '_'], ' ', $baseName));
        try {
            $fullPath = storage_path('app/public/' . $path);
            [$w, $h] = str_starts_with($mime, 'image/') && function_exists('getimagesize')
                ? (@getimagesize($fullPath) ?: [null, null])
                : [null, null];
        } catch (\Exception $e) { $w = null; $h = null; }
        \App\Models\Media::create([
            'file_name' => $filename,
            'url'       => $url,
            'mime_type' => $mime,
            'width'     => $w,
            'height'    => $h,
            'size'      => $size,
            'alt_text'  => $altText,
        ]);
        $output .= "<span style='color:green'>Imported: $filename</span><br>";
        $count++;
    }
    return $output . "<br><strong>Done! Imported: $count baru, Skipped: $skipped sudah ada.</strong>";
});

// TEMPORARY: Clear all caches
Route::get('/clear-cache-production', function () {
    abort_unless(app()->environment('local'), 404);

    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        return '<h3>Cache Cleared!</h3><p>All caches have been cleared.</p>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// TEMPORARY: Debug site verification
Route::get('/debug-settings', function () {
    abort_unless(app()->environment('local'), 404);

    $settings = SiteSetting::first();
    $systemGsc = \App\Models\Setting::get('google_search_console');
    $systemBing = \App\Models\Setting::get('bing_webmaster');

    return [
        'site_settings_table' => [
            'google_site_verification' => $settings->google_site_verification ?? 'NULL',
            'bing_site_verification' => $settings->bing_site_verification ?? 'NULL',
            'site_title' => $settings->site_title ?? 'NULL',
            'has_settings' => $settings ? true : false,
        ],
        'system_settings_table' => [
            'google_search_console' => $systemGsc ?: 'NULL',
            'bing_webmaster' => $systemBing ?: 'NULL',
        ],
        'blade_will_use' => [
            'google' => $settings->google_site_verification ?? $systemGsc ?? 'NULL (no meta tag will render)',
            'bing' => $settings->bing_site_verification ?? $systemBing ?? 'NULL (no meta tag will render)',
        ],
        'timestamp' => now()->toDateTimeString(),
    ];
});

// TEMPORARY: Test meta tag rendering directly
Route::get('/debug-meta', function () {
    abort_unless(app()->environment('local'), 404);

    $settings = SiteSetting::first();
    $gsc = $settings->google_site_verification ?? \App\Models\Setting::get('google_search_console') ?? null;
    $bing = $settings->bing_site_verification ?? \App\Models\Setting::get('bing_webmaster') ?? null;

    $html = '<h1>Meta Tag Test</h1>';
    $html .= '<pre>';
    if ($gsc) {
        $html .= htmlspecialchars('<meta name="google-site-verification" content="' . $gsc . '">') . "\n";
    } else {
        $html .= "GOOGLE: NO META TAG (value is null)\n";
    }
    if ($bing) {
        $html .= htmlspecialchars('<meta name="msvalidate.01" content="' . $bing . '">') . "\n";
    } else {
        $html .= "BING: NO META TAG (value is null)\n";
    }
    $html .= '</pre>';
    $html .= '<hr><a href="/">Back to Home</a>';
    return $html;
});

// Katalog Produk
Route::get('/katalog', [App\Http\Controllers\ProductController::class, 'katalogIndex']);
Route::get('/katalog/{slug}', [App\Http\Controllers\ProductController::class, 'katalogCategory']);

// ============================================================
// BLOG ARCHIVE ROUTES — must come BEFORE /blog/{slug}
// ============================================================

// Blog Archive (Semua Artikel)
Route::get('/blog', function () {
    $query = Post::select('id', 'title', 'slug', 'excerpt', 'content', 'status', 'published_at', 'created_at', 'featured_image_id', 'category_id', 'author_id')
        ->with(['featuredImage:id,url,file_name', 'category:id,name,slug', 'author:id,name,username,avatar_url'])
        ->published();
    
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
    return view('blog', compact('posts', 'archiveTitle', 'archiveDescription', 'search'));
});

// Blog Archive by Category
Route::get('/blog/category/{slug}', function ($slug) {
    $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
    $posts = Post::select('id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'created_at', 'featured_image_id', 'category_id', 'author_id')
        ->with(['featuredImage:id,url,file_name', 'category:id,name,slug', 'author:id,name,username,avatar_url'])
        ->where('category_id', $category->id)
        ->published()
        ->latest('published_at')
        ->paginate(6);
    $archiveTitle = 'Kategori: ' . $category->name;
    $archiveDescription = $category->description ?? 'Menampilkan artikel dalam kategori ' . $category->name . '.';
    if (request()->ajax() || request('ajax')) {
        return response()->json([
            'posts_html'      => view('partials.posts-grid', compact('posts'))->render(),
            'pagination_html' => view('partials.pagination-numbers', compact('posts'))->render(),
        ]);
    }
    return view('blog', compact('posts', 'archiveTitle', 'archiveDescription', 'category'));
});

// Jenis Lanyard Landing Page
Route::get('/jenis', function () {
    return view('jenis');
});

// Produk Landing Page
Route::get('/produk', function () {
    return view('produk');
});

// Harga Landing Page
Route::get('/harga', function () {
    return view('harga');
});

// FAQ Landing Page
Route::get('/faq', function () {
    return view('faq');
});

// Promo Page
Route::get('/promo', function () {
    return view('promo');
});

// Static Pages
Route::get('/kontak', function () {
    return view('kontak');
});

Route::get('/tentang', function () {
    return view('tentang');
});

Route::get('/pengiriman', function () {
    return view('pengiriman');
});

Route::get('/kebijakan', function () {
    return view('kebijakan');
});

// Order / Payment Page
Route::get('/order', function () {
    return view('order');
});

// Blog Archive by Tag
Route::get('/blog/tag/{slug}', function ($slug) {
    $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
    $posts = $tag->posts()
        ->select('posts.id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'posts.created_at', 'featured_image_id', 'category_id', 'author_id')
        ->with(['featuredImage:id,url,file_name', 'category:id,name,slug', 'author:id,name,username,avatar_url'])
        ->published()
        ->latest('published_at')
        ->paginate(6);
    $archiveTitle = 'Tag: ' . $tag->name;
    $archiveDescription = 'Menampilkan artikel dengan tag ' . $tag->name . '.';
    if (request()->ajax() || request('ajax')) {
        return response()->json([
            'posts_html'      => view('partials.posts-grid', compact('posts'))->render(),
            'pagination_html' => view('partials.pagination-numbers', compact('posts'))->render(),
        ]);
    }
    return view('blog', compact('posts', 'archiveTitle', 'archiveDescription', 'tag'));
});

// ============================================================
// Authors List (Index)
// ============================================================
Route::get('/author', function () {
    // Fetch users who have a username, with their post count and role
    $authors = \App\Models\User::whereNotNull('username')
        ->with('role')
        ->withCount(['posts' => function($query) {
            $query->published();
        }])
        ->orderBy('posts_count', 'desc')
        ->get();

    return view('authors', compact('authors'));
});

// ============================================================
// Author Archive (standalone route, NOT under /blog/)
// ============================================================
Route::get('/author/{slug}', function ($slug) {
    // Match by slugified username (spaces → dashes)
    $author = \App\Models\User::select('id', 'name', 'username', 'email', 'bio', 'avatar_url', 'created_at')
        ->whereNotNull('username')
        ->get()
        ->first(fn ($u) => \Illuminate\Support\Str::slug($u->username ?? '') === $slug);
    abort_if(!$author, 404);
    $posts = Post::select('id', 'title', 'slug', 'excerpt', 'status', 'published_at', 'created_at', 'featured_image_id', 'category_id', 'author_id')
        ->with(['featuredImage:id,url,file_name', 'category:id,name,slug', 'author:id,name,username,avatar_url'])
        ->where('author_id', $author->id)
        ->published()
        ->latest('published_at')
        ->paginate(6);
    if (request()->ajax() || request('ajax')) {
        return response()->json([
            'posts_html'      => view('partials.posts-grid', compact('posts'))->render(),
            'pagination_html' => view('partials.pagination-numbers', compact('posts'))->render(),
        ]);
    }
    return view('author', compact('posts', 'author'));
});

// ============================================================
// SITEMAP HTML (Human-readable)
// ============================================================
Route::get('/sitemap', function () {
    $posts = Post::select('title', 'slug', 'updated_at', 'featured_image_id')
        ->with('featuredImage:id,url')
        ->published()
        ->latest('updated_at')
        ->get();
    $categories = \App\Models\Category::select('name', 'slug')->orderBy('name')->get();
    $statics = [
        'jenis', 'produk', 'harga', 'faq', 'kontak', 'tentang', 'pengiriman', 'kebijakan', 'blog'
    ];

    return view('sitemap', compact('posts', 'categories', 'statics'));
});

// ============================================================
// SITEMAP XML (For search engines)
// ============================================================
Route::get('/sitemap.xml', function () {
    $posts = Post::select('slug', 'updated_at')->published()->latest('updated_at')->get();
    $categories = \App\Models\Category::select('slug')->orderBy('slug')->get();
    $statics = [
        'jenis', 'produk', 'harga', 'faq', 'kontak', 'tentang', 'pengiriman', 'kebijakan', 'blog'
    ];

    return response()->view('sitemap-xml', compact('posts', 'categories', 'statics'))
        ->header('Content-Type', 'text/xml');
});

// ============================================================
// Single Post — MUST be LAST to avoid swallowing the routes above
// ============================================================
Route::get('/blog/{slug}', function ($slug) {
    $post = Post::with(['featuredImage', 'author', 'category', 'tags'])->where('slug', $slug)->firstOrFail();

    return view('post', compact('post'));
});

// ============================================================
// 404 FALLBACK — Check redirects before showing 404
// ============================================================
Route::fallback(function (\Illuminate\Http\Request $request) {
    $path = '/' . ltrim($request->path(), '/');

    $redirect = \App\Models\Redirect::where('from_url', $path)
        ->where('is_active', true)
        ->first();

    if ($redirect) {
        return redirect($redirect->to_url, $redirect->status_code);
    }

    abort(404);
});
