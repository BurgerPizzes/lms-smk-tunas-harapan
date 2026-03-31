<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGuruMapel;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminKelasController extends Controller
{
    /**
     * Display a listing of kelas.
     */
    public function index(): \Illuminate\View\View
    {
        $kelasList = Kelas::with(['waliKelas', 'jurusan'])
            ->withCount('siswas')
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.kelas.index', compact('kelasList'));
    }

    /**
     * Show the form for creating a new kelas.
     */
    public function create(): \Illuminate\View\View
    {
        $guruList = User::role('guru')->where('is_active', true)->orderBy('name')->get();
        $jurusans = \App\Models\Jurusan::where('aktif', true)->orderBy('nama')->get();
        $tahunAjaran = \App\Models\TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('admin.kelas.create', compact('guruList', 'jurusans', 'tahunAjaran'));
    }

    /**
     * Store a newly created kelas in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'jurusan_id'  => ['nullable', 'exists:jurusans,id'],
            'guru_id'     => ['nullable', 'exists:users,id'],
            'tingkat'     => ['required', 'string', 'max:10'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
            'deskripsi'   => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['kode_unik'] = strtoupper(Str::random(8));

        Kelas::create($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan dengan kode: ' . $validated['kode_unik']);
    }

    /**
     * Display the specified kelas with detail.
     */
    public function show(Kelas $kelas): \Illuminate\View\View
    {
        $kelas->load(['waliKelas', 'jurusan', 'siswas', 'gurus']);

        // Guru pengampu for this kelas
        $guruPengampu = ClassGuruMapel::where('class_id', $kelas->id)
            ->with(['guru', 'mapel', 'tahunAjaran'])
            ->get();

        // Stats for the kelas
        $stats = [
            'totalMateri'   => $kelas->materis()->count(),
            'totalTugas'    => $kelas->tugases()->count(),
            'averageGrade'  => $kelas->submissions()->whereNotNull('nilai')->avg('nilai')
                ? round($kelas->submissions()->whereNotNull('nilai')->avg('nilai'), 1)
                : '-',
        ];

        return view('admin.kelas.show', compact('kelas', 'guruPengampu', 'stats'));
    }

    /**
     * Show the form for editing the specified kelas.
     */
    public function edit(Kelas $kelas): \Illuminate\View\View
    {
        $guruList = User::role('guru')->where('is_active', true)->orderBy('name')->get();
        $jurusans = \App\Models\Jurusan::where('aktif', true)->orderBy('nama')->get();
        $tahunAjaran = \App\Models\TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('admin.kelas.edit', compact('kelas', 'guruList', 'jurusans', 'tahunAjaran'));
    }

    /**
     * Update the specified kelas in storage.
     */
    public function update(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'jurusan_id'  => ['nullable', 'exists:jurusans,id'],
            'guru_id'     => ['nullable', 'exists:users,id'],
            'tingkat'     => ['required', 'string', 'max:10'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
            'deskripsi'   => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', $kelas->is_active);

        $kelas->update($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified kelas from storage.
     */
    public function destroy(Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        if ($kelas->siswas()->exists()) {
            return back()->withErrors('Kelas tidak dapat dihapus karena masih memiliki siswa.');
        }

        $kelas->delete();

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * Assign wali kelas.
     */
    public function assignWaliKelas(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'guru_id' => ['required', 'exists:users,id'],
        ]);

        $kelas->update([
            'guru_id' => $validated['guru_id'],
        ]);

        return redirect()
            ->route('admin.kelas.show', $kelas)
            ->with('success', 'Wali kelas berhasil ditetapkan.');
    }

    /**
     * Manage siswa enrollment for a kelas.
     */
    public function manageEnrollment(Kelas $kelas): \Illuminate\View\View
    {
        $enrolledSiswa = $kelas->siswas()->orderBy('name')->get();
        $availableSiswa = User::role('siswa')
            ->where('is_active', true)
            ->whereDoesntHave('enrolledClasses', function ($query) use ($kelas) {
                $query->where('kelas.id', $kelas->id);
            })
            ->when($kelas->jurusan_id, function ($query) use ($kelas) {
                $query->where('jurusan_id', $kelas->jurusan_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.kelas.enrollment', compact('kelas', 'enrolledSiswa', 'availableSiswa'));
    }

    /**
     * Enroll siswa to kelas.
     */
    public function enrollSiswa(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'user_ids'   => ['required', 'array'],
            'user_ids.*' => ['required', 'exists:users,id'],
        ]);

        $kelas->siswas()->syncWithoutDetaching($validated['user_ids']);

        return redirect()
            ->route('admin.kelas.members', $kelas)
            ->with('success', count($validated['user_ids']) . ' siswa berhasil didaftarkan.');
    }

    /**
     * Remove siswa from kelas.
     */
    public function removeMember(Kelas $kelas, User $user): \Illuminate\Http\RedirectResponse
    {
        $kelas->siswas()->detach($user->id);

        return redirect()
            ->route('admin.kelas.members', $kelas)
            ->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }

    /**
     * Generate a new unique class code.
     */
    public function generateCode(Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $newCode = strtoupper(Str::random(8));

        $kelas->update([
            'kode_unik' => $newCode,
        ]);

        return redirect()
            ->route('admin.kelas.show', $kelas)
            ->with('success', 'Kode kelas baru: ' . $newCode);
    }
}
