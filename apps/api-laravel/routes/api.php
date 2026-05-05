<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomizerController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RequireAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => ['status' => 'ok', 'service' => 'api']);

// Fallback for unauthenticated requests that try to redirect to 'login'
Route::get('/login', fn () => response()->json(['message' => 'Unauthenticated.'], 401))->name('login');

// Auth (stateful SPA via Sanctum session)
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// Debug login test
Route::post('/test-login', function (\Illuminate\Http\Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        return response()->json([
            'error' => 'User not found',
            'email' => $email,
            'available_users' => \App\Models\User::pluck('email')->toArray()
        ], 404);
    }
    
    $passwordCheck = password_verify($password, $user->password);
    
    if (!$passwordCheck) {
        return response()->json([
            'error' => 'Password mismatch',
            'message' => 'Password does not match hash'
        ], 401);
    }
    
    $attempt = \Auth::attempt(['email' => $email, 'password' => $password]);
    
    if ($attempt) {
        $request->session()->regenerate();
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'email' => $user->email
            ]
        ]);
    }
    
    return response()->json(['error' => 'Auth failed'], 401);
});

// User profile (self)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/me', [UserController::class, 'me']);
    Route::put('/users/me', [UserController::class, 'updateMe']);
});

// Public posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

// Public pages (by slug, only if published)
Route::get('/pages/{slug}', [PageController::class, 'publicShow']);
Route::get('/settings', [SiteSettingController::class, 'show']);
Route::get('/homepage', [HomepageController::class, 'show']);
Route::get('/banners', [BannerController::class, 'index']);

// Public list endpoints (for dashboard overview)
Route::get('/pages', [PageController::class, 'index']);
Route::get('/media', [MediaController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/tags', [TagController::class, 'index']);

// Public comments (per-post by slug)
Route::get('/posts/{slug}/comments',  [CommentController::class, 'publicIndex']);
Route::post('/posts/{slug}/comments', [CommentController::class, 'publicStore']);

// Public customizer (mockup uploader + lead capture)
Route::post('/customizer/upload', [CustomizerController::class, 'upload']);
Route::post('/customizer/leads',  [CustomizerController::class, 'storeLead']);

// Public chat API
Route::post('/chat/send', [ChatController::class, 'send']);
Route::get('/chat/history', [ChatController::class, 'history']);
Route::get('/chat/settings', [ChatController::class, 'getSettings']);

// Admin (requires authenticated session + admin/editor role)
Route::middleware(['auth:sanctum', RequireAdmin::class])->prefix('admin')->group(function () {
    Route::get('/me',       [AdminController::class, 'me']);
    Route::get('/overview', [AdminController::class, 'overview']);

    Route::apiResource('posts',      PostController::class);
    Route::apiResource('pages',      PageController::class);
    Route::apiResource('categories', CategoryController::class)->except(['show']);
    Route::apiResource('media',      MediaController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('tags',        TagController::class)->except(['show']);
    Route::get('/settings', [SiteSettingController::class, 'show']);
    Route::put('/settings', [SiteSettingController::class, 'update']);
    Route::get('/homepage', [HomepageController::class, 'show']);
    Route::put('/homepage', [HomepageController::class, 'update']);

    Route::post('/comments/bulk', [CommentController::class, 'bulk']);
    Route::get('/comments',              [CommentController::class, 'index']);
    Route::patch('/comments/{comment}',  [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    Route::get('/users',          [UserController::class, 'index']);
    Route::post('/users',         [UserController::class, 'store']);
    Route::patch('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}',[UserController::class, 'destroy']);

    Route::get('/lanyard-leads',          [CustomizerController::class, 'index']);
    Route::delete('/lanyard-leads/{lead}', [CustomizerController::class, 'destroy']);

    // Settings (R2, etc.)
    Route::get('/system-settings', [SettingController::class, 'index']);
    Route::put('/system-settings', [SettingController::class, 'update']);

    Route::get('/banners', [BannerController::class, 'adminIndex']);
    Route::post('/banners', [BannerController::class, 'store']);
    Route::get('/banners/{id}', [BannerController::class, 'show']);
    Route::put('/banners/{id}', [BannerController::class, 'update']);
    Route::delete('/banners/{id}', [BannerController::class, 'destroy']);
    // Route::apiResource('banners', BannerController::class)->except(['index']);

    Route::apiResource('redirects', RedirectController::class);

    // AI Chat Management
    Route::get('/chat/sessions', [ChatController::class, 'sessions']);
    Route::get('/chat/sessions/{sessionId}', [ChatController::class, 'sessionMessages']);
    Route::get('/chat/training-data', [ChatController::class, 'trainingData']);
    Route::patch('/chat/messages/{message}/training', [ChatController::class, 'markTraining']);
    Route::delete('/chat/messages/{message}', [ChatController::class, 'destroy']);
    Route::get('/chat/settings', [ChatController::class, 'getSettings']);
    Route::put('/chat/settings', [ChatController::class, 'updateSettings']);
    Route::post('/chat/test', [ChatController::class, 'testProvider']);
});
