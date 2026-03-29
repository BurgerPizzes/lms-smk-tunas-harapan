<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates notifications for users with various types.
     */
    public function run(): void
    {
        $users = User::where('is_active', true)->get();
        $admin = User::where('email', 'admin@smktunas.sch.id')->first();
        $gurus = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))->get();
        $siswas = User::whereHas('roles', fn($q) => $q->where('name', 'siswa'))->take(20)->get();

        $notifications = [];

        // ─── Tugas Baru notifications (for siswa) ───────────────────
        $tugasList = Tugas::where('is_published', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->take(10)
            ->get();

        foreach ($tugasList as $tugas) {
            $kelasSiswa = $tugas->kelas->siswas ?? collect();
            foreach ($kelasSiswa as $siswa) {
                $notifications[] = [
                    'user_id' => $siswa->id,
                    'type'    => 'tugas_baru',
                    'title'   => 'Tugas Baru: ' . $tugas->judul,
                    'message' => "Guru {$tugas->guru->name} memberikan tugas baru pada mapel {$tugas->mapel->nama}. Deadline: {$tugas->deadline?->format('d M Y H:i')}.",
                    'data'    => ['tugas_id' => $tugas->id, 'kelas_id' => $tugas->kelas_id, 'mapel_id' => $tugas->mapel_id],
                    'is_read' => mt_rand(0, 1) === 1,
                    'read_at' => mt_rand(0, 1) === 1 ? now()->subHours(mt_rand(1, 48)) : null,
                    'link'    => '/guru/tugas/' . $tugas->id,
                ];
            }
        }

        // ─── Deadline reminders ─────────────────────────────────────
        $upcomingTugas = Tugas::where('is_published', true)
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays(7))
            ->take(5)
            ->get();

        foreach ($upcomingTugas as $tugas) {
            $kelasSiswa = $tugas->kelas->siswas ?? collect();
            foreach ($kelasSiswa as $siswa) {
                $notifications[] = [
                    'user_id' => $siswa->id,
                    'type'    => 'deadline',
                    'title'   => 'Reminder: Deadline Tugas Mendekat!',
                    'message' => "Tugas \"{$tugas->judul}\" akan berakhir pada {$tugas->deadline->format('d M Y H:i')}. Segera kumpulkan!",
                    'data'    => ['tugas_id' => $tugas->id, 'deadline' => $tugas->deadline->toIso8601String()],
                    'is_read' => mt_rand(0, 1) === 1,
                    'read_at' => mt_rand(0, 1) === 1 ? now()->subHours(mt_rand(1, 24)) : null,
                    'link'    => '/guru/tugas/' . $tugas->id,
                ];
            }
        }

        // ─── Nilai (grade) notifications ────────────────────────────
        $gradedSubmissions = \App\Models\Submission::whereNotNull('nilai')
            ->whereNotNull('feedback')
            ->take(30)
            ->get();

        foreach ($gradedSubmissions as $submission) {
            $notifications[] = [
                'user_id' => $submission->siswa_id,
                'type'    => 'nilai',
                'title'   => 'Tugas Dinilai: ' . $submission->tugas->judul,
                'message' => "Tugas \"{$submission->tugas->judul}\" telah dinilai. Nilai: {$submission->nilai}/{$submission->tugas->nilai_maks}. Feedback: {$submission->feedback}",
                'data'    => ['submission_id' => $submission->id, 'nilai' => $submission->nilai],
                'is_read' => mt_rand(0, 1) === 1,
                'read_at' => mt_rand(0, 1) === 1 ? now()->subHours(mt_rand(1, 72)) : null,
                'link'    => '/siswa/tugas/' . $submission->tugas_id,
            ];
        }

        // ─── Announcement notifications (for all) ───────────────────
        $announcementMessages = [
            ['title' => 'Libur Nasional', 'message' => 'Diberitahukan bahwa tanggal 17 Agustus 2025 libur nasional dalam rangka HUT RI ke-80.'],
            ['title' => 'Jadwal UTS Semester Ganjil', 'message' => 'Jadwal UTS semester ganjil 2024/2025 telah dirilis. Silakan cek di menu akademik.'],
            ['title' => 'Pendaftaran Ekstrakurikuler', 'message' => 'Pendaftaran ekskul dibuka hingga 30 September 2024. Hubungi Wakil Kesiswaan.'],
        ];

        foreach ($users as $user) {
            foreach ($announcementMessages as $ann) {
                $notifications[] = [
                    'user_id' => $user->id,
                    'type'    => 'announcement',
                    'title'   => $ann['title'],
                    'message' => $ann['message'],
                    'data'    => null,
                    'is_read' => mt_rand(0, 1) === 1,
                    'read_at' => mt_rand(0, 1) === 1 ? now()->subDays(mt_rand(1, 7)) : null,
                    'link'    => '/pengumuman',
                ];
            }
        }

        // ─── Materi Baru notifications (for siswa) ──────────────────
        $recentMateris = \App\Models\Materi::where('is_published', true)
            ->latest()
            ->take(10)
            ->get();

        foreach ($recentMateris as $materi) {
            $kelasSiswa = $materi->kelas->siswas ?? collect();
            foreach ($kelasSiswa as $siswa) {
                $notifications[] = [
                    'user_id' => $siswa->id,
                    'type'    => 'materi_baru',
                    'title'   => 'Materi Baru: ' . $materi->judul,
                    'message' => "Materi baru \"{$materi->judul}\" telah diunggah pada mapel {$materi->mapel->nama}.",
                    'data'    => ['materi_id' => $materi->id, 'kelas_id' => $materi->kelas_id, 'mapel_id' => $materi->mapel_id],
                    'is_read' => mt_rand(0, 1) === 1,
                    'read_at' => mt_rand(0, 1) === 1 ? now()->subHours(mt_rand(1, 24)) : null,
                    'link'    => '/guru/materi/' . $materi->id,
                ];
            }
        }

        // Insert in batches to avoid memory issues
        $batchSize = 200;
        $batches = array_chunk($notifications, $batchSize);

        foreach ($batches as $batch) {
            // Add timestamps
            foreach ($batch as &$notif) {
                $notif['created_at'] = now()->subHours(mt_rand(1, 168));
                $notif['updated_at'] = now();
            }
            Notification::insert($batch);
        }

        $this->command->info('✓ ' . count($notifications) . ' notifications berhasil dibuat');
    }
}
