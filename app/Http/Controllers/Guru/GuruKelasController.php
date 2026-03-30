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
        $jurusans = \App\Models\Jurusan::where('is_active', true)->orderBy('nama')->get();
        $tahunAjaran = \App\Models\TahunAjaran::where('is_active', true)->first();

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
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'deskripsi'   => ['nullable', 'string'],
        ]);

        $guru = auth()->user();

        $kelas = Kelas::create([
            'nama'         => $validated['nama'],
            'jurusan_id'   => $validated['jurusan_id'],
            'wali_kelas_id' => $guru->id,
            'tingkat'      => $validated['tingkat'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
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

        $kelas->load(['jurusan', 'waliKelas', 'siswa']);

        // Feed: Materi and Tugas combined as a timeline
        $materiFeed = Materi::where('kelas_id', $kelas->id)
            ->with('mapel', 'user')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($item) => [
                'type'      => 'materi',
                'id'        => $item->id,
                'title'     => $item->judul,
                'mapel'     => $item->mapel?->nama,
                'user_name' => $item->user?->name,
                'created_at' => $item->created_at,
            ]);

        $tugasFeed = Tugas::where('kelas_id', $kelas->id)
            ->with('mapel', 'user')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($item) => [
                'type'      => 'tugas',
                'id'        => $item->id,
                'title'     => $item->judul,
                'mapel'     => $item->mapel?->nama,
                'user_name' => $item->user?->name,
                'created_at' => $item->created_at,
                'deadline'  => $item->deadline,
            ]);

        // Merge and sort by created_at
        $feed = $materiFeed->merge($tugasFeed)->sortByDesc('created_at')->values();

        return view('guru.kelas.show', compact('kelas', 'feed'));
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
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'deskripsi'   => ['nullable', 'string'],
        ]);

        $kelas->update($validated);

        return redirect()
            ->route('guru.kelas.show', $kelas)
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * View class members (siswa list).
     */
    public function members(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $siswa = $kelas->siswa()->orderBy('name')->paginate(20);

        return view('guru.kelas.members', compact('kelas', 'siswa'));
    }

    /**
     * Remove a siswa from class.
     */
    public function removeMember(Kelas $kelas, User $user): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        if (! $kelas->siswa()->where('users.id', $user->id)->exists()) {
            return back()->withErrors('Siswa tidak ditemukan di kelas ini.');
        }

        $kelas->siswa()->detach($user->id);

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

        if (! $hasAccess && $kelas->wali_kelas_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }
}
