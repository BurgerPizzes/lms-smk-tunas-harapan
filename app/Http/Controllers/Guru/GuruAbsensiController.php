<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruAbsensiController extends Controller
{
    /**
     * Display a listing of attendance sessions for a class.
     */
    public function index(Request $request, Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $query = Attendance::where('class_id', $kelas->id)
            ->with(['mapel', 'guru']);

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->input('mapel_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->input('date_to'));
        }

        $attendances = $query->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.absensi.index', compact('kelas', 'attendances', 'mapels'));
    }

    /**
     * Show the form for creating a new attendance session.
     */
    public function create(Kelas $kelas): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $kelas->load('siswas');

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        return view('guru.absensi.create', compact('kelas', 'mapels'));
    }

    /**
     * Store a new attendance session with per-student status.
     */
    public function store(Request $request, Kelas $kelas): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($kelas);

        // View sends absensi[$siswaId] and keterangan[$siswaId]
        // Controller expects statuses[] array - transform here
        $statuses = [];
        foreach ($request->input('absensi', []) as $siswaId => $status) {
            $statuses[] = [
                'user_id' => $siswaId,
                'status' => $status,
                'keterangan' => $request->input("keterangan.{$siswaId}"),
            ];
        }

        $validated = $request->validate([
            'mapel_id' => ['required', 'exists:mapels,id'],
            'tanggal'  => ['required', 'date', 'before_or_equal:today'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'statuses' => ['required', 'array', 'min:1'],
            'statuses.*.user_id' => ['required', 'exists:users,id'],
            'statuses.*.status'  => ['required', 'string', 'in:hadir,izin,sakit,alpha'],
            'statuses.*.keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['statuses'] = $statuses;
            ->where('mapel_id', $validated['mapel_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->exists();

        if ($exists) {
            return back()->withErrors('Absensi untuk mata pelajaran, kelas, dan tanggal ini sudah pernah dibuat.');
        }

        DB::beginTransaction();
        try {
            $attendance = Attendance::create([
                'class_id' => $kelas->id,
                'mapel_id' => $validated['mapel_id'],
                'guru_id'  => Auth::id(),
                'tanggal'  => $validated['tanggal'],
                'catatan'  => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['statuses'] as $statusData) {
                AttendanceDetail::create([
                    'attendance_id' => $attendance->id,
                    'siswa_id'      => $statusData['user_id'],
                    'status'        => $statusData['status'],
                    'keterangan'    => $statusData['keterangan'] ?? null,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menyimpan absensi: ' . $e->getMessage());
        }

        return redirect()
            ->route('guru.kelas.absensi.index', $kelas)
            ->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * Display the specified attendance session detail.
     */
    public function show(Attendance $attendance): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($attendance->kelas);

        $attendance->load(['kelas', 'mapel', 'guru', 'details.siswa']);

        $detailList = $attendance->details;

        $stats = [
            'hadir'  => $detailList->where('status', 'hadir')->count(),
            'izin'   => $detailList->where('status', 'izin')->count(),
            'sakit'  => $detailList->where('status', 'sakit')->count(),
            'alpha'  => $detailList->where('status', 'alpha')->count(),
        ];

        return view('guru.absensi.show', compact('attendance', 'detailList', 'stats'));
    }

    /**
     * Update the specified attendance session.
     */
    public function update(Request $request, Attendance $attendance): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeGuruAccess($attendance->kelas);

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
            'statuses' => ['required', 'array'],
            'statuses.*.id'         => ['required', 'exists:attendance_details,id'],
            'statuses.*.user_id'   => ['required', 'exists:users,id'],
            'statuses.*.status'    => ['required', 'string', 'in:hadir,izin,sakit,alpha'],
            'statuses.*.keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $attendance->update([
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['statuses'] as $statusData) {
                AttendanceDetail::where('id', $statusData['id'])
                    ->where('attendance_id', $attendance->id)
                    ->update([
                        'status'     => $statusData['status'],
                        'keterangan' => $statusData['keterangan'] ?? null,
                    ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal memperbarui absensi: ' . $e->getMessage());
        }

        return redirect()
            ->route('guru.absensi.show', $attendance)
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Display attendance recap per student for a class.
     */
    public function recap(Kelas $kelas, Request $request): \Illuminate\View\View
    {
        $this->authorizeGuruAccess($kelas);

        $siswa = $kelas->siswas()->orderBy('name')->get();

        $mapels = Mapel::whereHas('guruMapel', function ($query) use ($kelas) {
            $query->where('class_id', $kelas->id)
                  ->where('guru_id', Auth::id());
        })->orderBy('nama')->get();

        $recapData = $siswa->map(function ($s) use ($kelas) {
            $attendanceDetails = AttendanceDetail::whereHas('attendance', function ($query) use ($kelas) {
                $query->where('class_id', $kelas->id);
            })
            ->where('siswa_id', $s->id)
            ->get();

            $totalSessions = $attendanceDetails->count();

            return [
                'name'        => $s->name,
                'nis'         => $s->nis ?? '-',
                'hadir'       => $attendanceDetails->where('status', 'hadir')->count(),
                'izin'        => $attendanceDetails->where('status', 'izin')->count(),
                'sakit'       => $attendanceDetails->where('status', 'sakit')->count(),
                'alpha'       => $attendanceDetails->where('status', 'alpha')->count(),
                'persentase'  => $totalSessions > 0
                    ? round(($attendanceDetails->where('status', 'hadir')->count() / $totalSessions) * 100, 1)
                    : 0,
            ];
        });

        return view('guru.absensi.recap', compact('kelas', 'recapData', 'mapels'));
    }

    /**
     * Export attendance recap to CSV.
     */
    public function exportRecap(Kelas $kelas): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorizeGuruAccess($kelas);

        $siswa = $kelas->siswas()->orderBy('name')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="absensi_' . \Illuminate\Support\Str::slug($kelas->nama) . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($siswa, $kelas) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['No', 'Nama Siswa', 'NIS', 'Hadir', 'Izin', 'Sakit', 'Alpha', 'Total', 'Persentase']);

            foreach ($siswa as $index => $s) {
                $details = AttendanceDetail::whereHas('attendance', function ($query) use ($kelas) {
                    $query->where('class_id', $kelas->id);
                })
                ->where('siswa_id', $s->id)
                ->get();

                $total = $details->count();
                $hadir = $details->where('status', 'hadir')->count();
                $izin  = $details->where('status', 'izin')->count();
                $sakit = $details->where('status', 'sakit')->count();
                $alpha = $details->where('status', 'alpha')->count();
                $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                fputcsv($file, [
                    $index + 1, $s->name, $s->nis ?? '-',
                    $hadir, $izin, $sakit, $alpha, $total, $persentase . '%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
