<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Add a comment on any commentable entity.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'commentable_type' => ['required', 'string'],
            'commentable_id'   => ['required', 'integer'],
            'parent_id'        => ['nullable', 'integer', 'exists:comments,id'],
            'body'             => ['required', 'string', 'max:5000'],
        ]);

        // Validate commentable type
        $allowedTypes = [
            \App\Models\Materi::class,
            \App\Models\Tugas::class,
        ];

        if (! in_array($validated['commentable_type'], $allowedTypes)) {
            return back()->withErrors('Tipe komentar tidak valid.');
        }

        // Verify the commentable entity exists
        $model = $validated['commentable_type']::find($validated['commentable_id']);
        if (! $model) {
            return back()->withErrors('Entitas tidak ditemukan.');
        }

        // Verify parent comment exists and belongs to the same entity
        if (! empty($validated['parent_id'])) {
            $parent = Comment::where('id', $validated['parent_id'])
                ->where('commentable_type', $validated['commentable_type'])
                ->where('commentable_id', $validated['commentable_id'])
                ->first();

            if (! $parent) {
                return back()->withErrors('Balasan tidak valid.');
            }
        }

        $comment = Comment::create([
            'user_id'          => Auth::id(),
            'commentable_type' => $validated['commentable_type'],
            'commentable_id'   => $validated['commentable_id'],
            'parent_id'        => $validated['parent_id'] ?? null,
            'body'             => $validated['body'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan.',
                'comment' => $comment->load('user'),
            ], 201);
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Edit a comment.
     */
    public function update(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if ($comment->user_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengedit komentar sendiri.');
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $comment->update([
            'body'      => $validated['body'],
            'is_edited' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil diperbarui.',
                'comment' => $comment->fresh(),
            ]);
        }

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        // Allow deletion if: own comment, or admin/guru
        $canDelete = $comment->user_id === $user->id
            || $user->hasRole('admin')
            || $user->hasRole('guru');

        if (! $canDelete) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        // Delete all replies
        $comment->replies()->delete();
        $comment->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    /**
     * Get comments for a commentable entity (JSON).
     */
    public function getComments(Request $request, string $commentableType, int $commentableId): JsonResponse
    {
        // Map short type names to full class names
        $typeMap = [
            'materi' => \App\Models\Materi::class,
            'tugas'  => \App\Models\Tugas::class,
        ];

        $modelClass = $typeMap[$commentableType] ?? $commentableType;

        // Verify entity exists
        $modelClass::findOrFail($commentableId);

        $comments = Comment::where('commentable_type', $modelClass)
            ->where('commentable_id', $commentableId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $comments,
        ]);
    }
}
