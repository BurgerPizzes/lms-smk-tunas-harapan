<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class AdminMapelController extends Controller
{
    /**
     * Display a listing of mata pelajaran with optional category filter.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Mapel::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        $mapels = $query->withCount(['guruMapel'])
            ->orderBy('kode')
            ->paginate(15)
            ->withQueryString();

        $kategoris = ['normatif', 'adaptif', 'produktif'];

        return view('admin.mapel.index', compact('mapels', 'kategoris'));
    }

    /**
     * Show the form for creating a new mata pelajaran.
     */
    public function create(): \Illuminate\View\View
    {
        $kategoris = ['normatif', 'adaptif', 'produktif'];
        $jurusans = \App\Models\Jurusan::where('aktif', true)->orderBy('nama')->get();

        return view('admin.mapel.create', compact('kategoris', 'jurusans'));
    }

    /**
     * Store a newly created mata pelajaran in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => ['required', 'string', 'max:20', 'unique:mapels,kode'],
            'nama'      => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string', 'in:normatif,adaptif,produktif'],
            'kkm'       => ['required', 'integer', 'min:0', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Mapel::create($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified mata pelajaran.
     */
    public function show(Mapel $mapel): \Illuminate\View\View
    {
        $mapel->load(['guruMapel.guru', 'guruMapel.kelas']);

        return view('admin.mapel.show', compact('mapel'));
    }

    /**
     * Show the form for editing the specified mata pelajaran.
     */
    public function edit(Mapel $mapel): \Illuminate\View\View
    {
        $kategoris = ['normatif', 'adaptif', 'produktif'];
        $jurusans = \App\Models\Jurusan::where('aktif', true)->orderBy('nama')->get();

        return view('admin.mapel.edit', compact('mapel', 'kategoris', 'jurusans'));
    }

    /**
     * Update the specified mata pelajaran in storage.
     */
    public function update(Request $request, Mapel $mapel): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'kode'      => ['required', 'string', 'max:20', 'unique:mapels,kode,' . $mapel->id],
            'nama'      => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string', 'in:normatif,adaptif,produktif'],
            'kkm'       => ['required', 'integer', 'min:0', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', $mapel->is_active);

        $mapel->update($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified mata pelajaran from storage.
     */
    public function destroy(Mapel $mapel): \Illuminate\Http\RedirectResponse
    {
        if ($mapel->guruMapel()->exists()) {
            return back()->withErrors('Mata Pelajaran tidak dapat dihapus karena masih memiliki pengampu terkait.');
        }

        $mapel->delete();

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
