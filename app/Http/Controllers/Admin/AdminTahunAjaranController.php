<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AdminTahunAjaranController extends Controller
{
    /**
     * Display a listing of tahun ajaran.
     */
    public function index(): \Illuminate\View\View
    {
        $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    /**
     * Show the form for creating a new tahun ajaran.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.tahun-ajaran.create');
    }

    /**
     * Store a newly created tahun ajaran in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'tahun_mulai' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tahun_selesai' => ['required', 'integer', 'min:2000', 'max:2100', 'gt:tahun_mulai'],
            'semester'   => ['required', 'string', 'in:ganjil,genap'],
            'aktif'      => ['sometimes', 'boolean'],
        ]);

        // Ensure only one active tahun ajaran
        if ($request->boolean('aktif', false)) {
            TahunAjaran::where('aktif', true)->update(['aktif' => false]);
            $validated['aktif'] = true;
        } else {
            $validated['aktif'] = false;
        }

        TahunAjaran::create($validated);

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified tahun ajaran.
     */
    public function edit(TahunAjaran $tahunAjaran): \Illuminate\View\View
    {
        return view('admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Update the specified tahun ajaran in storage.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'tahun_mulai' => ['required', 'integer', 'min:2000', 'max:2100'],
            'tahun_selesai' => ['required', 'integer', 'min:2000', 'max:2100', 'gt:tahun_mulai'],
            'semester'   => ['required', 'string', 'in:ganjil,genap'],
            'aktif'      => ['sometimes', 'boolean'],
        ]);

        // Ensure only one active tahun ajaran
        if ($request->boolean('aktif', false)) {
            TahunAjaran::where('aktif', true)
                ->where('id', '!=', $tahunAjaran->id)
                ->update(['aktif' => false]);
            $validated['aktif'] = true;
        } else {
            // Prevent deactivating if this is the only active one
            $validated['aktif'] = $tahunAjaran->aktif;
        }

        $tahunAjaran->update($validated);

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified tahun ajaran from storage.
     */
    public function destroy(TahunAjaran $tahunAjaran): \Illuminate\Http\RedirectResponse
    {
        if ($tahunAjaran->aktif) {
            return back()->withErrors('Tahun Ajaran aktif tidak dapat dihapus. Nonaktifkan terlebih dahulu.');
        }

        if ($tahunAjaran->guruMapel()->exists()) {
            return back()->withErrors('Tahun Ajaran tidak dapat dihapus karena masih memiliki data pengampu terkait.');
        }

        $tahunAjaran->delete();

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }

    /**
     * Set a tahun ajaran as the active one.
     */
    public function setActive(TahunAjaran $tahunAjaran): \Illuminate\Http\RedirectResponse
    {
        TahunAjaran::where('aktif', true)->update(['aktif' => false]);

        $tahunAjaran->update(['aktif' => true]);

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran ' . $tahunAjaran->nama . ' berhasil diaktifkan.');
    }
}
