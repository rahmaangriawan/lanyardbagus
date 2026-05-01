<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /** Admin list with filters */
    public function index(Request $request): JsonResponse
    {
        $q = Comment::query()
            ->with(['post:id,title,slug', 'parent:id,author_name'])
            ->orderByDesc('created_at');

        if ($request->filled('status') && $request->status !== 'ALL') {
            $q->where('status', $request->status);
        }
        if ($request->filled('post_id')) {
            $q->where('post_id', $request->post_id);
        }
        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $q->where(fn ($w) => $w->where('content', 'like', $term)
                ->orWhere('author_name', 'like', $term)
                ->orWhere('author_email', 'like', $term));
        }

        $perPage = min((int) $request->input('per_page', 20), 100);

        // Status counts (independent of current filter)
        $counts = Comment::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');

        return response()->json([
            'data'   => $q->paginate($perPage),
            'counts' => [
                'ALL'      => Comment::count(),
                'PENDING'  => $counts['PENDING']  ?? 0,
                'APPROVED' => $counts['APPROVED'] ?? 0,
                'SPAM'     => $counts['SPAM']     ?? 0,
                'TRASH'    => $counts['TRASH']    ?? 0,
            ],
        ]);
    }

    /** Admin update status / content */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        $data = $request->validate([
            'status'  => ['sometimes', 'in:PENDING,APPROVED,SPAM,TRASH'],
            'content' => ['sometimes', 'string'],
        ]);
        $comment->update($data);
        return response()->json($comment->load(['post:id,title,slug', 'parent:id,author_name']));
    }

    /** Admin delete (permanent) */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();
        return response()->json(['ok' => true]);
    }

    /** Admin bulk action */
    public function bulk(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids'    => ['required', 'array', 'min:1'],
            'ids.*'  => ['integer'],
            'action' => ['required', 'in:approve,pending,spam,trash,delete'],
        ]);

        $q = Comment::whereIn('id', $data['ids']);

        if ($data['action'] === 'delete') {
            $count = $q->delete();
            return response()->json(['ok' => true, 'affected' => $count]);
        }

        $statusMap = [
            'approve' => 'APPROVED',
            'pending' => 'PENDING',
            'spam'    => 'SPAM',
            'trash'   => 'TRASH',
        ];
        $count = $q->update(['status' => $statusMap[$data['action']]]);
        return response()->json(['ok' => true, 'affected' => $count]);
    }

    /** Public: list approved comments for a post (by slug) */
    public function publicIndex(Request $request, string $slug): JsonResponse
    {
        $post = Post::where('slug', $slug)->where('status', 'PUBLISHED')->firstOrFail();

        $comments = Comment::where('post_id', $post->id)
            ->where('status', 'APPROVED')
            ->orderBy('created_at')
            ->get(['id', 'parent_id', 'author_name', 'author_url', 'content', 'created_at']);

        return response()->json($comments);
    }

    /** Public: submit comment to a post (status: PENDING) */
    public function publicStore(Request $request, string $slug): JsonResponse
    {
        $post = Post::where('slug', $slug)->where('status', 'PUBLISHED')->firstOrFail();

        $data = $request->validate([
            'author_name'  => ['required', 'string', 'max:191'],
            'author_email' => ['required', 'email', 'max:191'],
            'author_url'   => ['nullable', 'url', 'max:255'],
            'content'      => ['required', 'string', 'min:2', 'max:5000'],
            'parent_id'    => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        // If parent provided, ensure it belongs to same post
        if (!empty($data['parent_id'])) {
            $parent = Comment::find($data['parent_id']);
            if (!$parent || $parent->post_id !== $post->id) {
                return response()->json(['message' => 'Komentar induk tidak valid.'], 422);
            }
        }

        $comment = Comment::create([
            ...$data,
            'post_id'    => $post->id,
            'status'     => 'PENDING',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return response()->json([
            'message' => 'Komentar terkirim dan menunggu moderasi.',
            'comment' => $comment->only(['id', 'author_name', 'content', 'created_at']),
        ], 201);
    }
}
