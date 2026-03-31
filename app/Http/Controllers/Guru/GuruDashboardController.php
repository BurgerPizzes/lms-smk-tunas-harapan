<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Submission;
use App\Models\Tugas;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruDashboardController extends Controller
{
    /**
     * Display the guru dashboard with overview data.
     */
    public function index(): \Illuminate\View\View
    {
        $guru = Auth::user();

        // Classes assigned to this guru
        $assignedClasses = GuruMapel::with(['kelas.jurusan', 'mapel', 'tahunAjaran'])
            ->where('guru_id', $guru->id)
            ->get();

        // Class IDs for further queries
        $kelasIds = $assignedClasses->pluck('class_id')->unique()->filter()->toArray();
        $mapelIds = $assignedClasses->pluck('mapel_id')->unique()->filter()->toArray();

        // Upcoming deadlines with eager-loaded kelas (with siswas) and mapel
        $upcomingDeadlines = Tugas::with(['kelas.siswas', 'mapel'])
            ->whereIn('class_id', $kelasIds)
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->take(10)
            ->get();

        // Recent submissions that need grading (eager load tugas.kelas to avoid N+1)
        $recentSubmissions = Submission::with(['tugas.kelas', 'tugas.mapel', 'siswa'])
            ->whereHas('tugas', function ($query) use ($kelasIds, $mapelIds) {
                $query->whereIn('class_id', $kelasIds)
                      ->whereIn('mapel_id', $mapelIds);
            })
            ->whereNull('nilai')
            ->latest()
            ->take(10)
            ->get();

        // Ungraded submissions count
        $ungradedCount = Submission::whereHas('tugas', function ($query) use ($kelasIds, $mapelIds) {
            $query->whereIn('class_id', $kelasIds)
                  ->whereIn('mapel_id', $mapelIds);
        })
        ->whereNull('nilai')
        ->count();

        // Attendance summary this week
        $attendanceSummary = DB::table('attendances')
            ->join('attendance_details', 'attendances.id', '=', 'attendance_details.attendance_id')
            ->where('attendances.guru_id', $guru->id)
            ->where('attendances.created_at', '>=', now()->startOfWeek())
            ->selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN attendance_details.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN attendance_details.status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN attendance_details.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN attendance_details.status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        // Total materi created
        $totalMateri = Materi::where('guru_id', $guru->id)->count();

        // Materi created this week
        $materiGrowth = Materi::where('guru_id', $guru->id)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        // Total tugas created
        $totalTugas = Tugas::whereIn('class_id', $kelasIds)
            ->whereIn('mapel_id', $mapelIds)
            ->count();

        // Active tugas (deadline not passed)
        $tugasAktif = Tugas::whereIn('class_id', $kelasIds)
            ->whereIn('mapel_id', $mapelIds)
            ->where('deadline', '>=', now())
            ->count();

        // Deadlines within 24 hours
        $deadlineTerdekat = Tugas::whereIn('class_id', $kelasIds)
            ->whereIn('mapel_id', $mapelIds)
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addHours(24))
            ->count();

        // Total unique kelas
        $totalKelas = count($kelasIds);

        // Total siswa across all classes
        $totalSiswa = \App\Models\Kelas::withCount('siswas')
            ->whereIn('id', $kelasIds)
            ->get()
            ->sum('siswas_count');

        // Build stats array expected by the view
        $stats = [
            'totalKelas'     => $totalKelas,
            'totalSiswa'     => $totalSiswa,
            'totalMateri'    => $totalMateri,
            'materiGrowth'   => $materiGrowth,
            'totalTugas'     => $totalTugas,
            'tugasAktif'     => $tugasAktif,
            'perluDinilai'   => $ungradedCount,
            'deadlineTerdekat' => $deadlineTerdekat,
        ];

        return view('guru.dashboard', compact(
            'guru',
            'assignedClasses',
            'upcomingDeadlines',
            'recentSubmissions',
            'ungradedCount',
            'attendanceSummary',
            'totalMateri',
            'totalTugas',
            'stats'
        ));
    }
}
