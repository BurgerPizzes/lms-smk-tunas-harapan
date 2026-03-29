<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            ->withCount('siswa')
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.kelas.index', compact('kelasList'));
    }

    /**
     * Show the form for creating a new kelas.
     */
    public function create(): \Illuminate\View\View
    {
        $gurus = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();
        $jurusans = \App\Models\Jurusan::where('is_active', true)->orderBy('nama')->get();

        return view('admin.kelas.create', compact('gurus', 'jurusans'));
    }

    /**
     * Store a newly created kelas in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'jurusan_id'  => ['nullable', 'exists:jurusans,id'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
            'tingkat'     => ['required', 'string', 'max:10'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
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
        $kelas->load(['waliKelas', 'jurusan', 'siswa', 'guruMapel.mapel', 'guruMapel.guru']);

        return view('admin.kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing the specified kelas.
     */
    public function edit(Kelas $kelas): \Illuminate\View\View
    {
        $gurus = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();
        $jurusans = \App\Models\Jurusan::where('is_active', true)->orderBy('nama')->get();

        return view('admin.kelas.edit', compact('kelas', 'gurus', 'jurusans'));
    }

    /**
     * Update the specified kelas in storage.
     */
    public function update(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'jurusan_id'  => ['nullable', 'exists:jurusans,id'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
            'tingkat'     => ['required', 'string', 'max:10'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
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
        if ($kelas->siswa()->exists()) {
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
            'wali_kelas_id' => ['required', 'exists:users,id'],
        ]);

        $kelas->update([
            'wali_kelas_id' => $validated['wali_kelas_id'],
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
        $enrolledSiswa = $kelas->siswa()->orderBy('name')->get();
        $availableSiswa = User::where('role', 'siswa')
            ->where('is_active', true)
            ->whereDoesntHave('kelas', function ($query) use ($kelas) {
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

        $kelas->siswa()->syncWithoutDetaching($validated['user_ids']);

        return redirect()
            ->route('admin.kelas.manage-enrollment', $kelas)
            ->with('success', count($validated['user_ids']) . ' siswa berhasil didaftarkan.');
    }

    /**
     * Remove siswa from kelas.
     */
    public function removeSiswa(Kelas $kelas, User $user): \Illuminate\Http\RedirectResponse
    {
        $kelas->siswa()->detach($user->id);

        return redirect()
            ->route('admin.kelas.manage-enrollment', $kelas)
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
