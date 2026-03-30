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
        $totalGuru     = User::role('guru')->count();
        $totalSiswa    = User::role('siswa')->count();
        $totalKelas    = Kelas::count();
        $totalMateri   = Materi::count();
        $totalTugas    = Tugas::count();

        // Recent activity logs (last 7 days)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Attendance summary for the current week (join with details to get status)
        $attendanceSummary = DB::table('attendances')
            ->join('attendance_details', 'attendances.id', '=', 'attendance_details.attendance_id')
            ->where('attendances.created_at', '>=', now()->startOfWeek())
            ->selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN attendance_details.status = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN attendance_details.status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN attendance_details.status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN attendance_details.status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        // Top performing students (highest average scores) — uses Spatie role check via join
        $topStudents = DB::table('submissions')
            ->join('users', 'submissions.siswa_id', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->where('model_has_roles.model_type', User::class)
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'siswa')
            ->whereNotNull('submissions.nilai')
            ->select('users.id', 'users.name', 'users.email', DB::raw('AVG(submissions.nilai) as average_nilai'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('average_nilai')
            ->take(5)
            ->get();

        // Monthly registration trend (last 6 months) — uses Spatie roles table
        $registrationTrend = User::selectRaw("
                DATE_FORMAT(users.created_at, '%Y-%m') as month,
                COUNT(*) as count,
                SUM(CASE WHEN roles.name = 'guru' THEN 1 ELSE 0 END) as guru_count,
                SUM(CASE WHEN roles.name = 'siswa' THEN 1 ELSE 0 END) as siswa_count
            ")
            ->leftJoin('model_has_roles', function ($join) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                     ->where('model_has_roles.model_type', User::class);
            })
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('users.created_at', '>=', now()->subMonths(6))
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
