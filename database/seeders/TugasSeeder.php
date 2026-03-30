<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates 25 tugas across various kelas and mapel.
     */
    public function run(): void
    {
        $mapels = Mapel::all()->keyBy('kode');
        $gurus  = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))
            ->orderBy('id')->get();

        $kelasPPLGX1 = Kelas::where('nama', 'PPLG X-1')->first();
        $kelasPPLGX2 = Kelas::where('nama', 'PPLG X-2')->first();
        $kelasPPLGY1 = Kelas::where('nama', 'PPLG XI-1')->first();
        $kelasPPLGY2 = Kelas::where('nama', 'PPLG XI-2')->first();
        $kelasPPLGZ1 = Kelas::where('nama', 'PPLG XII-1')->first();
        $kelasPPLGZ2 = Kelas::where('nama', 'PPLG XII-2')->first();
        $kelasTJKTX1 = Kelas::where('nama', 'TJKT X-1')->first();
        $kelasTJKTX2 = Kelas::where('nama', 'TJKT X-2')->first();

        $tugasList = [
            // ─── PPLG X-1: Pemrograman Dasar ────────────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Tugas Algoritma Pseudocode',
                'deskripsi' => 'Buat pseudocode untuk 5 soal berikut.',
                'instruksi' => "1. Tulis pseudocode untuk menghitung luas segitiga\n2. Buat pseudocode untuk menentukan bilangan ganjil/genap\n3. Buat pseudocode untuk menghitung rata-rata\n4. Tulis pseudocode untuk mencari nilai maksimum\n5. Buat pseudocode untuk konversi suhu Celcius ke Fahrenheit",
                'file_attachment' => 'tugas/soal-pseudocode.pdf',
                'deadline' => now()->subDays(10), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Quiz Percabangan If-Else',
                'deskripsi' => 'Quiz online tentang materi percabangan.',
                'instruksi' => 'Kerjakan 10 soal pilihan ganda dalam waktu 30 menit.',
                'file_attachment' => null,
                'deadline' => now()->addDays(5), 'tipe' => 'quiz', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Proyek: Program Kasir Sederhana',
                'deskripsi' => 'Buat program kasir sederhana menggunakan bahasa pemrograman dasar.',
                'instruksi' => "Buat program kasir sederhana yang dapat:\n- Input nama barang dan harga\n- Menghitung total belanja\n- Memberikan diskon jika total > 500000\n- Menampilkan struk belanja",
                'file_attachment' => null,
                'deadline' => now()->addDays(14), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => true, 'is_published' => true,
            ],

            // ─── PPLG X-1: Pemrograman Web ──────────────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PW-01'], 'guru' => $gurus[0],
                'judul' => 'Tugas Membuat Halaman HTML Profil',
                'deskripsi' => 'Buat halaman profil pribadi menggunakan HTML5.',
                'instruksi' => "Buat halaman HTML5 dengan struktur:\n- Header dengan nama\n- Section biodata\n- Section pendidikan\n- Footer",
                'file_attachment' => 'tugas/template-profil.html',
                'deadline' => now()->subDays(5), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PW-01'], 'guru' => $gurus[0],
                'judul' => 'Proyek Landing Page Sekolah',
                'deskripsi' => 'Buat landing page responsif untuk SMK Tunas Harapan.',
                'instruksi' => "Requirements:\n- Responsif (mobile & desktop)\n- Navigasi dengan smooth scroll\n- Hero section dengan gambar\n- Section program keahlian\n- Form kontak\n- Gunakan CSS Flexbox/Grid",
                'file_attachment' => null,
                'deadline' => now()->addDays(21), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => true, 'is_published' => true,
            ],

            // ─── PPLG X-1: Bahasa Indonesia ─────────────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['BIN'], 'guru' => $gurus[1],
                'judul' => 'Tugas Menulis Teks Eksposisi',
                'deskripsi' => 'Tulis teks eksposisi minimal 500 kata tentang pentingnya teknologi dalam pendidikan.',
                'instruksi' => "Tulis teks eksposisi dengan struktur:\n1. Tesis\n2. Argumentasi (minimal 3 argumen)\n3. Penegasan ulang\n\nMinimal 500 kata. Ketik di kertas folio bergaris.",
                'file_attachment' => null,
                'deadline' => now()->subDays(7), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── PPLG X-1: PAI ──────────────────────────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PAI'], 'guru' => $gurus[5],
                'judul' => 'Tugas Membuat Ringkasan Hadits',
                'deskripsi' => 'Buat ringkasan 10 hadits tentang keutamaan ilmu.',
                'instruksi' => "Pilih 10 hadits tentang keutamaan menuntut ilmu.\nUntuk setiap hadits, tulis:\n- Teks Arab\n- Terjemahan Indonesia\n- Hikmah/pelajaran",
                'file_attachment' => 'tugas/buku-hadits.pdf',
                'deadline' => now()->subDays(3), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── PPLG X-2: Pemrograman Dasar ────────────────────────
            [
                'kelas' => $kelasPPLGX2, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Tugas Array dan Sorting',
                'deskripsi' => 'Implementasi algoritma sorting pada array.',
                'instruksi' => "Buat program yang:\n1. Menerima input 10 bilangan\n2. Menyimpannya dalam array\n3. Mengurutkan dengan bubble sort\n4. Menampilkan hasil sebelum dan sesudah sorting",
                'file_attachment' => null,
                'deadline' => now()->addDays(7), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── PPLG XI-1: PBO ─────────────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Tugas Membuat Class Diagram',
                'deskripsi' => 'Buat class diagram untuk sistem perpustakaan.',
                'instruksi' => "Buat class diagram yang memodelkan:\n- Buku (id, judul, penulis, tahun)\n- Anggota (id, nama, alamat)\n- Peminjaman (id, tanggal_pinjam, tanggal_kembali)\n\nGunakan tools: draw.io atau Lucidchart",
                'file_attachment' => null,
                'deadline' => now()->subDays(12), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Ujian Tengah Semester PBO',
                'deskripsi' => 'UTS Pemrograman Berorientasi Objek.',
                'instruksi' => "Materi yang diujikan:\n- Class dan Object\n- Inheritance\n- Polymorphism\n- Encapsulation\n\nWaktu: 90 menit",
                'file_attachment' => null,
                'deadline' => now()->subDays(20), 'tipe' => 'ujian', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── PPLG XI-1: Basis Data ───────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['BD-01'], 'guru' => $gurus[3],
                'judul' => 'Tugas Merancang Database Toko Online',
                'deskripsi' => 'Buat ERD dan implementasi SQL untuk database toko online.',
                'instruksi' => "1. Buat ERD minimal 5 tabel\n2. Implementasikan dalam MySQL\n3. Insert minimal 10 data per tabel\n4. Buat 5 query SELECT dengan JOIN\n5. Buat 2 view dan 1 stored procedure",
                'file_attachment' => 'tugas/contoh-erd.pdf',
                'deadline' => now()->addDays(10), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => true, 'is_published' => true,
            ],

            // ─── PPLG XI-1: Desain UI/UX ─────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['UIX-01'], 'guru' => $gurus[6],
                'judul' => 'Tugas Desain Wireframe Aplikasi',
                'deskripsi' => 'Buat wireframe untuk aplikasi e-learning mobile.',
                'instruksi' => "Buat wireframe low-fidelity untuk:\n- Halaman Login/Register\n- Home/Dashboard\n- Detail Materi\n- Quiz/Exam\n- Profil Pengguna\n\nTools: Figma atau kertas + pulpen",
                'file_attachment' => null,
                'deadline' => now()->addDays(7), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── PPLG XI-2: PBO ─────────────────────────────────────
            [
                'kelas' => $kelasPPLGY2, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Tugas Implementasi Interface',
                'deskripsi' => 'Implementasikan interface pada project OOP.',
                'instruksi' => "Buat project PHP dengan:\n- Minimal 3 interface\n- Implementasi interface pada class\n- Demonstrasi polymorphism",
                'file_attachment' => null,
                'deadline' => now()->addDays(14), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => true, 'is_published' => true,
            ],

            // ─── PPLG XII-1: Pemrograman Mobile ──────────────────────
            [
                'kelas' => $kelasPPLGZ1, 'mapel' => $mapels['PM-01'], 'guru' => $gurus[4],
                'judul' => 'Tugas Aplikasi CRUD Android',
                'deskripsi' => 'Buat aplikasi Android dengan fitur CRUD.',
                'instruksi' => "Buat aplikasi Android yang memiliki fitur:\n- Create (Tambah data)\n- Read (Tampilkan data)\n- Update (Edit data)\n- Delete (Hapus data)\n\nData disimpan di SQLite atau Room Database.",
                'file_attachment' => null,
                'deadline' => now()->subDays(15), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],
            [
                'kelas' => $kelasPPLGZ1, 'mapel' => $mapels['PM-01'], 'guru' => $gurus[4],
                'judul' => 'Ujian Akhir Semester - Mobile Dev',
                'deskripsi' => 'UAS Pemrograman Mobile semester ganjil.',
                'instruksi' => "Waktu pengerjaan: 120 menit. Buka buku diperbolehkan.",
                'file_attachment' => null,
                'deadline' => now()->addDays(30), 'tipe' => 'ujian', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── PPLG XII-1: Game Dev ───────────────────────────────
            [
                'kelas' => $kelasPPLGZ1, 'mapel' => $mapels['GD-01'], 'guru' => $gurus[4],
                'judul' => 'Proyek Game 2D Platformer',
                'deskripsi' => 'Buat game 2D platformer sederhana menggunakan Unity.',
                'instruksi' => "Buat game platformer dengan fitur:\n- Karakter yang bisa berlompat\n- Obstacle/enemy\n- Coin/kolektibel\n- Score system\n- Game over & restart",
                'file_attachment' => null,
                'deadline' => now()->addDays(28), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => true, 'is_published' => true,
            ],

            // ─── PPLG XII-2: DevOps ──────────────────────────────────
            [
                'kelas' => $kelasPPLGZ2, 'mapel' => $mapels['DVC-01'], 'guru' => $gurus[4],
                'judul' => 'Tugas Deploy Aplikasi dengan Docker',
                'deskripsi' => 'Deploy aplikasi web menggunakan Docker dan Docker Compose.',
                'instruksi' => "1. Buat Dockerfile untuk aplikasi Laravel\n2. Buat docker-compose.yml dengan:\n   - App container\n   - MySQL container\n   - Nginx container\n3. Dokumentasikan langkah-langkah deployment",
                'file_attachment' => null,
                'deadline' => now()->addDays(14), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── TJKT X-1: Fisika ────────────────────────────────────
            [
                'kelas' => $kelasTJKTX1, 'mapel' => $mapels['FIS'], 'guru' => $gurus[7],
                'judul' => 'Tugas Laporan Praktikum Rangkaian Listrik',
                'deskripsi' => 'Buat laporan praktikum rangkaian seri dan paralel.',
                'instruksi' => "Buat laporan yang berisi:\n1. Tujuan praktikum\n2. Alat dan bahan\n3. Langkah kerja\n4. Tabel pengukuran\n5. Analisis data\n6. Kesimpulan",
                'file_attachment' => 'tugas/format-laporan.docx',
                'deadline' => now()->subDays(8), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── TJKT X-1: Bahasa Inggris ───────────────────────────
            [
                'kelas' => $kelasTJKTX1, 'mapel' => $mapels['BIG'], 'guru' => $gurus[2],
                'judul' => 'Tugas Menulis Email Formal dalam Bahasa Inggris',
                'deskripsi' => 'Tulis email formal untuk berbagai situasi profesional.',
                'instruksi' => "Tulis 3 email formal dalam bahasa Inggris:\n1. Email lamaran magang\n2. Email pertanyaan ke dosen\n3. Email konfirmasi kehadiran seminar",
                'file_attachment' => null,
                'deadline' => now()->addDays(5), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => true,
            ],

            // ─── TJKT X-2: Kimia ────────────────────────────────────
            [
                'kelas' => $kelasTJKTX2, 'mapel' => $mapels['KIM'], 'guru' => $gurus[7],
                'judul' => 'Tugas Tabel Periodik Interaktif',
                'deskripsi' => 'Buat tabel periodik interaktif dalam bentuk web.',
                'instruksi' => "Buat halaman web sederhana yang menampilkan tabel periodik unsur. Setiap unsur yang diklik menampilkan informasi lengkap (nomor atom, massa, konfigurasi elektron).",
                'file_attachment' => null,
                'deadline' => now()->addDays(12), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => true, 'is_published' => true,
            ],

            // ─── Draft tugas ────────────────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Tugas Design Pattern (Draft)',
                'deskripsi' => 'Tugas tentang implementasi design pattern.',
                'instruksi' => 'TBA - masih disusun.',
                'file_attachment' => null,
                'deadline' => now()->addDays(30), 'tipe' => 'tugas', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => false,
            ],
            [
                'kelas' => $kelasPPLGZ1, 'mapel' => $mapels['DVC-01'], 'guru' => $gurus[4],
                'judul' => 'Tugas CI/CD Pipeline (Draft)',
                'deskripsi' => 'Buat CI/CD pipeline menggunakan GitHub Actions.',
                'instruksi' => 'TBA.',
                'file_attachment' => null,
                'deadline' => now()->addDays(45), 'tipe' => 'proyek', 'nilai_maks' => 100,
                'allow_late' => false, 'is_published' => false,
            ],
        ];

        foreach ($tugasList as $item) {
            Tugas::create([
                'class_id'        => $item['kelas']->id,
                'mapel_id'        => $item['mapel']->id,
                'guru_id'         => $item['guru']->id,
                'judul'           => $item['judul'],
                'deskripsi'       => $item['deskripsi'],
                'instruksi'       => $item['instruksi'],
                'file_attachment' => $item['file_attachment'],
                'deadline'        => $item['deadline'],
                'tipe'            => $item['tipe'],
                'nilai_maks'      => $item['nilai_maks'],
                'allow_late'      => $item['allow_late'],
                'is_published'    => $item['is_published'],
            ]);
        }

        $this->command->info('✓ ' . count($tugasList) . ' tugas berhasil dibuat');
    }
}
