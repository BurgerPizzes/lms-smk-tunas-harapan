<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class AdminJurusanController extends Controller
{
    /**
     * Display a listing of jurusan.
     */
    public function index(): \Illuminate\View\View
    {
        $jurusans = Jurusan::withCount(['kelas', 'users'])
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.jurusans.index', compact('jurusans'));
    }

    /**
     * Show the form for creating a new jurusan.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.jurusans.create');
    }

    /**
     * Store a newly created jurusan in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => ['required', 'string', 'max:20', 'unique:jurusans,kode'],
            'nama'      => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Jurusan::create($validated);

        return redirect()
            ->route('admin.jurusans.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Display the specified jurusan.
     */
    public function show(Jurusan $jurusan): \Illuminate\View\View
    {
        $jurusan->load(['kelas.siswa', 'kelas.waliKelas', 'guru']);

        return view('admin.jurusans.show', compact('jurusan'));
    }

    /**
     * Show the form for editing the specified jurusan.
     */
    public function edit(Jurusan $jurusan): \Illuminate\View\View
    {
        return view('admin.jurusans.edit', compact('jurusan'));
    }

    /**
     * Update the specified jurusan in storage.
     */
    public function update(Request $request, Jurusan $jurusan): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => ['required', 'string', 'max:20', 'unique:jurusans,kode,' . $jurusan->id],
            'nama'      => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', $jurusan->is_active);

        $jurusan->update($validated);

        return redirect()
            ->route('admin.jurusans.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    /**
     * Remove the specified jurusan from storage.
     */
    public function destroy(Jurusan $jurusan): \Illuminate\Http\RedirectResponse
    {
        if ($jurusan->kelas()->exists()) {
            return back()->withErrors('Jurusan tidak dapat dihapus karena masih memiliki kelas terkait.');
        }

        $jurusan->delete();

        return redirect()
            ->route('admin.jurusans.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }

    /**
     * Toggle jurusan active status.
     */
    public function toggleStatus(Jurusan $jurusan): \Illuminate\Http\RedirectResponse
    {
        $jurusan->update([
            'is_active' => ! $jurusan->is_active,
        ]);

        $status = $jurusan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.jurusans.index')
            ->with('success', "Jurusan berhasil {$status}.");
    }
}
