<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates comments on materi and tugas (root + replies).
     */
    public function run(): void
    {
        $gurus = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))->get();
        $siswas = User::whereHas('roles', fn($q) => $q->where('name', 'siswa'))->take(15)->get();

        $materis = Materi::where('is_published', true)->take(10)->get();
        $tugases = Tugas::where('is_published', true)->take(5)->get();

        $comments = [];

        // ─── Comments on Materi ─────────────────────────────────────
        $materiQuestions = [
            'Pak/Bu, untuk soal nomor 3 apakah boleh menggunakan nested if?',
            'Mohon penjelasan lebih detail untuk materi ini.',
            'Terima kasih materinya sangat membantu!',
            'Apakah ada contoh kasus nyata penerapan materi ini?',
            'Video-nya sangat jelas, terima kasih Bu.',
            'Boleh tanya, kalau outputnya diminta dalam format tabel bagaimana?',
            'Saya sudah coba ikuti tutorial tapi ada error di baris 15.',
            'Materinya lengkap sekali, sangat bermanfaat.',
            'Pak, file PDF-nya tidak bisa diunduh.',
            'Wah materinya keren banget, paham sekarang!',
            'Untuk tugas ini boleh dikerjakan berkelompok tidak?',
            'Kapan deadline pengumpulan?',
            'Mohon dijelaskan lagi bagian loop bersarang.',
            'Kalau pakai switch case boleh tidak Pak?',
            'Materi ini mirip dengan yang dijelaskan di channel Web Programming UNPAS.',
        ];

        $materiReplies = [
            'Boleh, asalkan logikanya benar.',
            'Silakan, contoh kasus bisa dilihat di slide berikutnya.',
            'Iya sama-sama, semoga bermanfaat!',
            'Coba cek lagi syntaxnya, kemungkinan ada kurung yang kurang.',
            'File sudah diperbaiki, silakan unduh ulang.',
            'Tidak, tugas ini harus dikerjakan individu.',
            'Deadline sudah ada di pengumuman kelas.',
            'Boleh, tapi sebaiknya gunakan if-else untuk latihan logika.',
            'Terima kasih atas masukannya.',
            'Silakan pelajari juga referensi tambahan di link berikut.',
        ];

        $rootMateriComments = 0;
        foreach ($materis as $materi) {
            // 2-4 comments per materi
            $numComments = mt_rand(2, 4);
            for ($i = 0; $i < $numComments; $i++) {
                $user = mt_rand(0, 1) === 0
                    ? $siswas[mt_rand(0, $siswas->count() - 1)]
                    : $gurus[mt_rand(0, $gurus->count() - 1)];

                $body = $materiQuestions[mt_rand(0, count($materiQuestions) - 1)];
                $comment = Comment::create([
                    'commentable_id'   => $materi->id,
                    'commentable_type' => Materi::class,
                    'user_id'          => $user->id,
                    'parent_id'        => null,
                    'body'             => $body,
                    'is_edited'        => false,
                ]);
                $rootMateriComments++;

                // 40% chance of a reply
                if (mt_rand(1, 100) <= 40) {
                    $replyUser = $user->hasRole('siswa')
                        ? $gurus[mt_rand(0, $gurus->count() - 1)]
                        : $siswas[mt_rand(0, $siswas->count() - 1)];

                    Comment::create([
                        'commentable_id'   => $materi->id,
                        'commentable_type' => Materi::class,
                        'user_id'          => $replyUser->id,
                        'parent_id'        => $comment->id,
                        'body'             => $materiReplies[mt_rand(0, count($materiReplies) - 1)],
                        'is_edited'        => false,
                    ]);
                }
            }
        }

        // ─── Comments on Tugas ──────────────────────────────────────
        $tugasQuestions = [
            'Pak/Bu, apakah boleh submit dalam format .rar?',
            'Saya sudah submit, mohon dicek ya.',
            'Deadline bisa diperpanjang tidak?',
            'File attachment-nya kosong Pak.',
            'Untuk soal nomor 5, apakah output-nya harus persis sama?',
            'Apakah boleh menggunakan library tambahan?',
            'Mohon maaf, saya baru lihat tugas ini. Boleh kumpul terlambat?',
            'Saya ada kendala teknis saat upload file.',
            'Terima kasih, tugasnya sudah saya kumpulkan.',
            'Instruksinya sangat jelas, terima kasih Bu!',
        ];

        $tugasReplies = [
            'Format .zip saja ya, tidak .rar.',
            'Sudah dicatat, akan segera dinilai.',
            'Deadline tidak bisa diperpanjang, mohon tepat waktu.',
            'File sudah diperbaiki, silakan unduh ulang.',
            'Output harus sesuai dengan yang diminta di soal.',
            'Boleh, asalkan open source.',
            'Karena allow_late aktif, silakan kumpulkan secepatnya.',
            'Coba gunakan browser lain untuk upload.',
        ];

        $rootTugasComments = 0;
        foreach ($tugases as $tugas) {
            // 2-3 comments per tugas
            $numComments = mt_rand(2, 3);
            for ($i = 0; $i < $numComments; $i++) {
                $user = $siswas[mt_rand(0, $siswas->count() - 1)];
                $body = $tugasQuestions[mt_rand(0, count($tugasQuestions) - 1)];

                $comment = Comment::create([
                    'commentable_id'   => $tugas->id,
                    'commentable_type' => Tugas::class,
                    'user_id'          => $user->id,
                    'parent_id'        => null,
                    'body'             => $body,
                    'is_edited'        => false,
                ]);
                $rootTugasComments++;

                // 60% chance guru replies to tugas comments
                if (mt_rand(1, 100) <= 60) {
                    $replyUser = $tugas->guru;
                    Comment::create([
                        'commentable_id'   => $tugas->id,
                        'commentable_type' => Tugas::class,
                        'user_id'          => $replyUser->id,
                        'parent_id'        => $comment->id,
                        'body'             => $tugasReplies[mt_rand(0, count($tugasReplies) - 1)],
                        'is_edited'        => false,
                    ]);
                }
            }
        }

        $totalComments = $rootMateriComments + $rootTugasComments;
        $this->command->info("✓ ~{$totalComments} root comments + replies berhasil dibuat (materi & tugas)");
    }
}
