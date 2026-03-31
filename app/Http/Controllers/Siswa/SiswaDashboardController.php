<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Notification;
use App\Models\Submission;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaDashboardController extends Controller
{
    /**
     * Display the siswa dashboard with overview data.
     */
    public function index(): \Illuminate\View\View
    {
        $siswa = Auth::user();

        // Enrolled classes
        $enrolledClasses = $siswa->enrolledClasses()
            ->with(['jurusan', 'waliKelas'])
            ->orderBy('nama')
            ->get();

        $kelasIds = $enrolledClasses->pluck('id')->toArray();

        // Upcoming deadlines (open tugas in enrolled classes)
        $upcomingTugas = Tugas::whereIn('class_id', $kelasIds)
            ->where('deadline', '>=', now())
            ->where('is_published', true)
            ->with(['kelas', 'mapel', 'submissions' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }])
            ->orderBy('deadline')
            ->take(10)
            ->get();

        // Recent grades
        $recentGrades = Submission::where('siswa_id', $siswa->id)
            ->whereNotNull('nilai')
            ->with(['tugas.kelas', 'tugas.mapel'])
            ->latest('submitted_at')
            ->take(10)
            ->get();

        // Average grade
        $averageGrade = Submission::where('siswa_id', $siswa->id)
            ->whereNotNull('nilai')
            ->avg('nilai');

        // Attendance summary
        $attendanceSummary = DB::table('attendance_details')
            ->where('siswa_id', $siswa->id)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        // Unread notifications
        $notifications = Notification::where('user_id', $siswa->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', $siswa->id)
            ->where('is_read', false)
            ->count();

        // Stats for cards
        $totalTugas = Tugas::whereIn('class_id', $kelasIds)
            ->where('is_published', true)
            ->count();
        $tugasSelesai = Submission::where('siswa_id', $siswa->id)
            ->count();
        $kehadiran = $attendanceSummary && $attendanceSummary->total > 0
            ? round(($attendanceSummary->hadir / $attendanceSummary->total) * 100, 1)
            : 0;

        $stats = [
            'kelas'         => $enrolledClasses->count(),
            'tugas_selesai' => $tugasSelesai,
            'total_tugas'   => $totalTugas,
            'nilai_rata'    => $averageGrade ? round($averageGrade, 1) : 0,
            'kehadiran'     => $kehadiran,
        ];

        return view('siswa.dashboard', compact(
            'siswa',
            'enrolledClasses',
            'upcomingTugas',
            'recentGrades',
            'averageGrade',
            'attendanceSummary',
            'notifications',
            'unreadCount',
            'stats'
        ));
    }
}
