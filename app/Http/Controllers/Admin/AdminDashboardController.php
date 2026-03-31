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
        $activeKelas  = Kelas::where('is_active', true)->count();
        $totalMateri   = Materi::count();
        $totalTugas    = Tugas::count();

        // Active tahun ajaran
        $activeTA = \App\Models\TahunAjaran::where('aktif', true)->first();
        $tahunAjaranLabel = $activeTA
            ? $activeTA->nama . ' ' . ucfirst($activeTA->semester)
            : '-';

        // Growth stats (this month vs last month)
        $siswaGrowth = User::role('siswa')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $guruGrowth = User::role('guru')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $materiGrowth = Materi::where('created_at', '>=', now()->subWeek())->count();
        $tugasPending = \App\Models\Submission::whereNull('nilai')->count();

        // Build stats array (used by dashboard view)
        $stats = [
            'totalSiswa'      => $totalSiswa,
            'totalGuru'       => $totalGuru,
            'totalKelas'      => $totalKelas,
            'totalMateri'     => $totalMateri,
            'totalTugas'      => $totalTugas,
            'kelasAktif'      => $activeKelas,
            'tahunAjaran'     => $tahunAjaranLabel,
            'semester'        => $activeTA ? ucfirst($activeTA->semester) : '-',
            'siswaGrowth'      => $siswaGrowth,
            'guruGrowth'      => $guruGrowth,
            'materiGrowth'    => $materiGrowth,
            'tugasPending'    => $tugasPending,
        ];

        // Recent activity logs
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Monthly registrations for chart
        $monthlyRegistrations = User::selectRaw("
                DATE_FORMAT(users.created_at, '%Y-%m') as month,
                COUNT(*) as count
            ")
            ->where('users.created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $maxRegistration = $monthlyRegistrations->max('count') ?? 1;

        // Jurusan distribution for pie chart
        $jurusans = \App\Models\Jurusan::withCount('users')->orderBy('nama')->get();
        $colors = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#14b8a6'];
        $jurusanDistribution = [];
        $jurusanColors = [];
        foreach ($jurusans as $i => $j) {
            if ($j->users_count > 0) {
                $jurusanDistribution[] = [
                    'nama'  => $j->nama,
                    'count' => $j->users_count,
                    'color' => $colors[$i % count($colors)],
                ];
                $jurusanColors[] = $colors[$i % count($colors)];
            }
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'monthlyRegistrations',
            'maxRegistration',
            'jurusanDistribution',
            'jurusanColors'
        ));
    }
}
