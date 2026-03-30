<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiMateriController extends Controller
{
    /**
     * List materi in a class.
     */
    public function index(Request $request, Kelas $kelas): JsonResponse
    {
        $query = Materi::where('class_id', $kelas->id)
            ->with('mapel', 'guru');

        // Siswa only sees published materi
        if ($request->user()->hasRole('siswa')) {
            $query->where('is_published', true);
        }

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('judul', 'like', "%{$search}%");
        }

        $materis = $query->orderBy('urutan')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $materis,
        ]);
    }

    /**
     * Get materi detail.
     */
    public function show(Request $request, Materi $materi): JsonResponse
    {
        if ($request->user()->hasRole('siswa') && ! $materi->is_published) {
            return response()->json(['message' => 'Materi tidak ditemukan.'], 404);
        }

        $materi->load(['kelas', 'mapel', 'guru', 'comments.user']);

        return response()->json([
            'success' => true,
            'data'    => $materi,
        ]);
    }

    /**
     * Create new materi (guru only).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('guru')) {
            return response()->json(['message' => 'Hanya guru yang dapat membuat materi.'], 403);
        }

        $validated = $request->validate([
            'class_id'     => ['required', 'exists:kelas,id'],
            'judul'        => ['required', 'string', 'max:255'],
            'mapel_id'     => ['required', 'exists:mapels,id'],
            'konten'       => ['required', 'string'],
            'video_url'    => ['nullable', 'string', 'url', 'max:500'],
            'file'         => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar'],
            'is_published' => ['boolean'],
        ]);

        $validated['guru_id'] = $user->id;
        $validated['is_published'] = $validated['is_published'] ?? true;
        $validated['urutan'] = Materi::where('class_id', $validated['class_id'])->max('urutan') + 1;

        // Handle file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('materi/' . $validated['class_id'], $filename, 'public');
            $validated['file_path'] = $path;
        }

        $materi = Materi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dibuat.',
            'data'    => $materi,
        ], 201);
    }

    /**
     * Update materi (guru only).
     */
    public function update(Request $request, Materi $materi): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('guru') || $materi->guru_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki izin.'], 403);
        }

        $validated = $request->validate([
            'judul'        => ['string', 'max:255'],
            'mapel_id'     => ['exists:mapels,id'],
            'konten'       => ['string'],
            'video_url'    => ['nullable', 'string', 'url', 'max:500'],
            'file'         => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar'],
            'is_published' => ['boolean'],
        ]);

        if ($request->hasFile('file')) {
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('materi/' . $materi->class_id, $filename, 'public');
            $validated['file_path'] = $path;
        }

        $materi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diperbarui.',
            'data'    => $materi->fresh(),
        ]);
    }

    /**
     * Delete materi (guru only).
     */
    public function destroy(Request $request, Materi $materi): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('guru') || $materi->guru_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki izin.'], 403);
        }

        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus.',
        ]);
    }
}
