<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Tugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiSubmissionController extends Controller
{
    /**
     * List submissions for a tugas (guru view).
     */
    public function index(Request $request, Tugas $tugas): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('guru')) {
            return response()->json(['message' => 'Hanya guru yang dapat melihat semua submission.'], 403);
        }

        $submissions = Submission::where('tugas_id', $tugas->id)
            ->with('siswa')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $submissions,
        ]);
    }

    /**
     * Submit tugas (siswa).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('siswa')) {
            return response()->json(['message' => 'Hanya siswa yang dapat mengumpulkan tugas.'], 403);
        }

        $validated = $request->validate([
            'tugas_id' => ['required', 'exists:tugas,id'],
            'jawaban'  => ['nullable', 'string', 'max:10000'],
            'file'     => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png,gif'],
        ]);

        if (empty($validated['jawaban']) && ! $request->hasFile('file')) {
            return response()->json([
                'message' => 'Anda harus mengisi jawaban atau mengunggah file.',
            ], 422);
        }

        $tugas = Tugas::findOrFail($validated['tugas_id']);

        if ($tugas->deadline && $tugas->deadline < now()) {
            return response()->json([
                'message' => 'Batas waktu pengumpulan telah berakhir.',
            ], 422);
        }

        $submissionData = [
            'tugas_id'     => $tugas->id,
            'siswa_id'     => $user->id,
            'konten'       => $validated['jawaban'] ?? null,
            'submitted_at' => now(),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $user->id . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('submissions/' . $tugas->id, $filename, 'public');
            $submissionData['file_path'] = $path;
        }

        // Update existing or create
        $existing = Submission::where('tugas_id', $tugas->id)
            ->where('siswa_id', $user->id)
            ->first();

        if ($existing) {
            if ($request->hasFile('file') && $existing->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->update($submissionData);
            $submission = $existing;
        } else {
            $submission = Submission::create($submissionData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dikumpulkan.',
            'data'    => $submission,
        ], 201);
    }

    /**
     * Get submission detail.
     */
    public function show(Request $request, Submission $submission): JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole('siswa') && $submission->siswa_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses.'], 403);
        }

        $submission->load(['tugas.kelas', 'tugas.mapel', 'siswa']);

        return response()->json([
            'success' => true,
            'data'    => $submission,
        ]);
    }

    /**
     * Grade a submission (guru).
     */
    public function grade(Request $request, Submission $submission): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('guru')) {
            return response()->json(['message' => 'Hanya guru yang dapat memberi nilai.'], 403);
        }

        $validated = $request->validate([
            'nilai'    => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ]);

        $submission->update([
            'nilai'    => $validated['nilai'],
            'feedback' => $validated['feedback'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil diberikan.',
            'data'    => $submission->fresh(),
        ]);
    }
}
