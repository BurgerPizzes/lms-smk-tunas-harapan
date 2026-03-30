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

        return view('admin.jurusan.index', compact('jurusans'));
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

        $validated['aktif'] = $request->boolean('is_active', true);

        Jurusan::create($validated);

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Display the specified jurusan.
     */
    public function show(Jurusan $jurusan): \Illuminate\View\View
    {
        $jurusan->load(['kelas.siswas', 'kelas.waliKelas', 'users']);

        return view('admin.jurusan.show', compact('jurusan'));
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

        $validated['aktif'] = $request->boolean('is_active', $jurusan->aktif);

        $jurusan->update($validated);

        return redirect()
            ->route('admin.jurusan.index')
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
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }

    /**
     * Toggle jurusan active status.
     */
    public function toggleStatus(Jurusan $jurusan): \Illuminate\Http\RedirectResponse
    {
        $jurusan->update([
            'aktif' => ! $jurusan->aktif,
        ]);

        $status = $jurusan->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', "Jurusan berhasil {$status}.");
    }
}
