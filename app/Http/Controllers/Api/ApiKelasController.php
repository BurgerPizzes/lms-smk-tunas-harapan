<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiKelasController extends Controller
{
    /**
     * List classes (filtered by enrolled for siswa, assigned for guru).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Kelas::query()->with(['jurusan', 'waliKelas']);

        if ($user->hasRole('siswa')) {
            $query->whereHas('siswa', fn ($q) => $q->where('users.id', $user->id));
        } elseif ($user->hasRole('guru')) {
            $query->whereHas('guruMapel', fn ($q) => $q->where('guru_id', $user->id))
                  ->orWhere('wali_kelas_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', "%{$search}%");
        }

        $kelasList = $query->orderBy('nama')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $kelasList,
        ]);
    }

    /**
     * Get class detail.
     */
    public function show(Request $request, Kelas $kelas): JsonResponse
    {
        $user = $request->user();

        // Verify access
        if ($user->hasRole('siswa')) {
            if (! $kelas->siswa()->where('users.id', $user->id)->exists()) {
                return response()->json(['message' => 'Anda tidak terdaftar di kelas ini.'], 403);
            }
        }

        $kelas->load(['jurusan', 'waliKelas', 'guruMapel.mapel', 'guruMapel.guru']);

        $memberCount = $kelas->siswa()->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'kelas'        => $kelas,
                'member_count' => $memberCount,
            ],
        ]);
    }

    /**
     * Create a new class (guru only).
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole('guru')) {
            return response()->json(['message' => 'Hanya guru yang dapat membuat kelas.'], 403);
        }

        $validated = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'jurusan_id'   => ['nullable', 'exists:jurusans,id'],
            'tingkat'      => ['required', 'string', 'max:10'],
            'tahun_ajaran'  => ['required', 'string', 'max:20'],
            'deskripsi'    => ['nullable', 'string'],
        ]);

        $validated['wali_kelas_id'] = $user->id;
        $validated['kode_unik'] = strtoupper(\Illuminate\Support\Str::random(8));
        $validated['is_active'] = true;

        $kelas = Kelas::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dibuat.',
            'data'    => $kelas,
        ], 201);
    }

    /**
     * Join a class via kode_unik.
     */
    public function join(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole('siswa')) {
            return response()->json(['message' => 'Hanya siswa yang dapat bergabung ke kelas.'], 403);
        }

        $validated = $request->validate([
            'kode_unik' => ['required', 'string', 'size:8'],
        ]);

        $kelas = Kelas::where('kode_unik', strtoupper($validated['kode_unik']))
            ->where('is_active', true)
            ->first();

        if (! $kelas) {
            return response()->json([
                'message' => 'Kode kelas tidak valid.',
            ], 404);
        }

        if ($user->kelas()->where('kelas.id', $kelas->id)->exists()) {
            return response()->json([
                'message' => 'Anda sudah terdaftar di kelas ini.',
            ], 422);
        }

        $user->kelas()->attach($kelas->id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung ke kelas.',
            'data'    => $kelas,
        ]);
    }
}
