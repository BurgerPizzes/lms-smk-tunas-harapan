<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Submission;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaTugasController extends Controller
{
    /**
     * List tugas in a class with status indicators.
     */
    public function index(Request $request, Kelas $kelas): \Illuminate\View\View
    {
        $this->verifyEnrollment($kelas);

        $siswa = Auth::user();

        $query = Tugas::where('class_id', $kelas->id)
            ->where('is_published', true)
            ->with(['mapel', 'comments']);

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->whereHas('submissions', function ($q) use ($siswa, $status) {
                $q->where('siswa_id', $siswa->id);
                if ($status === 'submitted') {
                    $q->whereNotNull('file_path')
                      ->orWhereNotNull('konten');
                } elseif ($status === 'graded') {
                    $q->whereNotNull('nilai');
                } elseif ($status === 'not_submitted') {
                    // This needs a left join approach, handled below
                }
            });
        }

        $tugasList = $query->orderBy('deadline')
            ->paginate(15)
            ->withQueryString();

        // Annotate with submission status
        $tugasList->transform(function ($tugas) use ($siswa) {
            $submission = Submission::where('tugas_id', $tugas->id)
                ->where('siswa_id', $siswa->id)
                ->first();

            $tugas->submission = $submission;
            $tugas->is_submitted = $submission !== null;
            $tugas->is_graded = $submission && $submission->nilai !== null;
            $tugas->is_expired = $tugas->deadline && $tugas->deadline < now();

            return $tugas;
        });

        $mapels = \App\Models\Mapel::whereHas('tugas', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)->where('is_published', true);
        })->orderBy('nama')->get();

        return view('siswa.tugas.index', compact('kelas', 'tugasList', 'mapels'));
    }

    /**
     * View tugas detail.
     */
    public function show(Tugas $tugas): \Illuminate\View\View
    {
        $this->verifyEnrollment($tugas->kelas);

        $siswa = Auth::user();

        $tugas->load(['kelas', 'mapel', 'guru', 'comments.user']);

        $submission = Submission::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        $is_expired = $tugas->deadline && $tugas->deadline < now();

        return view('siswa.tugas.show', compact('tugas', 'submission', 'is_expired'));
    }

    /**
     * Submit tugas (upload file and/or text).
     */
    public function submit(Request $request, Tugas $tugas): \Illuminate\Http\RedirectResponse
    {
        $this->verifyEnrollment($tugas->kelas);

        // Check deadline
        if ($tugas->deadline && $tugas->deadline < now()) {
            return back()->withErrors('Batas waktu pengumpulan telah berakhir.');
        }

        $siswa = Auth::user();

        $validated = $request->validate([
            'jawaban' => ['nullable', 'string', 'max:10000'],
            'file'    => ['nullable', 'file', 'max:102400', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar,jpg,jpeg,png,gif'],
        ]);

        if (empty($validated['jawaban']) && ! $request->hasFile('file')) {
            return back()->withErrors('Anda harus mengisi jawaban atau menggah file.');
        }

        $submissionData = [
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'konten'   => $validated['jawaban'] ?? null,
            'submitted_at' => now(),
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $siswa->id . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('submissions/' . $tugas->id, $filename, 'public');
            $submissionData['file_path'] = $path;
        }

        // Update existing or create new submission
        $existingSubmission = Submission::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($existingSubmission) {
            // Delete old file if new file uploaded
            if ($request->hasFile('file') && $existingSubmission->file_path) {
                Storage::disk('public')->delete($existingSubmission->file_path);
            }

            $existingSubmission->update($submissionData);
        } else {
            Submission::create($submissionData);
        }

        return redirect()
            ->route('siswa.tugas.show', $tugas)
            ->with('success', 'Tugas berhasil dikumpulkan.');
    }

    /**
     * List all user's submissions.
     */
    public function mySubmissions(): \Illuminate\View\View
    {
        $siswa = Auth::user();

        $submissions = Submission::where('siswa_id', $siswa->id)
            ->with(['tugas.kelas', 'tugas.mapel'])
            ->latest('submitted_at')
            ->paginate(15);

        return view('siswa.tugas.submissions', compact('submissions'));
    }

    /**
     * View submission detail with grade.
     */
    public function showSubmission(Submission $submission): \Illuminate\View\View
    {
        $siswa = Auth::user();

        if ($submission->siswa_id !== $siswa->id) {
            abort(403, 'Anda tidak memiliki akses ke submission ini.');
        }

        $submission->load(['tugas.kelas', 'tugas.mapel']);

        return view('siswa.tugas.submission-detail', compact('submission'));
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
