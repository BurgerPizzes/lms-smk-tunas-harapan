<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaDiskusiController extends Controller
{
    /**
     * Add a comment or reply on materi/tugas.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'commentable_type' => ['required', 'string', 'in:App\\Models\\Materi,App\\Models\\Tugas'],
            'commentable_id'   => ['required', 'integer'],
            'parent_id'        => ['nullable', 'integer', 'exists:comments,id'],
            'body'             => ['required', 'string', 'max:5000'],
        ]);

        $comment = Comment::create([
            'user_id'          => Auth::id(),
            'commentable_type' => $validated['commentable_type'],
            'commentable_id'   => $validated['commentable_id'],
            'parent_id'        => $validated['parent_id'] ?? null,
            'body'             => $validated['body'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Komentar berhasil ditambahkan.',
                'comment' => $comment->load('user'),
            ], 201);
        }

        $model = $validated['commentable_type']::find($validated['commentable_id']);

        return match ($validated['commentable_type']) {
            'App\\Models\\Materi' => redirect()->route('siswa.materi.show', $model)->with('success', 'Komentar berhasil ditambahkan.'),
            'App\\Models\\Tugas'  => redirect()->route('siswa.tugas.show', $model)->with('success', 'Komentar berhasil ditambahkan.'),
            default => back()->with('success', 'Komentar berhasil ditambahkan.'),
        };
    }

    /**
     * Edit own comment.
     */
    public function update(Request $request, Comment $comment): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $siswa = Auth::user();

        if ($comment->user_id !== $siswa->id) {
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
                'message' => 'Komentar berhasil diperbarui.',
                'comment' => $comment->fresh(),
            ]);
        }

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }

    /**
     * Delete own comment.
     */
    public function destroy(Request $request, Comment $comment): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $siswa = Auth::user();

        if ($comment->user_id !== $siswa->id) {
            abort(403, 'Anda hanya dapat menghapus komentar sendiri.');
        }

        $comment->replies()->delete();
        $comment->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Komentar berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
