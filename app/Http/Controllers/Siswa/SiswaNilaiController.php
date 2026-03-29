<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Submission;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;

class SiswaNilaiController extends Controller
{
    /**
     * Show grades for all enrolled classes.
     */
    public function index(): \Illuminate\View\View
    {
        $siswa = Auth::user();

        $kelasList = $siswa->kelas()->orderBy('nama')->get();

        $allGrades = [];
        $totalNilai = 0;
        $totalGraded = 0;

        foreach ($kelasList as $kelas) {
            $submissions = Submission::where('siswa_id', $siswa->id)
                ->whereHas('tugas', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id);
                })
                ->whereNotNull('nilai')
                ->with('tugas.mapel')
                ->get();

            $mapelGrades = $submissions->groupBy(fn ($s) => $s->tugas->mapel?->nama ?? 'Lainnya')
                ->map(function ($items) {
                    $graded = $items->whereNotNull('nilai');
                    return [
                        'average'  => $graded->isNotEmpty() ? round($graded->avg('nilai'), 1) : null,
                        'highest'  => $graded->max('nilai'),
                        'lowest'   => $graded->min('nilai'),
                        'count'    => $graded->count(),
                    ];
                });

            $totalForClass = $submissions->sum('nilai');
            $countForClass = $submissions->count();
            $totalNilai += $totalForClass;
            $totalGraded += $countForClass;

            $allGrades[] = [
                'kelas'      => $kelas,
                'mapel_grades' => $mapelGrades,
                'class_avg'  => $countForClass > 0 ? round($totalForClass / $countForClass, 1) : null,
                'total_submissions' => $countForClass,
            ];
        }

        $overallAverage = $totalGraded > 0 ? round($totalNilai / $totalGraded, 1) : null;

        return view('siswa.nilai.index', compact('allGrades', 'overallAverage'));
    }

    /**
     * Show grades for a specific class.
     */
    public function byClass(Kelas $kelas): \Illuminate\View\View
    {
        $this->verifyEnrollment($kelas);

        $siswa = Auth::user();

        $submissions = Submission::where('siswa_id', $siswa->id)
            ->whereHas('tugas', function ($query) use ($kelas) {
                $query->where('kelas_id', $kelas->id);
            })
            ->whereNotNull('nilai')
            ->with('tugas.mapel')
            ->latest('graded_at')
            ->get();

        $mapelGrades = $submissions->groupBy(fn ($s) => $s->tugas->mapel?->nama ?? 'Lainnya')
            ->map(function ($items) {
                $graded = $items->whereNotNull('nilai');
                return [
                    'mapel'       => $items->first()->tugas->mapel,
                    'submissions' => $items,
                    'average'     => $graded->isNotEmpty() ? round($graded->avg('nilai'), 1) : null,
                    'highest'     => $graded->max('nilai'),
                    'lowest'      => $graded->min('nilai'),
                    'count'       => $graded->count(),
                ];
            });

        $classAverage = $submissions->isNotEmpty()
            ? round($submissions->avg('nilai'), 1)
            : null;

        return view('siswa.nilai.by-class', compact('kelas', 'submissions', 'mapelGrades', 'classAverage'));
    }

    /**
     * Show grades for a specific subject in a class.
     */
    public function byMapel(Kelas $kelas, Mapel $mapel): \Illuminate\View\View
    {
        $this->verifyEnrollment($kelas);

        $siswa = Auth::user();

        $submissions = Submission::where('siswa_id', $siswa->id)
            ->whereHas('tugas', function ($query) use ($kelas, $mapel) {
                $query->where('kelas_id', $kelas->id)
                      ->where('mapel_id', $mapel->id);
            })
            ->with('tugas')
            ->latest('graded_at')
            ->get();

        $gradedSubmissions = $submissions->whereNotNull('nilai');

        $statistics = [
            'average'  => $gradedSubmissions->isNotEmpty() ? round($gradedSubmissions->avg('nilai'), 1) : null,
            'highest'  => $gradedSubmissions->max('nilai'),
            'lowest'   => $gradedSubmissions->min('nilai'),
            'count'    => $gradedSubmissions->count(),
            'kkm'      => $mapel->kkm,
            'passed'   => $gradedSubmissions->where('nilai', '>=', $mapel->kkm)->count(),
            'failed'   => $gradedSubmissions->where('nilai', '<', $mapel->kkm)->count(),
        ];

        return view('siswa.nilai.by-mapel', compact('kelas', 'mapel', 'submissions', 'statistics'));
    }

    /**
     * Verify the authenticated siswa is enrolled in the given class.
     */
    private function verifyEnrollment(Kelas $kelas): void
    {
        $siswa = Auth::user();

        if (! $siswa->kelas()->where('kelas.id', $kelas->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }
    }
}
