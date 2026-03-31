<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class SiswaNilaiController extends Controller
{
    /**
     * Show grades for all enrolled classes.
     */
    public function index(): \Illuminate\View\View
    {
        $siswa = Auth::user();

        $kelasList = $siswa->enrolledClasses()->orderBy('nama')->get();

        $kelasNilai = [];
        $totalRataRata = 0;
        $totalDinilai = 0;
        $totalLulus = 0;
        $kelasCount = 0;

        foreach ($kelasList as $kelas) {
            $submissions = Submission::where('siswa_id', $siswa->id)
                ->whereHas('tugas', function ($query) use ($kelas) {
                    $query->where('class_id', $kelas->id);
                })
                ->whereNotNull('nilai')
                ->with('tugas.mapel')
                ->get();

            $mapelGrades = $submissions->groupBy(fn ($s) => $s->tugas->mapel?->nama ?? 'Lainnya')
                ->map(function ($items) {
                    $graded = $items->whereNotNull('nilai');
                    return [
                        'nama_mapel' => $items->first()->tugas->mapel?->nama ?? 'Lainnya',
                        'jumlah'     => $graded->count(),
                        'rata_rata'  => $graded->isNotEmpty() ? round($graded->avg('nilai'), 1) : null,
                    ];
                });

            $gradedCount = $submissions->count();
            $classAvg = $gradedCount > 0 ? round($submissions->avg('nilai'), 1) : null;

            if ($classAvg !== null) {
                $totalRataRata += $classAvg;
                $kelasCount++;
            }
            $totalDinilai += $gradedCount;
            $totalLulus += $submissions->where('nilai', '>=', 75)->count();

            $kelasNilai[] = [
                'kelas_id'    => $kelas->id,
                'nama_kelas'  => $kelas->nama,
                'cover_color' => $kelas->cover_color,
                'jumlah_nilai'=> $gradedCount,
                'rata_rata'   => $classAvg,
                'mapel'       => $mapelGrades->values()->toArray(),
            ];
        }

        $overallStats = [
            'rata_rata'    => $kelasCount > 0 ? round($totalRataRata / $kelasCount, 1) : 0,
            'total_dinilai'=> $totalDinilai,
            'total_lulus'  => $totalLulus,
        ];

        return view('siswa.nilai.index', compact('kelasNilai', 'overallStats'));
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
                $query->where('class_id', $kelas->id);
            })
            ->whereNotNull('nilai')
            ->with('tugas.mapel')
            ->latest('submitted_at')
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

        // Mapel list for filter buttons
        $mapelList = \App\Models\Mapel::whereHas('tugas', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id);
        })->orderBy('nama')->get();

        $lulusCount = $submissions->where('nilai', '>=', 75)->count();
        $totalNilai = $submissions->count();
        $rataRata = $classAverage;

        $nilaiList = $submissions;

        return view('siswa.nilai.by-class', compact('kelas', 'submissions', 'nilaiList', 'mapelGrades', 'classAverage', 'mapelList', 'rataRata', 'lulusCount', 'totalNilai'));
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
                $query->where('class_id', $kelas->id)
                      ->where('mapel_id', $mapel->id);
            })
            ->with('tugas')
            ->latest('submitted_at')
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

        if (! $siswa->enrolledClasses()->where('kelas.id', $kelas->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }
    }
}
