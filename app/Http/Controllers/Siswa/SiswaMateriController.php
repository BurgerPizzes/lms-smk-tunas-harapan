<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaMateriController extends Controller
{
    /**
     * List published materi in a class with optional mapel filter.
     */
    public function index(Request $request, Kelas $kelas): \Illuminate\View\View
    {
        $this->verifyEnrollment($kelas);

        $query = Materi::where('class_id', $kelas->id)
            ->where('is_published', true)
            ->with('mapel', 'guru');

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('judul', 'like', "%{$search}%");
        }

        $materis = $query->orderBy('urutan')->paginate(15)->withQueryString();

        $mapels = Mapel::whereHas('materi', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)->where('is_published', true);
        })->orderBy('nama')->get();

        return view('siswa.materi.index', compact('kelas', 'materis', 'mapels'));
    }

    /**
     * View materi detail (content, video, download file).
     */
    public function show(Materi $materi): \Illuminate\View\View
    {
        $this->verifyEnrollment($materi->kelas);

        if (! $materi->is_published) {
            abort(404, 'Materi tidak ditemukan.');
        }

        $materi->load(['kelas', 'mapel', 'guru', 'comments.user']);

        // Mark materi as read/visited by student (optional tracking)
        $siswa = Auth::user();
        \App\Models\MateriView::firstOrCreate([
            'materi_id' => $materi->id,
            'user_id'   => $siswa->id,
        ]);

        return view('siswa.materi.show', compact('materi'));
    }

    /**
     * Download materi file.
     */
    public function download(Materi $materi): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
    {
        $this->verifyEnrollment($materi->kelas);

        if (! $materi->file_path) {
            return back()->withErrors('Tidak ada file untuk diunduh.');
        }

        $filePath = storage_path('app/public/' . $materi->file_path);

        if (! file_exists($filePath)) {
            return back()->withErrors('File tidak ditemukan.');
        }

        return response()->download($filePath, 'file');
    }

    /**
     * Verify the authenticated siswa is enrolled in the given class.
     */
    private function verifyEnrollment(Kelas $kelas): void
    {
        $siswa = Auth::user();

        if (! $siswa->enrolledClasses()->where('kelas.id', $kelas->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }
    }
}
