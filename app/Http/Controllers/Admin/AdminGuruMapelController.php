<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;

class AdminGuruMapelController extends Controller
{
    /**
     * Display a listing of guru-mapel assignments.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = ClassGuruMapel::with(['guru', 'mapel', 'kelas', 'tahunAjaran']);

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->input('guru_id'));
        }

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->input('kelas_id'));
        }

        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->input('tahun_ajaran_id'));
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Data for filters (variable names match index view expectations)
        $guruList = User::role('guru')->where('is_active', true)->orderBy('name')->get();
        $mapelList = Mapel::where('is_active', true)->orderBy('nama')->get();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('admin.guru-mapel.index', compact(
            'assignments', 'guruList', 'mapelList', 'kelasList', 'tahunAjaranList'
        ));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(): \Illuminate\View\View
    {
        $gurus = User::role('guru')->where('is_active', true)->orderBy('name')->get();
        $mapels = Mapel::where('is_active', true)->orderBy('nama')->get();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        // Get active tahun ajaran
        $activeTahunAjaran = TahunAjaran::where('aktif', true)->first();

        return view('admin.guru-mapel.create', compact(
            'gurus', 'mapels', 'kelasList', 'tahunAjarans', 'activeTahunAjaran'
        ));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'guru_id'        => ['required', 'exists:users,id'],
            'mapel_id'       => ['required', 'exists:mapels,id'],
            'kelas_id'       => ['required', 'exists:kelas,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
        ]);

        // Check for duplicate assignment
        $exists = ClassGuruMapel::where($validated)->exists();

        if ($exists) {
            return back()
                ->withErrors('Pengampu ini sudah ditugaskan untuk mata pelajaran, kelas, dan tahun ajaran tersebut.')
                ->withInput();
        }

        ClassGuruMapel::create($validated);

        return redirect()
            ->route('admin.guru-mapel.index')
            ->with('success', 'Pengampu mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified assignment.
     */
    public function show(ClassGuruMapel $classGuruMapel): \Illuminate\View\View
    {
        $classGuruMapel->load(['guru', 'mapel', 'kelas', 'tahunAjaran']);

        return view('admin.guru-mapel.show', compact('classGuruMapel'));
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(ClassGuruMapel $classGuruMapel): \Illuminate\View\View
    {
        $gurus = User::role('guru')->where('is_active', true)->orderBy('name')->get();
        $mapels = Mapel::where('is_active', true)->orderBy('nama')->get();
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('admin.guru-mapel.edit', compact(
            'classGuruMapel', 'gurus', 'mapels', 'kelasList', 'tahunAjarans'
        ));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, ClassGuruMapel $classGuruMapel): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'guru_id'        => ['required', 'exists:users,id'],
            'mapel_id'       => ['required', 'exists:mapels,id'],
            'kelas_id'       => ['required', 'exists:kelas,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
        ]);

        // Check for duplicate assignment (excluding current)
        $exists = ClassGuruMapel::where($validated)
            ->where('id', '!=', $classGuruMapel->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors('Pengampu ini sudah ditugaskan untuk mata pelajaran, kelas, dan tahun ajaran tersebut.')
                ->withInput();
        }

        $classGuruMapel->update($validated);

        return redirect()
            ->route('admin.guru-mapel.index')
            ->with('success', 'Pengampu mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(ClassGuruMapel $classGuruMapel): \Illuminate\Http\RedirectResponse
    {
        $classGuruMapel->delete();

        return redirect()
            ->route('admin.guru-mapel.index')
            ->with('success', 'Pengampu mata pelajaran berhasil dihapus.');
    }
}
