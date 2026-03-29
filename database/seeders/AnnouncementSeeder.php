<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates announcements (class-specific and global).
     */
    public function run(): void
    {
        $admin  = User::where('email', 'admin@smktunas.sch.id')->first();
        $gurus  = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))
            ->orderBy('id')->get();

        $kelasPPLGX1 = Kelas::where('nama', 'PPLG X-1')->first();
        $kelasPPLGX2 = Kelas::where('nama', 'PPLG X-2')->first();
        $kelasPPLGY1 = Kelas::where('nama', 'PPLG XI-1')->first();
        $kelasTJKTX1 = Kelas::where('nama', 'TJKT X-1')->first();

        $announcements = [
            // ─── Global announcements (class_id = null) ─────────────
            [
                'class_id'     => null,
                'user_id'      => $admin->id,
                'judul'        => 'Selamat Datang di LMS SMK Tunas Harapan!',
                'konten'       => "<p>Assalamualaikum Wr. Wb.</p><p>Diberitahukan kepada seluruh warga sekolah bahwa <strong>LMS (Learning Management System) SMK Telekomunikasi Tunas Harapan</strong> telah resmi digunakan untuk mendukung kegiatan belajar mengajar.</p><p>Melalui platform ini, guru dapat mengunggah materi, memberikan tugas, dan melakukan penilaian secara digital. Siswa dapat mengakses materi, mengerjakan tugas, dan melihat nilai secara online.</p><p>Silakan login menggunakan akun yang telah diberikan. Jika ada kendala, hubungi tim IT.</p><p>Wassalamualaikum Wr. Wb.</p>",
                'priority'     => 4,
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
            [
                'class_id'     => null,
                'user_id'      => $admin->id,
                'judul'        => 'Jadwal UTS Semester Ganjil 2024/2025',
                'konten'       => "<p>Diberitahukan bahwa <strong>Ujian Tengah Semester (UTS)</strong> Semester Ganjil 2024/2025 akan dilaksanakan pada:</p><ul><li><strong>Tanggal:</strong> 30 September - 7 Oktober 2024</li><li><strong>Waktu:</strong> Sesuai jadwal pelajaran</li></ul><p>Materi yang diujikan meliputi seluruh materi pertemuan 1-8. Siswa diwajibkan mempersiapkan diri dengan baik.</p><p>Libur: <strong>29 September 2024</strong> (hari persiapan).</p>",
                'priority'     => 3,
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'class_id'     => null,
                'user_id'      => $admin->id,
                'judul'        => 'Peringatan Hari Kesaktian Pancasila',
                'konten'       => "<p>Sehubungan dengan <strong>Hari Kesaktian Pancasila</strong> pada tanggal 1 Oktober 2024, maka seluruh kegiatan belajar mengajar diliburkan.</p><p>Upacara peringatan akan dilaksanakan di lapangan sekolah pukul 07.00 WIB. Seluruh siswa wajib hadir menggunakan seragam lengkap.</p>",
                'priority'     => 2,
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'class_id'     => null,
                'user_id'      => $admin->id,
                'judul'        => 'Pendaftaran Lomba Hackathon Nasional',
                'konten'       => "<p>SMK Tunas Harapan membuka pendaftaran untuk mengikuti <strong>Lomba Hackathon Nasional 2024</strong> tingkat SMK.</p><p>Syarat:</p><ul><li>Siswa kelas X-XII jurusan PPLG/RPL</li><li>Tim terdiri dari 3 orang</li><li>Membuat aplikasi dengan tema "Smart City"</li></ul><p>Pendaftaran ditutup tanggal 15 Oktober 2024. Hubungi Pak Budi Santoso untuk informasi lebih lanjut.</p>",
                'priority'     => 2,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'class_id'     => null,
                'user_id'      => $admin->id,
                'judul'        => 'Maintenance Sistem LMS',
                'konten'       => "<p>Diberitahukan bahwa sistem LMS akan mengalami <strong>maintenance</strong> pada:</p><ul><li><strong>Tanggal:</strong> 20 Oktober 2024</li><li><strong>Waktu:</strong> 22.00 - 02.00 WIB</li></ul><p>Selama maintenance, LMS tidak dapat diakses. Mohon untuk menyelesaikan tugas sebelum waktu maintenance.</p>",
                'priority'     => 3,
                'is_published' => true,
                'published_at' => now()->addDays(3),
            ],

            // ─── Class-specific announcements ────────────────────────
            [
                'class_id'     => $kelasPPLGX1->id,
                'user_id'      => $gurus[0]->id,
                'judul'        => 'Kumpulan Materi Pemrograman Dasar',
                'konten'       => "<p>Assalamualaikum siswa PPLG X-1,</p><p>Semua materi Pemrograman Dasar telah diunggah di LMS. Silakan pelajari dan download materi yang diperlukan.</p><p>Untuk pertanyaan, silakan diskusi di kolom komentar setiap materi.</p>",
                'priority'     => 2,
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'class_id'     => $kelasPPLGX1->id,
                'user_id'      => $gurus[0]->id,
                'judul'        => 'Remidial Quiz Algoritma',
                'konten'       => "<p>Bagi siswa yang nilainya di bawah KKM pada Quiz Algoritma, diwajibkan mengikuti remedial.</p><p>Jadwal remedial: Jumat, 25 Oktober 2024, pukul 14.00 di Lab Komputer 1.</p><p>Bawa alat tulis dan laptop.</p>",
                'priority'     => 3,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'class_id'     => $kelasPPLGY1->id,
                'user_id'      => $gurus[3]->id,
                'judul'        => 'Persiapan UTS PBO',
                'konten'       => "<p>Siswa PPLG XI-1,</p><p>Materi UTS PBO meliputi:</p><ol><li>Class dan Object</li><li>Inheritance</li><li>Polymorphism</li><li>Encapsulation</li></ol><p>Latihan soal telah diunggah di materi. Silakan dikerjakan sebagai latihan.</p>",
                'priority'     => 3,
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'class_id'     => $kelasPPLGX2->id,
                'user_id'      => $gurus[0]->id,
                'judul'        => 'Pengumpulan Tugas Array',
                'konten'       => "<p>Pengingat: deadline tugas Array dan Sorting tinggal 3 hari lagi. Pastikan sudah submit sebelum deadline.</p>",
                'priority'     => 2,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'class_id'     => $kelasTJKTX1->id,
                'user_id'      => $gurus[7]->id,
                'judul'        => 'Jadwal Praktikum Fisika',
                'konten'       => "<p>Praktikum Rangkaian Listrik akan dilaksanakan pada:</p><ul><li>Hari: Rabu, 23 Oktober 2024</li><li>Waktu: 10.00 - 12.00</li><li>Tempat: Lab Fisika</li></ul><p>Wajib membawa: alat tulis, laporan format (sudah diunggah), dan wearpack.</p>",
                'priority'     => 2,
                'is_published' => true,
                'published_at' => now()->addDays(5),
            ],

            // ─── Draft announcement ──────────────────────────────────
            [
                'class_id'     => null,
                'user_id'      => $admin->id,
                'judul'        => 'Jadwal UAS Semester Ganjil 2024/2025 (Draft)',
                'konten'       => "<p>Jadwal UAS masih disusun...</p>",
                'priority'     => 2,
                'is_published' => false,
                'published_at' => null,
            ],
        ];

        foreach ($announcements as $ann) {
            Announcement::create([
                'class_id'     => $ann['class_id'],
                'user_id'      => $ann['user_id'],
                'judul'        => $ann['judul'],
                'konten'       => $ann['konten'],
                'priority'     => $ann['priority'],
                'is_published' => $ann['is_published'],
                'published_at' => $ann['published_at'],
            ]);
        }

        $this->command->info('✓ ' . count($announcements) . ' pengumuman berhasil dibuat');
    }
}
