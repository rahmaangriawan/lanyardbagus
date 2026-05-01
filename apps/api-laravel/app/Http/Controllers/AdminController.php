<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');

        return response()->json([
            'user' => [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role?->name,
            ],
        ]);
    }

    public function overview(): JsonResponse
    {
        $postsTotal     = Post::count();
        $pagesTotal     = Page::count();
        $mediaTotal     = Media::count();
        $usersTotal     = User::count();
        $drafts         = Post::where('status', 'DRAFT')->count();
        $published      = Post::where('status', 'PUBLISHED')->count();
        $pagesPublished = Page::where('is_published', true)->count();
        $pagesDraft     = Page::where('is_published', false)->count();

        $recentPosts = Post::select('id', 'title', 'slug', 'status', 'published_at', 'created_at')
            ->latest()
            ->limit(5)
            ->get();

        $recentPages = Page::select('id', 'title', 'slug', 'is_published', 'created_at')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'posts'          => $postsTotal,
            'pages'          => $pagesTotal,
            'media'          => $mediaTotal,
            'users'          => $usersTotal,
            'drafts'         => $drafts,
            'published'      => $published,
            'pagesPublished' => $pagesPublished,
            'pagesDraft'     => $pagesDraft,
            'recentPosts'    => $recentPosts,
            'recentPages'    => $recentPages,
        ]);
    }
}
