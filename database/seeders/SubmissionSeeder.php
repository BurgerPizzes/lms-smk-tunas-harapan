<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates 120+ submissions with mixed statuses.
     */
    public function run(): void
    {
        $tugasList = Tugas::where('is_published', true)->get();
        $siswas    = User::whereHas('roles', fn($q) => $q->where('name', 'siswa'))
            ->orderBy('id')->get();
        $feedbackOptions = [
            'Bagus, tetapi perlu diperbaiki pada bagian struktur kode.',
            'Sangat baik! Pengerjaan rapi dan sesuai instruksi.',
            'Cukup baik, namun masih ada beberapa kesalahan logika.',
            'Perlu revisi: perhatikan kembali requirement yang diminta.',
            'Luar biasa! Kreatif dan implementasi sangat baik.',
            'Ada beberapa syntax error yang perlu diperbaiki.',
            'Kerja bagus, tapi dokumentasi kurang lengkap.',
            'Sangat memuaskan, keep up the good work!',
            'Perlu lebih teliti dalam mengerjakan soal.',
            'Bagus, cobalah untuk lebih mengoptimalkan kode.',
        ];

        $submissionCount = 0;
        $statuses = ['submitted', 'late', 'not_submitted'];
        $statusWeights = [50, 20, 30]; // 50% submitted, 20% late, 30% not submitted

        foreach ($tugasList as $tugas) {
            // Get siswa enrolled in this kelas
            $kelasSiswa = $tugas->kelas->siswas ?? collect();
            if ($kelasSiswa->isEmpty()) {
                continue;
            }

            foreach ($kelasSiswa as $siswa) {
                // Weighted random status
                $rand = mt_rand(1, 100);
                if ($rand <= 50) {
                    $status = 'submitted';
                } elseif ($rand <= 70) {
                    $status = 'late';
                } else {
                    $status = 'not_submitted';
                }

                $nilai   = null;
                $feedback = null;
                $konten  = null;
                $filePath = null;
                $submittedAt = null;

                if ($status === 'submitted') {
                    $submittedAt = $tugas->deadline
                        ? $tugas->deadline->copy()->subDays(mt_rand(0, 5))
                        : now()->subDays(mt_rand(1, 10));

                    // 70% chance of being graded
                    if (mt_rand(1, 100) <= 70) {
                        $nilai = mt_rand(60, 100);
                        $feedback = $feedbackOptions[mt_rand(0, count($feedbackOptions) - 1)];
                    }

                    $filePath = 'submissions/' . $siswa->id . '/' . $tugas->id . '_tugas.zip';
                    $konten = 'Assalamualaikum Pak/Bu, saya mengumpulkan tugas ' . $tugas->judul . '. Terima kasih.';
                } elseif ($status === 'late') {
                    $submittedAt = $tugas->deadline
                        ? $tugas->deadline->copy()->addDays(mt_rand(1, 7))
                        : now()->subDays(1);

                    // 50% chance of being graded
                    if (mt_rand(1, 100) <= 50) {
                        $nilai = mt_rand(50, 85);
                        $feedback = $feedbackOptions[mt_rand(0, count($feedbackOptions) - 1)];
                    }

                    $filePath = 'submissions/' . $siswa->id . '/' . $tugas->id . '_tugas_terlambat.zip';
                    $konten = 'Mohon maaf, tugas ini terlambat dikumpulkan karena alasan kesehatan.';
                }
                // not_submitted: everything stays null

                Submission::create([
                    'tugas_id'    => $tugas->id,
                    'siswa_id'    => $siswa->id,
                    'konten'      => $konten,
                    'file_path'   => $filePath,
                    'submitted_at'=> $submittedAt,
                    'status'      => $status,
                    'nilai'       => $nilai,
                    'feedback'    => $feedback,
                    'catatan_guru'=> null,
                ]);

                $submissionCount++;
            }
        }

        $this->command->info("✓ {$submissionCount} submissions berhasil dibuat");
    }
}
