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
        $assignedClasses = GuruMapel::with(['kelas', 'mapel', 'tahunAjaran'])
            ->where('guru_id', $guru->id)
            ->get();

        // Class IDs for further queries
        $kelasIds = $assignedClasses->pluck('kelas_id')->unique()->filter()->toArray();
        $mapelIds = $assignedClasses->pluck('mapel_id')->unique()->filter()->toArray();

        // Upcoming deadlines (tugas that are still open and have ungraded submissions)
        $upcomingDeadlines = Tugas::whereIn('kelas_id', $kelasIds)
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->take(10)
            ->get();

        // Recent submissions that need grading
        $recentSubmissions = Submission::with(['tugas', 'user'])
            ->whereHas('tugas', function ($query) use ($kelasIds, $mapelIds) {
                $query->whereIn('kelas_id', $kelasIds)
                      ->whereIn('mapel_id', $mapelIds);
            })
            ->whereNull('nilai')
            ->latest()
            ->take(10)
            ->get();

        // Ungraded submissions count
        $ungradedCount = Submission::whereHas('tugas', function ($query) use ($kelasIds, $mapelIds) {
            $query->whereIn('kelas_id', $kelasIds)
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

        // Total tugas created
        $totalTugas = Tugas::whereIn('kelas_id', $kelasIds)
            ->whereIn('mapel_id', $mapelIds)
            ->count();

        return view('guru.dashboard', compact(
            'guru',
            'assignedClasses',
            'upcomingDeadlines',
            'recentSubmissions',
            'ungradedCount',
            'attendanceSummary',
            'totalMateri',
            'totalTugas'
        ));
    }
}
