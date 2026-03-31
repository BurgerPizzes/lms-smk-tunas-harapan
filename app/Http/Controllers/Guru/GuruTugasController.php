<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GuruTugasController extends Controller
{
    /**
     * Display a listing of tugas in a class.
     */
    public function index(Request $request, Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $query = Tugas::where('class_id', $kelas->id)->with('mapel', 'guru');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('deadline', '>=', now());
            } elseif ($status === 'expired') {
                $query->where('deadline', '<', now());
            }
        }

        $tugasList = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.tugas.index', compact('kelas', 'tugasList', 'mapels'));
    }

    /**
     * Show the form for creating a new tugas.
     */
    public function create(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.tugas.create', compact('kelas', 'mapels'));
    }

    /**
     * Store a newly created tugas with file attachment.
     */
    public function store(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        $validated = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'mapel_id'   => ['required', 'exists:mapels,id'],
            'deskripsi'  => ['required', 'string'],
            'deadline'   => ['required', 'date', 'after:now'],
            'file'       => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['class_id'] = $kelas->id;
        $validated['guru_id']  = Auth::id();
        $validated['is_published'] = $request->boolean('is_published', true);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('tugas/' . $kelas->id, $filename, 'public');
            $validated['file_attachment'] = $path;
        }

        Tugas::create($validated);

        return redirect()
            ->route('guru.kelas.tugas.index', $kelas)
            ->with('success', 'Tugas berhasil dibuat.');
    }

    /**
     * Display the specified tugas with all submissions.
     */
    public function show(Tugas $tugas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($tugas->kelas);

        $tugas->load([
            'kelas',
            'mapel',
            'guru',
            'submissions' => function ($query) {
                $query->with('siswa')->latest();
            },
        ]);

        $totalSiswa = $tugas->kelas ? $tugas->kelas->siswas()->count() : 0;
        $submittedCount = $tugas->submissions->count();
        $gradedCount = $tugas->submissions->whereNotNull('nilai')->count();
        $ungradedCount = $submittedCount - $gradedCount;
        $lateCount = $tugas->submissions->filter(fn ($s) => $s->isLate())->count();
        $onTimeCount = $submittedCount - $lateCount;
        $averageScore = $tugas->submissions->whereNotNull('nilai')->avg('nilai');

        $stats = [
            'submitted' => $onTimeCount,
            'late' => $lateCount,
            'not_submitted' => $totalSiswa - $submittedCount,
            'total' => $totalSiswa,
        ];

        return view('guru.tugas.show', compact(
            'tugas', 'submittedCount', 'gradedCount', 'ungradedCount', 'averageScore', 'stats'
        ));
    }

    /**
     * Show the form for editing the specified tugas.
     */
    public function edit(Tugas $tugas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($tugas->kelas);

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($tugas) {
            $query->where('class_id', $tugas->class_id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        // Provide kelasList for view dropdown
        $kelasList = Kelas::whereHas('guruMapel', function ($query) {
            $query->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.tugas.edit', compact('tugas', 'mapels', 'kelasList'));
    }

    /**
     * Update the specified tugas.
     */
    public function update(Request $request, Tugas $tugas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($tugas->kelas);

        $validated = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'mapel_id'   => ['required', 'exists:mapels,id'],
            'deskripsi'  => ['required', 'string'],
            'deadline'   => ['required', 'date'],
            'file'       => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published', $tugas->is_published);

        // Handle new file upload
        if ($request->hasFile('file')) {
            if ($tugas->file_attachment) {
                Storage::disk('public')->delete($tugas->file_attachment);
            }

            $file = $request->file('file');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('tugas/' . $tugas->class_id, $filename, 'public');
            $validated['file_attachment'] = $path;
        }

        $tugas->update($validated);

        return redirect()
            ->route('guru.tugas.show', $tugas)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Remove the specified tugas.
     */
    public function destroy(Tugas $tugas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($tugas->kelas);

        // Delete file
        if ($tugas->file_attachment) {
            Storage::disk('public')->delete($tugas->file_attachment);
        }

        // Delete all submission files
        foreach ($tugas->submissions as $submission) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
        }

        $tugas->delete();

        return redirect()
            ->route('guru.kelas.tugas.index', $tugas->kelas)
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Authorize that the authenticated guru has access to the given class.
     */
    private function authorizeGuruAccess(Kelas $kelas): void
    {
        $guru = auth()->user();
        $hasAccess = GuruMapel::where('guru_id', $guru->id)
            ->where('class_id', $kelas->id)
            ->exists();

        if (! $hasAccess && $kelas->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }
}
