<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Tugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiTugasController extends Controller
{
    /**
     * List tugas in a class.
     */
    public function index(Request $request, Kelas $kelas): JsonResponse
    {
        $query = Tugas::where('kelas_id', $kelas->id)->with('mapel', 'user');

        if ($request->user()->role === 'siswa') {
            $query->where('is_published', true);
        }

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('deadline', '>=', now());
            } elseif ($status === 'expired') {
                $query->where('deadline', '<', now());
            }
        }

        $tugasList = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $tugasList,
        ]);
    }

    /**
     * Get tugas detail.
     */
    public function show(Request $request, Tugas $tugas): JsonResponse
    {
        if ($request->user()->role === 'siswa' && ! $tugas->is_published) {
            return response()->json(['message' => 'Tugas tidak ditemukan.'], 404);
        }

        $tugas->load(['kelas', 'mapel', 'user']);

        // Include user's submission if siswa
        if ($request->user()->role === 'siswa') {
            $tugas->my_submission = \App\Models\Submission::where('tugas_id', $tugas->id)
                ->where('user_id', $request->user()->id)
                ->first();
        }

        return response()->json([
            'success' => true,
            'data'    => $tugas,
        ]);
    }

    /**
     * Create a new tugas (guru only).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole('guru')) {
            return response()->json(['message' => 'Hanya guru yang dapat membuat tugas.'], 403);
        }

        $validated = $request->validate([
            'kelas_id'     => ['required', 'exists:kelas,id'],
            'judul'        => ['required', 'string', 'max:255'],
            'mapel_id'     => ['required', 'exists:mapels,id'],
            'deskripsi'    => ['required', 'string'],
            'deadline'     => ['required', 'date', 'after:now'],
            'file'         => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar'],
            'is_published' => ['boolean'],
        ]);

        $validated['user_id'] = $user->id;
        $validated['is_published'] = $validated['is_published'] ?? true;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('tugas/' . $validated['kelas_id'], $filename, 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        $tugas = Tugas::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat.',
            'data'    => $tugas,
        ], 201);
    }
}
