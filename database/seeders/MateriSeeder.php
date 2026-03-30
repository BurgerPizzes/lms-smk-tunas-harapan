<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates 35 materi across various kelas and mapel.
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

        $materiList = [
            // ─── PPLG X-1: Pemrograman Dasar ────────────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Pengenalan Algoritma dan Pseudocode',
                'deskripsi' => 'Materi dasar mengenai konsep algoritma dan cara menulis pseudocode.',
                'konten' => "<h2>Pengenalan Algoritma</h2><p>Algoritma adalah langkah-langkah sistematis untuk menyelesaikan suatu masalah. Dalam pemrograman, algoritma menjadi fondasi utama sebelum menulis kode.</p><h3>Pseudocode</h3><p>Pseudocode adalah cara penulisan algoritma menggunakan bahasa yang mendekati bahasa pemrograman namun tidak terikat pada sintaks tertentu.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Variabel, Tipe Data, dan Operator',
                'deskripsi' => 'Pembahasan tentang variabel, tipe data dasar, dan operator dalam pemrograman.',
                'konten' => "<h2>Variabel dan Tipe Data</h2><p>Variabel adalah wadah untuk menyimpan nilai. Setiap variabel memiliki tipe data yang menentukan jenis nilai yang dapat disimpan.</p><ul><li><strong>Integer</strong>: Bilangan bulat</li><li><strong>Float</strong>: Bilangan desimal</li><li><strong>String</strong>: Teks</li><li><strong>Boolean</strong>: Benar/Salah</li></ul>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 2,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Percabangan (If-Else)',
                'deskripsi' => 'Struktur kontrol percabangan menggunakan if, else if, dan else.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=ZPmJwHSiWwg',
                'pertemuan_ke' => 3, 'is_published' => true, 'urutan' => 3,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Perulangan (Looping)',
                'deskripsi' => 'Video tutorial perulangan for, while, dan do-while.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=r4UOlvFMO8o',
                'pertemuan_ke' => 4, 'is_published' => true, 'urutan' => 4,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Modul Praktik Algoritma Dasar',
                'deskripsi' => 'Modul praktikum lengkap dengan contoh soal dan jawaban.',
                'konten' => null,
                'tipe' => 'file', 'file_path' => 'materi/modul-algoritma-dasar.pdf',
                'video_url' => null, 'pertemuan_ke' => 5, 'is_published' => true, 'urutan' => 5,
            ],

            // ─── PPLG X-1: Pemrograman Web ──────────────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PW-01'], 'guru' => $gurus[0],
                'judul' => 'Pengenalan HTML5',
                'deskripsi' => 'Dasar-dasar HTML5: tag, elemen, atribut, dan struktur dokumen.',
                'konten' => "<h2>HTML5 - HyperText Markup Language</h2><p>HTML adalah bahasa markup standar untuk membuat halaman web. HTML5 adalah versi terbaru yang mendukung multimedia dan fitur interaktif.</p><h3>Struktur Dasar HTML5</h3><pre><code>&lt;!DOCTYPE html&gt;\n&lt;html lang='id'&gt;\n&lt;head&gt;\n  &lt;meta charset='UTF-8'&gt;\n  &lt;title&gt;Halaman Pertama&lt;/title&gt;\n&lt;/head&gt;\n&lt;body&gt;\n  &lt;h1&gt;Hello World!&lt;/h1&gt;\n&lt;/body&gt;\n&lt;/html&gt;</code></pre>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PW-01'], 'guru' => $gurus[0],
                'judul' => 'CSS3 - Styling dan Layout',
                'deskripsi' => 'Video tutorial dasar CSS3 untuk styling halaman web.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=wRNinF7YQqQ',
                'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 2,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PW-01'], 'guru' => $gurus[0],
                'judul' => 'JavaScript Dasar',
                'deskripsi' => 'Referensi lengkap JavaScript dasar untuk pemula.',
                'konten' => null,
                'tipe' => 'link', 'file_path' => null,
                'video_url' => 'https://developer.mozilla.org/id/docs/Web/JavaScript/Guide',
                'pertemuan_ke' => 3, 'is_published' => true, 'urutan' => 3,
            ],
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PW-01'], 'guru' => $gurus[0],
                'judul' => 'Proyek: Landing Page Sederhana',
                'deskripsi' => 'Template dan panduan membuat landing page responsif.',
                'konten' => "<h2>Proyek Akhir Modul Web</h2><p>Buat landing page responsif untuk sekolah menggunakan HTML, CSS, dan JavaScript dasar.</p><h3>Requirement:</h3><ul><li>Header dengan navigasi</li><li>Hero section</li><li>Section tentang sekolah</li><li>Galeri foto</li><li>Footer dengan kontak</li></ul>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 6, 'is_published' => true, 'urutan' => 4,
            ],

            // ─── PPLG X-1: Bahasa Indonesia ─────────────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['BIN'], 'guru' => $gurus[1],
                'judul' => 'Teks Eksposisi',
                'deskripsi' => 'Mengenal struktur dan cara menulis teks eksposisi.',
                'konten' => "<h2>Teks Eksposisi</h2><p>Teks eksposisi adalah paragraf atau karangan yang bertujuan memberikan informasi atau pengetahuan kepada pembaca.</p><h3>Struktur Teks Eksposisi:</h3><ol><li><strong>Tesis</strong>: Ide pokok/pembuka</li><li><strong>Argumentasi</strong>: Deretan argumen yang mendukung tesis</li><li><strong>Penegasan Ulang</strong>: Penguatan ide pokok</li></ol>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── PPLG X-1: Pendidikan Agama Islam ───────────────────
            [
                'kelas' => $kelasPPLGX1, 'mapel' => $mapels['PAI'], 'guru' => $gurus[5],
                'judul' => 'Iman kepada Kitab-Kitab Allah SWT',
                'deskripsi' => 'Materi tentang iman kepada kitab-kitab Allah SWT dan hikmahnya.',
                'konten' => "<h2>Iman kepada Kitab-Kitab Allah SWT</h2><p>Beriman kepada kitab-kitab Allah SWT merupakan rukun iman yang ke-3. Kitab-kitab Allah yang wajib diimani ada 4: Taurat, Zabur, Injil, dan Al-Quran.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── PPLG X-2: Pemrograman Dasar ────────────────────────
            [
                'kelas' => $kelasPPLGX2, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Array dan Fungsi',
                'deskripsi' => 'Materi tentang array satu dimensi, multidimensi, dan fungsi dalam pemrograman.',
                'konten' => "<h2>Array</h2><p>Array adalah struktur data yang menyimpan kumpulan elemen dengan tipe data yang sama dalam satu variabel.</p><h3>Fungsi</h3><p>Fungsi adalah blok kode yang dapat dipanggil berulang kali untuk melakukan tugas tertentu.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 6, 'is_published' => true, 'urutan' => 6,
            ],
            [
                'kelas' => $kelasPPLGX2, 'mapel' => $mapels['PD-01'], 'guru' => $gurus[0],
                'judul' => 'Pengenalan Array - Video Tutorial',
                'deskripsi' => 'Video penjelasan lengkap tentang array dalam pemrograman.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=5S3hQ9qPEcQ',
                'pertemuan_ke' => 6, 'is_published' => true, 'urutan' => 7,
            ],

            // ─── PPLG XI-1: PBO ─────────────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Pengenalan OOP - Class dan Object',
                'deskripsi' => 'Konsep dasar pemrograman berorientasi objek: class, object, attribute, method.',
                'konten' => "<h2>Pemrograman Berorientasi Objek (OOP)</h2><p>OOP adalah paradigma pemrograman yang menggunakan objek sebagai komponen utama. Objek memiliki <em>attribute</em> (data) dan <em>method</em> (perilaku).</p><h3>4 Pilar OOP:</h3><ol><li><strong>Encapsulation</strong>: Membungkus data dan method</li><li><strong>Inheritance</strong>: Pewarisan sifat</li><li><strong>Polymorphism</strong>: Satu nama, banyak bentuk</li><li><strong>Abstraction</strong>: Menyembunyikan kompleksitas</li></ol>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Inheritance (Pewarisan)',
                'deskripsi' => 'Materi inheritance dan implementasinya dalam PHP.',
                'konten' => null,
                'tipe' => 'file', 'file_path' => 'materi/pbo-inheritance.pdf',
                'video_url' => null, 'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 2,
            ],
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Polymorphism dan Interface',
                'deskripsi' => 'Video tutorial polymorphism dan interface.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=BWnqZ7YV6Hc',
                'pertemuan_ke' => 3, 'is_published' => true, 'urutan' => 3,
            ],

            // ─── PPLG XI-1: Basis Data ───────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['BD-01'], 'guru' => $gurus[3],
                'judul' => 'Pengenalan Basis Data dan ERD',
                'deskripsi' => 'Dasar-dasar basis data relasional dan Entity Relationship Diagram.',
                'konten' => "<h2>Pengenalan Basis Data</h2><p>Basis data (database) adalah kumpulan data yang terorganisir dan disimpan secara sistematis di dalam komputer.</p><h3>ERD (Entity Relationship Diagram)</h3><p>ERD adalah model data konseptual yang digunakan untuk menggambarkan hubungan antar entitas dalam basis data.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['BD-01'], 'guru' => $gurus[3],
                'judul' => 'Tutorial MySQL - SELECT, INSERT, UPDATE, DELETE',
                'deskripsi' => 'Video tutorial operasi CRUD pada MySQL.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=7S_tz1z_5bA',
                'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 2,
            ],

            // ─── PPLG XI-1: Desain UI/UX ─────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['UIX-01'], 'guru' => $gurus[6],
                'judul' => 'Prinsip Desain UI yang Baik',
                'deskripsi' => 'Memahami prinsip-prinsip desain User Interface yang efektif.',
                'konten' => "<h2>Prinsip Desain UI</h2><ul><li><strong>Consistency</strong>: Konsistensi dalam desain</li><li><strong>Visibility</strong>: Elemen penting harus terlihat jelas</li><li><strong>Feedback</strong>: Sistem harus memberikan respons</li><li><strong>Simplicity</strong>: Tetap sederhana dan mudah dipahami</li></ul>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── PPLG XI-2: PBO ─────────────────────────────────────
            [
                'kelas' => $kelasPPLGY2, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Encapsulation dan Access Modifier',
                'deskripsi' => 'Materi tentang encapsulation, public, private, protected.',
                'konten' => "<h2>Encapsulation</h2><p>Encapsulation adalah teknik menyembunyikan detail implementasi dari luar class.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── PPLG XII-1: Pemrograman Mobile ──────────────────────
            [
                'kelas' => $kelasPPLGZ1, 'mapel' => $mapels['PM-01'], 'guru' => $gurus[4],
                'judul' => 'Pengenalan Android Development',
                'deskripsi' => 'Overview pengembangan aplikasi Android menggunakan Android Studio.',
                'konten' => "<h2>Android Development</h2><p>Android adalah sistem operasi mobile yang paling populer di dunia. Dalam materi ini, kita akan belajar membuat aplikasi Android menggunakan Android Studio dan Kotlin.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],
            [
                'kelas' => $kelasPPLGZ1, 'mapel' => $mapels['PM-01'], 'guru' => $gurus[4],
                'judul' => 'Layout Android: XML Basics',
                'deskripsi' => 'Video tutorial membuat layout XML di Android Studio.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=g5nKtMvK13E',
                'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 2,
            ],
            [
                'kelas' => $kelasPPLGZ1, 'mapel' => $mapels['GD-01'], 'guru' => $gurus[4],
                'judul' => 'Pengenalan Game Engine Unity',
                'deskripsi' => 'Materi dasar penggunaan Unity untuk game development.',
                'konten' => "<h2>Game Development dengan Unity</h2><p>Unity adalah game engine cross-platform yang populer untuk mengembangkan game 2D dan 3D.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── PPLG XII-2: DevOps ──────────────────────────────────
            [
                'kelas' => $kelasPPLGZ2, 'mapel' => $mapels['DVC-01'], 'guru' => $gurus[4],
                'judul' => 'Pengenalan DevOps dan CI/CD',
                'deskripsi' => 'Konsep dasar DevOps, Continuous Integration, dan Continuous Deployment.',
                'konten' => "<h2>DevOps</h2><p>DevOps adalah praktik yang menggabungkan pengembangan perangkat lunak (Dev) dan operasi IT (Ops) untuk memperpendek siklus pengembangan.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],
            [
                'kelas' => $kelasPPLGZ2, 'mapel' => $mapels['DVC-01'], 'guru' => $gurus[4],
                'judul' => 'Docker untuk Pemula',
                'deskripsi' => 'Referensi belajar Docker dari nol.',
                'konten' => null,
                'tipe' => 'link', 'file_path' => null,
                'video_url' => 'https://docs.docker.com/get-started/',
                'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 2,
            ],

            // ─── PPLG XI-1: Bahasa Inggris ──────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['BIG'], 'guru' => $gurus[2],
                'judul' => 'Present Perfect Tense',
                'deskripsi' => 'Materi tentang Present Perfect Tense dan penggunaannya.',
                'konten' => "<h2>Present Perfect Tense</h2><p>Present Perfect Tense digunakan untuk menyatakan kejadian yang terjadi di masa lalu dan masih berhubungan dengan kondisi saat ini.</p><p><strong>Rumus:</strong> Subject + have/has + Past Participle</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── PPLG XI-1: Matematika ──────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['MTK'], 'guru' => $gurus[2],
                'judul' => 'Sistem Persamaan Linear',
                'deskripsi' => 'Materi tentang SPLDV dan metode penyelesaiannya.',
                'konten' => null,
                'tipe' => 'file', 'file_path' => 'materi/spldv-matematika.pdf',
                'video_url' => null, 'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── TJKT X-1: Fisika ────────────────────────────────────
            [
                'kelas' => $kelasTJKTX1, 'mapel' => $mapels['FIS'], 'guru' => $gurus[7],
                'judul' => 'Hukum Ohm dan Rangkaian Listrik',
                'deskripsi' => 'Materi fisika tentang hukum Ohm, tegangan, arus, dan hambatan.',
                'konten' => "<h2>Hukum Ohm</h2><p>V = I × R</p><p>Di mana V = Tegangan (Volt), I = Arus (Ampere), R = Hambatan (Ohm).</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],
            [
                'kelas' => $kelasTJKTX1, 'mapel' => $mapels['FIS'], 'guru' => $gurus[7],
                'judul' => 'Video Eksperimen Rangkaian Listrik',
                'deskripsi' => 'Demonstrasi rangkaian seri dan paralel.',
                'konten' => null,
                'tipe' => 'video', 'file_path' => null,
                'video_url' => 'https://www.youtube.com/watch?v=VsNzMQGuCIY',
                'pertemuan_ke' => 2, 'is_published' => true, 'urutan' => 2,
            ],

            // ─── TJKT X-1: Kimia ────────────────────────────────────
            [
                'kelas' => $kelasTJKTX1, 'mapel' => $mapels['KIM'], 'guru' => $gurus[7],
                'judul' => 'Struktur Atom dan Tabel Periodik',
                'deskripsi' => 'Materi kimia tentang struktur atom dan sistem periodik unsur.',
                'konten' => "<h2>Struktur Atom</h2><p>Atom terdiri dari proton, neutron, dan elektron. Nomor atom menunjukkan jumlah proton dalam inti atom.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── TJKT X-1: Bahasa Indonesia ─────────────────────────
            [
                'kelas' => $kelasTJKTX1, 'mapel' => $mapels['BIN'], 'guru' => $gurus[1],
                'judul' => 'Teks Prosedur',
                'deskripsi' => 'Cara menulis teks prosedur yang baik dan benar.',
                'konten' => "<h2>Teks Prosedur</h2><p>Teks prosedur adalah teks yang berisi langkah-langkah untuk melakukan suatu kegiatan.</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── TJKT X-2: Fisika ────────────────────────────────────
            [
                'kelas' => $kelasTJKTX2, 'mapel' => $mapels['FIS'], 'guru' => $gurus[7],
                'judul' => 'Gelombang dan Bunyi',
                'deskripsi' => 'Materi tentang sifat gelombang, jenis-jenis gelombang, dan karakteristik bunyi.',
                'konten' => null,
                'tipe' => 'file', 'file_path' => 'materi/fisika-gelombang.pdf',
                'video_url' => null, 'pertemuan_ke' => 1, 'is_published' => true, 'urutan' => 1,
            ],

            // ─── Draft materi ────────────────────────────────────────
            [
                'kelas' => $kelasPPLGY1, 'mapel' => $mapels['PBO-01'], 'guru' => $gurus[3],
                'judul' => 'Design Pattern (Draft)',
                'deskripsi' => 'Materi tentang design pattern - masih dalam tahap penyusunan.',
                'konten' => "<h2>Design Pattern</h2><p>TBA</p>",
                'tipe' => 'text', 'file_path' => null, 'video_url' => null,
                'pertemuan_ke' => 8, 'is_published' => false, 'urutan' => 8,
            ],
        ];

        foreach ($materiList as $item) {
            Materi::create([
                'class_id'      => $item['kelas']->id,
                'mapel_id'      => $item['mapel']->id,
                'guru_id'       => $item['guru']->id,
                'judul'         => $item['judul'],
                'deskripsi'     => $item['deskripsi'],
                'konten'        => $item['konten'],
                'tipe'          => $item['tipe'],
                'file_path'     => $item['file_path'],
                'video_url'     => $item['video_url'],
                'pertemuan_ke'  => $item['pertemuan_ke'],
                'is_published'  => $item['is_published'],
                'urutan'        => $item['urutan'],
            ]);
        }

        $this->command->info('✓ ' . count($materiList) . ' materi berhasil dibuat');
    }
}
