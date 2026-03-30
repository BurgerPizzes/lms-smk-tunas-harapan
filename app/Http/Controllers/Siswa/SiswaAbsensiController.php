<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class SiswaAbsensiController extends Controller
{
    /**
     * Display attendance summary for all enrolled classes.
     */
    public function index(): \Illuminate\View\View
    {
        $siswa = Auth::user();

        $kelasList = $siswa->enrolledClasses()->orderBy('nama')->get();

        $kelasAbsensi = $kelasList->map(function ($kelas) use ($siswa) {
            $details = AttendanceDetail::whereHas('attendance', function ($query) use ($kelas) {
                $query->where('kelas_id', $kelas->id);
            })
            ->where('siswa_id', $siswa->id)
            ->get();

            $total = $details->count();

            return [
                'kelas_id'         => $kelas->id,
                'nama_kelas'       => $kelas->nama,
                'cover_color'      => $kelas->cover_color,
                'jurusan'          => $kelas->jurusan?->nama_jurusan ?? '',
                'total'            => $total,
                'total_pertemuan'  => $total,
                'hadir'            => $details->where('status', 'hadir')->count(),
                'izin'             => $details->where('status', 'izin')->count(),
                'sakit'            => $details->where('status', 'sakit')->count(),
                'alpha'            => $details->where('status', 'alpha')->count(),
                'persentase'       => $total > 0
                    ? round(($details->where('status', 'hadir')->count() / $total) * 100, 1)
                    : 0,
            ];
        });

        // Overall summary
        $overallDetails = AttendanceDetail::where('siswa_id', $siswa->id)->get();
        $overallTotal = $overallDetails->count();
        $overallStats = [
            'total'      => $overallTotal,
            'hadir'      => $overallDetails->where('status', 'hadir')->count(),
            'izin'       => $overallDetails->where('status', 'izin')->count(),
            'sakit'      => $overallDetails->where('status', 'sakit')->count(),
            'alpha'      => $overallDetails->where('status', 'alpha')->count(),
            'persentase' => $overallTotal > 0
                ? round(($overallDetails->where('status', 'hadir')->count() / $overallTotal) * 100, 1)
                : 0,
        ];

        return view('siswa.absensi.index', compact('kelasAbsensi', 'overallStats'));
    }

    /**
     * Display attendance records for a specific class.
     */
    public function byClass(Kelas $kelas): \Illuminate\View\View
    {
        $this->verifyEnrollment($kelas);

        $siswa = Auth::user();

        $attendances = Attendance::where('kelas_id', $kelas->id)
            ->with(['mapel', 'guru'])
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        // Annotate with student's status for each attendance session
        $attendances->transform(function ($attendance) use ($siswa) {
            $detail = AttendanceDetail::where('attendance_id', $attendance->id)
                ->where('siswa_id', $siswa->id)
                ->first();

            $attendance->student_status = $detail?->status ?? '-';
            $attendance->student_keterangan = $detail?->keterangan ?? '-';

            return $attendance;
        });

        // Summary for this class
        $details = AttendanceDetail::whereHas('attendance', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })
        ->where('siswa_id', $siswa->id)
        ->get();

        $total = $details->count();
        $summary = [
            'hadir'      => $details->where('status', 'hadir')->count(),
            'izin'       => $details->where('status', 'izin')->count(),
            'sakit'      => $details->where('status', 'sakit')->count(),
            'alpha'      => $details->where('status', 'alpha')->count(),
            'persentase' => $total > 0
                ? round(($details->where('status', 'hadir')->count() / $total) * 100, 1)
                : 0,
        ];

        return view('siswa.absensi.by-class', compact('kelas', 'attendances', 'summary'));
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
