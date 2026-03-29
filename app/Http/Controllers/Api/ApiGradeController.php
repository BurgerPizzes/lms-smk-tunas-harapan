<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Submission;
use App\Models\Tugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiGradeController extends Controller
{
    /**
     * Get grade list for a class and subject.
     */
    public function index(Request $request, Kelas $kelas, Mapel $mapel): JsonResponse
    {
        $user = $request->user();

        // Guru can view, siswa can view own
        $query = Submission::whereHas('tugas', function ($query) use ($kelas, $mapel) {
            $query->where('kelas_id', $kelas->id)
                  ->where('mapel_id', $mapel->id);
        })
        ->whereNotNull('nilai')
        ->with(['user', 'tugas']);

        if ($user->hasRole('siswa')) {
            $query->where('siswa_id', $user->id);
        }

        $submissions = $query->orderBy('graded_at', 'desc')->paginate(20);

        // Calculate statistics
        $allGraded = Submission::whereHas('tugas', function ($query) use ($kelas, $mapel) {
            $query->where('kelas_id', $kelas->id)->where('mapel_id', $mapel->id);
        })->whereNotNull('nilai');

        $statistics = [
            'average' => round($allGraded->avg('nilai'), 1),
            'highest' => $allGraded->max('nilai'),
            'lowest'  => $allGraded->min('nilai'),
            'count'   => $allGraded->count(),
            'kkm'     => $mapel->kkm,
            'passed'  => $allGraded->where('nilai', '>=', $mapel->kkm)->count(),
            'failed'  => $allGraded->where('nilai', '<', $mapel->kkm)->count(),
        ];

        return response()->json([
            'success'    => true,
            'data'       => $submissions,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Create or update a grade.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole('guru')) {
            return response()->json(['message' => 'Hanya guru yang dapat memberi nilai.'], 403);
        }

        $validated = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*.submission_id' => ['required', 'exists:submissions,id'],
            'grades.*.nilai'        => ['required', 'integer', 'min:0', 'max:100'],
            'grades.*.feedback'     => ['nullable', 'string', 'max:5000'],
        ]);

        $count = 0;
        foreach ($validated['grades'] as $gradeData) {
            $submission = Submission::find($gradeData['submission_id']);
            if ($submission) {
                $submission->update([
                    'nilai'     => $gradeData['nilai'],
                    'feedback'  => $gradeData['feedback'] ?? null,
                    'graded_at' => now(),
                ]);
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} nilai berhasil disimpan.",
        ]);
    }

    /**
     * Export grades for a class.
     */
    public function export(Request $request, Kelas $kelas): \Symfony\Component\HttpFoundation\StreamedResponse|JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole('guru')) {
            return response()->json(['message' => 'Hanya guru yang dapat mengekspor nilai.'], 403);
        }

        $tugasList = Tugas::where('kelas_id', $kelas->id)
            ->with('mapel')
            ->orderBy('created_at')
            ->get();

        $siswa = $kelas->siswa()->orderBy('name')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="nilai_' . \Illuminate\Support\Str::slug($kelas->nama) . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($tugasList, $siswa) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $header = ['No', 'Nama', 'NIS'];
            foreach ($tugasList as $tugas) {
                $header[] = Str::limit($tugas->judul, 25);
            }
            $header[] = 'Rata-rata';
            fputcsv($file, $header);

            foreach ($siswa as $index => $s) {
                $row = [$index + 1, $s->name, $s->nis_nip ?? '-'];
                $total = 0;
                $graded = 0;

                foreach ($tugasList as $tugas) {
                    $sub = Submission::where('tugas_id', $tugas->id)
                        ->where('user_id', $s->id)
                        ->first();

                    $val = $sub?->nilai ?? '-';
                    $row[] = $val;

                    if ($val !== '-') {
                        $total += $val;
                        $graded++;
                    }
                }

                $row[] = $graded > 0 ? round($total / $graded, 1) : '-';
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
