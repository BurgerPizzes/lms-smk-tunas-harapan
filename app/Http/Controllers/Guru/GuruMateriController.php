<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GuruMateriController extends Controller
{
    /**
     * Display a listing of materi in a class with optional mapel filter.
     */
    public function index(Request $request, Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $query = Materi::where('kelas_id', $kelas->id)->with('mapel', 'user');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('judul', 'like', "%{$search}%");
        }

        $materis = $query->orderBy('urutan')->paginate(15)->withQueryString();

        // Mapel options for filter
        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })->orderBy('nama')->get();

        return view('guru.materi.index', compact('kelas', 'materis', 'mapels'));
    }

    /**
     * Show the form for creating new materi.
     */
    public function create(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.materi.create', compact('kelas', 'mapels'));
    }

    /**
     * Store newly created materi with file handling.
     */
    public function store(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        $validated = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'mapel_id'   => ['required', 'exists:mapels,id'],
            'konten'     => ['required', 'string'],
            'video_url'  => ['nullable', 'string', 'url', 'max:500'],
            'file'       => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['kelas_id'] = $kelas->id;
        $validated['user_id']  = Auth::id();
        $validated['is_published'] = $request->boolean('is_published', true);

        // Determine next order
        $lastOrder = Materi::where('kelas_id', $kelas->id)->max('urutan') ?? 0;
        $validated['urutan'] = $lastOrder + 1;

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('materi/' . $kelas->id, $filename, 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        Materi::create($validated);

        return redirect()
            ->route('guru.materi.index', $kelas)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Display the specified materi.
     */
    public function show(Materi $materi): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($materi->kelas);

        $materi->load(['kelas', 'mapel', 'user', 'comments.user']);

        return view('guru.materi.show', compact('materi'));
    }

    /**
     * Show the form for editing the specified materi.
     */
    public function edit(Materi $materi): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($materi->kelas);

        $materi->load('kelas');

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($materi) {
            $query->where('kelas_id', $materi->kelas_id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.materi.edit', compact('materi', 'mapels'));
    }

    /**
     * Update the specified materi.
     */
    public function update(Request $request, Materi $materi): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($materi->kelas);

        $validated = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'mapel_id'   => ['required', 'exists:mapels,id'],
            'konten'     => ['required', 'string'],
            'video_url'  => ['nullable', 'string', 'url', 'max:500'],
            'file'       => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published', $materi->is_published);

        // Handle new file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('materi/' . $materi->kelas_id, $filename, 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        $materi->update($validated);

        return redirect()
            ->route('guru.materi.show', $materi)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified materi and its file.
     */
    public function destroy(Materi $materi): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($materi->kelas);

        // Delete file
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()
            ->route('guru.materi.index', $materi->kelas_id)
            ->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Materi $materi): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($materi->kelas);

        $materi->update([
            'is_published' => ! $materi->is_published,
        ]);

        $status = $materi->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Materi berhasil {$status}.");
    }

    /**
     * Update materi order (reorder).
     */
    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id'   => ['required', 'exists:materis,id'],
            'items.*.urutan' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['items'] as $item) {
            Materi::where('id', $item['id'])->update(['urutan' => $item['urutan']]);
        }

        return response()->json(['message' => 'Urutan materi berhasil diperbarui.']);
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
