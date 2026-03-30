<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Submission;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GuruPenilaianController extends Controller
{
    /**
     * Display all submissions for a specific tugas.
     */
    public function index(Tugas $tugas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($tugas->kelas);

        $tugas->load(['kelas', 'mapel']);

        $submissions = Submission::where('tugas_id', $tugas->id)
            ->with('siswa')
            ->orderBy('nilai')
            ->paginate(20);

        $statistics = $this->calculateStatistics($tugas);

        return view('guru.penilaian.index', compact('tugas', 'submissions', 'statistics'));
    }

    /**
     * Grade a single submission with score and feedback.
     */
    public function grade(Request $request, Submission $submission): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($submission->tugas->kelas);

        $validated = $request->validate([
            'nilai'    => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ]);

        $submission->update([
            'nilai'    => $validated['nilai'],
            'feedback' => $validated['feedback'],
        ]);

        return back()->with('success', 'Nilai berhasil diberikan.');
    }

    /**
     * Bulk grade multiple submissions at once.
     */
    public function gradeBulk(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*.submission_id' => ['required', 'exists:submissions,id'],
            'grades.*.nilai'        => ['required', 'integer', 'min:0', 'max:100'],
            'grades.*.feedback'     => ['nullable', 'string', 'max:5000'],
        ]);

        $count = 0;
        foreach ($validated['grades'] as $gradeData) {
            $submission = Submission::find($gradeData['submission_id']);

            if ($submission && $this->canGrade($submission)) {
                $submission->update([
                    'nilai'     => $gradeData['nilai'],
                    'feedback'  => $gradeData['feedback'] ?? null,
                ]);
                $count++;
            }
        }

        return back()->with('success', "{$count} submission berhasil dinilai.");
    }

    /**
     * Export grades as CSV for a class and mapel combination.
     */
    public function exportNilai(Kelas $kelas, Mapel $mapel): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorizeGuruAccess($kelas);

        $tugasList = Tugas::where('class_id', $kelas->id)
            ->where('mapel_id', $mapel->id)
            ->orderBy('created_at')
            ->get();

        $siswa = $kelas->siswas()->orderBy('name')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="nilai_' . Str::slug($kelas->nama) . '_' . Str::slug($mapel->nama) . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($tugasList, $siswa) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            $header = ['No', 'Nama Siswa', 'NIS'];
            foreach ($tugasList as $tugas) {
                $header[] = Str::limit($tugas->judul, 25);
            }
            $header[] = 'Rata-rata';
            fputcsv($file, $header);

            // Data rows
            foreach ($siswa as $index => $s) {
                $row = [$index + 1, $s->name, $s->nis ?? '-'];
                $totalNilai = 0;
                $gradedCount = 0;

                foreach ($tugasList as $tugas) {
                    $submission = Submission::where('tugas_id', $tugas->id)
                        ->where('siswa_id', $s->id)
                        ->first();

                    $nilai = $submission?->nilai ?? '-';
                    $row[] = $nilai;

                    if ($nilai !== '-') {
                        $totalNilai += $nilai;
                        $gradedCount++;
                    }
                }

                $average = $gradedCount > 0 ? round($totalNilai / $gradedCount, 1) : '-';
                $row[] = $average;

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show grade recap for all tugas in a class.
     */
    public function recapNilai(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $kelas->load(['siswas', 'guruMapel.mapel']);

        $mapels = $kelas->guruMapel->pluck('mapel');

        $recapData = [];
        foreach ($mapels as $mapel) {
            $tugasList = Tugas::where('class_id', $kelas->id)
                ->where('mapel_id', $mapel->id)
                ->orderBy('created_at')
                ->get();

            $siswaData = $kelas->siswas->map(function ($siswa) use ($tugasList) {
                $submissions = Submission::whereIn('tugas_id', $tugasList->pluck('id'))
                    ->where('siswa_id', $siswa->id)
                    ->get();

                $graded = $submissions->whereNotNull('nilai');
                $average = $graded->isNotEmpty() ? round($graded->avg('nilai'), 1) : null;

                return [
                    'siswa'       => $siswa,
                    'submitted'   => $submissions->count(),
                    'graded'      => $graded->count(),
                    'average'     => $average,
                    'highest'     => $graded->max('nilai'),
                    'lowest'      => $graded->min('nilai'),
                ];
            });

            $recapData[] = [
                'mapel'       => $mapel,
                'tugas_count' => $tugasList->count(),
                'siswa_data'  => $siswaData,
            ];
        }

        return view('guru.penilaian.recap', compact('kelas', 'recapData'));
    }

    /**
     * Calculate grading statistics for a tugas.
     */
    private function calculateStatistics(Tugas $tugas): array
    {
        $submissions = Submission::where('tugas_id', $tugas->id)
            ->whereNotNull('nilai')
            ->get();

        if ($submissions->isEmpty()) {
            return [
                'count' => 0, 'average' => 0, 'highest' => 0,
                'lowest' => 0, 'median' => 0, 'passed' => 0,
                'failed' => 0,
            ];
        }

        $sorted = $submissions->pluck('nilai')->sort()->values();
        $count = $sorted->count();
        $mid = intdiv($count, 2);

        $median = $count % 2 === 0
            ? ($sorted[$mid - 1] + $sorted[$mid]) / 2
            : $sorted[$mid];

        $kkm = $tugas->mapel?->kkm ?? 75;

        return [
            'count'   => $count,
            'average' => round($sorted->avg(), 1),
            'highest' => $sorted->max(),
            'lowest'  => $sorted->min(),
            'median'  => $median,
            'passed'  => $sorted->where(fn ($v) => $v >= $kkm)->count(),
            'failed'  => $sorted->where(fn ($v) => $v < $kkm)->count(),
        ];
    }

    /**
     * Check if the guru can grade the given submission.
     */
    private function canGrade(Submission $submission): bool
    {
        $guru = auth()->user();
        return GuruMapel::where('guru_id', $guru->id)
            ->where('class_id', $submission->tugas->class_id)
            ->where('mapel_id', $submission->tugas->mapel_id)
            ->exists();
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
