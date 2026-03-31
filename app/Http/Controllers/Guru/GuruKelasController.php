<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuruKelasController extends Controller
{
    /**
     * Display a listing of the guru's classes.
     */
    public function index(): \Illuminate\View\View
    {
        $guru = auth()->user();

        $guruMapels = GuruMapel::with(['kelas.jurusan', 'mapel', 'tahunAjaran'])
            ->where('guru_id', $guru->id)
            ->get()
            ->groupBy('kelas_id');

        return view('guru.kelas.index', compact('guruMapels'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create(): \Illuminate\View\View
    {
        $jurusans = \App\Models\Jurusan::where('aktif', true)->orderBy('nama')->get();
        $tahunAjaran = \App\Models\TahunAjaran::where('aktif', true)->first();

        return view('guru.kelas.create', compact('jurusans', 'tahunAjaran'));
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'jurusan_id'  => ['nullable', 'exists:jurusans,id'],
            'tingkat'     => ['required', 'string', 'max:10'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
            'deskripsi'   => ['nullable', 'string'],
        ]);

        $guru = auth()->user();

        $kelas = Kelas::create([
            'nama'         => $validated['nama'],
            'jurusan_id'   => $validated['jurusan_id'],
            'guru_id'      => $guru->id,
            'tingkat'      => $validated['tingkat'],
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'] ?? null,
            'deskripsi'    => $validated['deskripsi'],
            'kode_unik'    => strtoupper(Str::random(8)),
            'is_active'    => true,
        ]);

        return redirect()
            ->route('guru.kelas.show', $kelas)
            ->with('success', 'Kelas berhasil dibuat. Kode kelas: ' . $kelas->kode_unik);
    }

    /**
     * Display the specified class with feed (announcements, materi, tugas timeline).
     */
    public function show(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $kelas->load(['jurusan', 'waliKelas', 'siswas']);

        // Feed: Materi and Tugas combined as a timeline
        $materiFeed = Materi::where('kelas_id', $kelas->id)
            ->with('mapel', 'guru')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($item) => [
                'type'      => 'materi',
                'id'        => $item->id,
                'title'     => $item->judul,
                'mapel'     => $item->mapel?->nama,
                'user_name' => $item->guru?->name,
                'created_at' => $item->created_at,
                'deskripsi' => $item->deskripsi,
            ]);

        $tugasFeed = Tugas::where('kelas_id', $kelas->id)
            ->with('mapel', 'guru')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($item) => [
                'type'      => 'tugas',
                'id'        => $item->id,
                'title'     => $item->judul,
                'mapel'     => $item->mapel?->nama,
                'user_name' => $item->guru?->name,
                'created_at' => $item->created_at,
                'deadline'  => $item->deadline,
                'deskripsi' => $item->deskripsi,
            ]);

        // Merge and sort by created_at
        $feedItems = $materiFeed->merge($tugasFeed)->sortByDesc('created_at')->values();

        // Materi list
        $materiList = Materi::where('kelas_id', $kelas->id)
            ->with('mapel')
            ->latest()
            ->take(10)
            ->get();

        // Tugas list
        $tugasList = Tugas::where('kelas_id', $kelas->id)
            ->with('mapel')
            ->latest()
            ->take(10)
            ->get();

        // Siswa list
        $siswaList = $kelas->siswas()->orderBy('name')->get();

        // Absensi list
        $absensiList = \App\Models\Attendance::where('kelas_id', $kelas->id)
            ->with('mapel')
            ->latest()
            ->take(10)
            ->get();

        // Quiz list
        $quizList = Quiz::where('kelas_id', $kelas->id)
            ->with('mapel')
            ->withCount('questions')
            ->latest()
            ->take(10)
            ->get();

        // Mapel filter options
        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })->orderBy('nama')->get();

        // Guru list (teachers assigned to this class)
        $guruList = \App\Models\User::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })->get();

        return view('guru.kelas.show', compact('kelas', 'feedItems', 'materiList', 'tugasList', 'siswaList', 'absensiList', 'quizList', 'mapels', 'guruList'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $jurusans = \App\Models\Jurusan::where('aktif', true)->orderBy('nama')->get();
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('guru.kelas.edit', compact('kelas', 'jurusans', 'tahunAjarans'));
    }

    /**
     * Update the specified class.
     */
    public function update(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'jurusan_id'  => ['nullable', 'exists:jurusans,id'],
            'tingkat'     => ['required', 'string', 'max:10'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
            'deskripsi'   => ['nullable', 'string'],
        ]);

        $kelas->update($validated);

        return redirect()
            ->route('guru.kelas.show', $kelas)
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        $kelas->delete();

        return redirect()
            ->route('guru.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * View class members (siswa list).
     */
    public function members(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $siswaList = $kelas->siswas()->orderBy('name')->paginate(20);

        // Guru list (teachers assigned to this class)
        $guruList = \App\Models\User::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })->get();

        return view('guru.kelas.members', compact('kelas', 'siswaList', 'guruList'));
    }

    /**
     * Remove a siswa from class.
     */
    public function removeMember(Kelas $kelas, User $user): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        if (! $kelas->siswas()->where('users.id', $user->id)->exists()) {
            return back()->withErrors('Siswa tidak ditemukan di kelas ini.');
        }

        $kelas->siswas()->detach($user->id);

        return redirect()
            ->route('guru.kelas.members', $kelas)
            ->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }

    /**
     * Authorize that the authenticated guru has access to the given class.
     */
    private function authorizeGuruAccess(Kelas $kelas): void
    {
        $guru = auth()->user();
        $hasAccess = GuruMapel::where('guru_id', $guru->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        if (! $hasAccess && $kelas->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }
}
