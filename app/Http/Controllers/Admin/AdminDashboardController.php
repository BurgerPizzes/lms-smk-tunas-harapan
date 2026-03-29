<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and summaries.
     */
    public function index(): \Illuminate\View\View
    {
        // Total counts
        $totalUsers    = User::count();
        $totalGuru     = User::where('role', 'guru')->count();
        $totalSiswa    = User::where('role', 'siswa')->count();
        $totalKelas    = Kelas::count();
        $totalMateri   = Materi::count();
        $totalTugas    = Tugas::count();

        // Recent activity logs (last 7 days)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Attendance summary for the current week
        $attendanceSummary = DB::table('attendances')
            ->selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->where('created_at', '>=', now()->startOfWeek())
            ->first();

        // Top performing students (highest average scores)
        $topStudents = DB::table('submissions')
            ->join('users', 'submissions.user_id', '=', 'users.id')
            ->where('users.role', 'siswa')
            ->whereNotNull('submissions.nilai')
            ->select('users.id', 'users.name', 'users.email', DB::raw('AVG(submissions.nilai) as average_nilai'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('average_nilai')
            ->take(5)
            ->get();

        // Monthly registration trend (last 6 months)
        $registrationTrend = User::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count,
                SUM(CASE WHEN role = 'guru' THEN 1 ELSE 0 END) as guru_count,
                SUM(CASE WHEN role = 'siswa' THEN 1 ELSE 0 END) as siswa_count
            ")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGuru',
            'totalSiswa',
            'totalKelas',
            'totalMateri',
            'totalTugas',
            'recentActivities',
            'attendanceSummary',
            'topStudents',
            'registrationTrend'
        ));
    }
}
