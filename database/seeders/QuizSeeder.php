<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates 3 quizzes with 5-10 questions each and 20+ quiz attempts.
     */
    public function run(): void
    {
        $mapels = Mapel::all()->keyBy('kode');
        $gurus  = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))
            ->orderBy('id')->get();

        $kelasPPLGX1 = Kelas::where('nama', 'PPLG X-1')->first();
        $kelasPPLGY1 = Kelas::where('nama', 'PPLG XI-1')->first();
        $kelasPPLGZ1 = Kelas::where('nama', 'PPLG XII-1')->first();

        // ─── Quiz 1: Pemrograman Dasar - PPLG X-1 ──────────────────
        $quiz1 = Quiz::create([
            'tugas_id'      => null,
            'class_id'      => $kelasPPLGX1->id,
            'mapel_id'      => $mapels['PD-01']->id,
            'guru_id'       => $gurus[0]->id,
            'judul'         => 'Quiz Algoritma dan Pemrograman Dasar',
            'deskripsi'     => 'Quiz pertengahan semester untuk menguji pemahaman dasar algoritma dan pemrograman.',
            'durasi_menit'  => 30,
            'jumlah_soal'   => 8,
            'random_soal'   => true,
            'show_result'   => true,
            'is_published'  => true,
            'mulai_at'      => now()->subDays(20),
            'selesai_at'    => now()->subDays(15),
        ]);

        $quiz1Questions = [
            [
                'pertanyaan' => 'Apa yang dimaksud dengan algoritma?',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Bahasa pemrograman', 'pilihan_b' => 'Langkah-langkah sistematis untuk menyelesaikan masalah',
                'pilihan_c' => 'Tipe data', 'pilihan_d' => 'Variabel', 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => 'Algoritma adalah langkah-langkah sistematis untuk menyelesaikan suatu masalah.',
                'poin' => 10, 'urutan' => 1,
            ],
            [
                'pertanyaan' => 'Tipe data yang digunakan untuk menyimpan bilangan desimal adalah...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Integer', 'pilihan_b' => 'String', 'pilihan_c' => 'Float/Double',
                'pilihan_d' => 'Boolean', 'pilihan_e' => 'Character',
                'jawaban_benar' => 'c', 'pembahasan' => 'Float atau Double digunakan untuk menyimpan bilangan desimal.',
                'poin' => 10, 'urutan' => 2,
            ],
            [
                'pertanyaan' => 'Pernyataan "IF-ELSE" digunakan untuk membuat percabangan. Pernyataan ini bernilai TRUE.',
                'tipe' => 'true_false',
                'pilihan_a' => 'Benar', 'pilihan_b' => 'Salah', 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'a', 'pembahasan' => 'Pernyataan ini benar. IF-ELSE adalah struktur kontrol percabangan dalam pemrograman.',
                'poin' => 10, 'urutan' => 3,
            ],
            [
                'pertanyaan' => 'Manakah yang BUKAN merupakan tipe data dasar?',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Integer', 'pilihan_b' => 'Float', 'pilihan_c' => 'Array',
                'pilihan_d' => 'String', 'pilihan_e' => null,
                'jawaban_benar' => 'c', 'pembahasan' => 'Array adalah tipe data kolektif/bentukan, bukan tipe data dasar.',
                'poin' => 10, 'urutan' => 4,
            ],
            [
                'pertanyaan' => 'Jelaskan perbedaan antara perulangan FOR dan WHILE!',
                'tipe' => 'essay',
                'pilihan_a' => null, 'pilihan_b' => null, 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'FOR digunakan ketika jumlah iterasi sudah diketahui, sedangkan WHILE digunakan ketika jumlah iterasi tidak pasti/tidak diketahui. FOR memiliki inisialisasi, kondisi, dan increment dalam satu baris. WHILE hanya memeriksa kondisi.',
                'poin' => 20, 'urutan' => 5,
            ],
            [
                'pertanyaan' => 'Operator == dan === memiliki fungsi yang sama.',
                'tipe' => 'true_false',
                'pilihan_a' => 'Benar', 'pilihan_b' => 'Salah', 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => '=== juga membandingkan tipe data, sedangkan == hanya membandingkan nilai.',
                'poin' => 10, 'urutan' => 6,
            ],
            [
                'pertanyaan' => 'Fungsi yang tidak mengembalikan nilai disebut...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Return function', 'pilihan_b' => 'Void function', 'pilihan_c' => 'Main function',
                'pilihan_d' => 'Recursive function', 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => 'Void function adalah fungsi yang tidak mengembalikan nilai (void = kosong).',
                'poin' => 10, 'urutan' => 7,
            ],
            [
                'pertanyaan' => 'Buat pseudocode untuk menghitung faktorial dari suatu bilangan!',
                'tipe' => 'essay',
                'pilihan_a' => null, 'pilihan_b' => null, 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'Contoh pseudocode:\nALGORITMA Faktorial\nDEKLARASI\n  n, i, faktorial : integer\nDESKRIPSI\n  Read(n)\n  faktorial ← 1\n  FOR i ← 1 TO n DO\n    faktorial ← faktorial * i\n  ENDFOR\n  Write(faktorial)',
                'poin' => 20, 'urutan' => 8,
            ],
        ];

        foreach ($quiz1Questions as $q) {
            QuizQuestion::create(array_merge(['quiz_id' => $quiz1->id], $q));
        }

        // ─── Quiz 2: PBO - PPLG XI-1 ──────────────────────────────
        $quiz2 = Quiz::create([
            'tugas_id'      => null,
            'class_id'      => $kelasPPLGY1->id,
            'mapel_id'      => $mapels['PBO-01']->id,
            'guru_id'       => $gurus[3]->id,
            'judul'         => 'Quiz OOP - Class, Object, dan Inheritance',
            'deskripsi'     => 'Quiz untuk menguji pemahaman konsep OOP dasar.',
            'durasi_menit'  => 45,
            'jumlah_soal'   => 10,
            'random_soal'   => true,
            'show_result'   => true,
            'is_published'  => true,
            'mulai_at'      => now()->subDays(10),
            'selesai_at'    => now()->subDays(5),
        ]);

        $quiz2Questions = [
            [
                'pertanyaan' => 'Apa itu Class dalam OOP?',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Variabel', 'pilihan_b' => 'Blueprint/cetakan untuk membuat objek',
                'pilihan_c' => 'Fungsi', 'pilihan_d' => 'Looping', 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => 'Class adalah blueprint atau cetakan untuk membuat objek.',
                'poin' => 10, 'urutan' => 1,
            ],
            [
                'pertanyaan' => 'Encapsulation berarti...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Menyembunyikan detail implementasi dari luar', 'pilihan_b' => 'Mewarisi sifat parent',
                'pilihan_c' => 'Satu nama banyak bentuk', 'pilihan_d' => 'Membuat class baru', 'pilihan_e' => null,
                'jawaban_benar' => 'a', 'pembahasan' => 'Encapsulation adalah menyembunyikan detail implementasi dan hanya menampilkan interface.',
                'poin' => 10, 'urutan' => 2,
            ],
            [
                'pertanyaan' => 'Keyword yang digunakan untuk mewarisi class di PHP adalah...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'implements', 'pilihan_b' => 'inherits', 'pilihan_c' => 'extends',
                'pilihan_d' => 'super', 'pilihan_e' => null,
                'jawaban_benar' => 'c', 'pembahasan' => 'extends digunakan untuk pewarisan class di PHP.',
                'poin' => 10, 'urutan' => 3,
            ],
            [
                'pertanyaan' => 'Polymorphism memungkinkan method dengan nama sama memiliki perilaku berbeda.',
                'tipe' => 'true_false',
                'pilihan_a' => 'Benar', 'pilihan_b' => 'Salah', 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'a', 'pembahasan' => 'Polymorphism adalah konsep di mana satu interface dapat digunakan untuk bentuk berbeda.',
                'poin' => 10, 'urutan' => 4,
            ],
            [
                'pertanyaan' => 'Constructor adalah method khusus yang dipanggil ketika...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Class dihapus', 'pilihan_b' => 'Object dibuat',
                'pilihan_c' => 'Program selesai', 'pilihan_d' => 'Error terjadi', 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => 'Constructor dipanggil secara otomatis saat object dibuat (instansiasi).',
                'poin' => 10, 'urutan' => 5,
            ],
            [
                'pertanyaan' => 'Sebutkan 4 pilar OOP dan jelaskan masing-masing!',
                'tipe' => 'essay',
                'pilihan_a' => null, 'pilihan_b' => null, 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => '4 pilar OOP:\n1. Encapsulation - Menyembunyikan detail implementasi\n2. Inheritance - Pewarisan sifat dari parent ke child\n3. Polymorphism - Satu nama banyak bentuk\n4. Abstraction - Menyembunyikan kompleksitas',
                'poin' => 20, 'urutan' => 6,
            ],
            [
                'pertanyaan' => 'Apa perbedaan abstract class dan interface?',
                'tipe' => 'essay',
                'pilihan_a' => null, 'pilihan_b' => null, 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'Abstract class bisa memiliki implementasi method, sedangkan interface hanya deklarasi. Class hanya bisa extend 1 abstract class tapi bisa implement banyak interface.',
                'poin' => 20, 'urutan' => 7,
            ],
            [
                'pertanyaan' => 'Access modifier "protected" berarti...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Hanya bisa diakses dari class itu sendiri', 'pilihan_b' => 'Bisa diakses dari class itu sendiri dan child class',
                'pilihan_c' => 'Bisa diakses dari mana saja', 'pilihan_d' => 'Tidak bisa diakses sama sekali', 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => 'Protected memungkinkan akses dari class sendiri dan class turunannya.',
                'poin' => 10, 'urutan' => 8,
            ],
            [
                'pertanyaan' => 'Method overriding terjadi pada...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Satu class yang sama', 'pilihan_b' => 'Hubungan parent-child (inheritance)',
                'pilihan_c' => 'Dua class yang tidak berhubungan', 'pilihan_d' => 'Dalam interface saja', 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => 'Overriding terjadi saat child class mendefinisikan ulang method parent class.',
                'poin' => 10, 'urutan' => 9,
            ],
            [
                'pertanyaan' => 'Instance variable adalah variabel yang dideklarasikan di dalam class tetapi di luar method.',
                'tipe' => 'true_false',
                'pilihan_a' => 'Benar', 'pilihan_b' => 'Salah', 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'a', 'pembahasan' => 'Instance variable (atau attribute) dideklarasikan di dalam class di luar method.',
                'poin' => 10, 'urutan' => 10,
            ],
        ];

        foreach ($quiz2Questions as $q) {
            QuizQuestion::create(array_merge(['quiz_id' => $quiz2->id], $q));
        }

        // ─── Quiz 3: Pemrograman Web - PPLG X-1 ────────────────────
        $quiz3 = Quiz::create([
            'tugas_id'      => null,
            'class_id'      => $kelasPPLGX1->id,
            'mapel_id'      => $mapels['PW-01']->id,
            'guru_id'       => $gurus[0]->id,
            'judul'         => 'Quiz HTML dan CSS Dasar',
            'deskripsi'     => 'Quiz untuk menguji pemahaman dasar HTML5 dan CSS3.',
            'durasi_menit'  => 25,
            'jumlah_soal'   => 5,
            'random_soal'   => false,
            'show_result'   => true,
            'is_published'  => true,
            'mulai_at'      => now()->subDays(5),
            'selesai_at'    => now()->subDays(2),
        ]);

        $quiz3Questions = [
            [
                'pertanyaan' => 'Tag HTML untuk membuat heading terbesar adalah...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => '<h6>', 'pilihan_b' => '<h1>', 'pilihan_c' => '<head>',
                'pilihan_d' => '<title>', 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => '<h1> adalah tag heading dengan ukuran terbesar.',
                'poin' => 20, 'urutan' => 1,
            ],
            [
                'pertanyaan' => 'CSS adalah singkatan dari...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'Computer Style Sheets', 'pilihan_b' => 'Creative Style Sheets',
                'pilihan_c' => 'Cascading Style Sheets', 'pilihan_d' => 'Colorful Style Sheets', 'pilihan_e' => null,
                'jawaban_benar' => 'c', 'pembahasan' => 'CSS adalah Cascading Style Sheets.',
                'poin' => 20, 'urutan' => 2,
            ],
            [
                'pertanyaan' => 'Property CSS yang digunakan untuk mengubah warna teks adalah...',
                'tipe' => 'pilihan_ganda',
                'pilihan_a' => 'text-color', 'pilihan_b' => 'font-color', 'pilihan_c' => 'color',
                'pilihan_d' => 'text-style', 'pilihan_e' => null,
                'jawaban_benar' => 'c', 'pembahasan' => 'Property "color" digunakan untuk mengubah warna teks.',
                'poin' => 20, 'urutan' => 3,
            ],
            [
                'pertanyaan' => 'HTML adalah bahasa pemrograman.',
                'tipe' => 'true_false',
                'pilihan_a' => 'Benar', 'pilihan_b' => 'Salah', 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'b', 'pembahasan' => 'HTML adalah bahasa markup, bukan bahasa pemrograman.',
                'poin' => 20, 'urutan' => 4,
            ],
            [
                'pertanyaan' => 'Sebutkan 5 tag HTML yang sering digunakan beserta fungsinya!',
                'tipe' => 'essay',
                'pilihan_a' => null, 'pilihan_b' => null, 'pilihan_c' => null, 'pilihan_d' => null, 'pilihan_e' => null,
                'jawaban_benar' => 'Contoh: <p> (paragraf), <a> (link), <img> (gambar), <div> (kontainer), <ul>/<li> (list).',
                'poin' => 20, 'urutan' => 5,
            ],
        ];

        foreach ($quiz3Questions as $q) {
            QuizQuestion::create(array_merge(['quiz_id' => $quiz3->id], $q));
        }

        // ─── Quiz Attempts ──────────────────────────────────────────
        $attemptCount = 0;

        // Quiz 1 attempts: PPLG X-1 siswa
        $quiz1Siswa = $kelasPPLGX1->siswas;
        foreach ($quiz1Siswa as $siswa) {
            $totalBenar = mt_rand(3, 8);
            $totalSalah = 8 - $totalBenar;
            $skor = $totalBenar >= 6 ? mt_rand(70, 100) : mt_rand(30, 65);

            QuizAttempt::create([
                'quiz_id'      => $quiz1->id,
                'siswa_id'     => $siswa->id,
                'skor'         => $skor,
                'total_benar'  => $totalBenar,
                'total_salah'  => $totalSalah,
                'total_soal'   => 8,
                'waktu_mulai'  => $quiz1->mulai_at->copy()->addHours(mt_rand(1, 72)),
                'waktu_selesai'=> $quiz1->mulai_at->copy()->addHours(mt_rand(2, 74)),
                'durasi_detik' => mt_rand(600, 1800),
                'status'       => 'selesai',
                'answers'      => null,
            ]);
            $attemptCount++;
        }

        // Quiz 2 attempts: PPLG XI-1 siswa
        $quiz2Siswa = $kelasPPLGY1->siswas;
        foreach ($quiz2Siswa as $siswa) {
            $totalBenar = mt_rand(4, 10);
            $totalSalah = 10 - $totalBenar;
            $skor = $totalBenar >= 7 ? mt_rand(75, 100) : mt_rand(40, 70);

            QuizAttempt::create([
                'quiz_id'      => $quiz2->id,
                'siswa_id'     => $siswa->id,
                'skor'         => $skor,
                'total_benar'  => $totalBenar,
                'total_salah'  => $totalSalah,
                'total_soal'   => 10,
                'waktu_mulai'  => $quiz2->mulai_at->copy()->addHours(mt_rand(1, 72)),
                'waktu_selesai'=> $quiz2->mulai_at->copy()->addHours(mt_rand(2, 74)),
                'durasi_detik' => mt_rand(900, 2700),
                'status'       => 'selesai',
                'answers'      => null,
            ]);
            $attemptCount++;
        }

        // Quiz 3 attempts: PPLG X-1 siswa
        $quiz3Siswa = $kelasPPLGX1->siswas;
        foreach ($quiz3Siswa as $siswa) {
            $totalBenar = mt_rand(2, 5);
            $totalSalah = 5 - $totalBenar;
            $skor = $totalBenar >= 4 ? mt_rand(80, 100) : mt_rand(40, 75);

            QuizAttempt::create([
                'quiz_id'      => $quiz3->id,
                'siswa_id'     => $siswa->id,
                'skor'         => $skor,
                'total_benar'  => $totalBenar,
                'total_salah'  => $totalSalah,
                'total_soal'   => 5,
                'waktu_mulai'  => $quiz3->mulai_at->copy()->addHours(mt_rand(1, 48)),
                'waktu_selesai'=> $quiz3->mulai_at->copy()->addHours(mt_rand(1, 50)),
                'durasi_detik' => mt_rand(300, 1500),
                'status'       => 'selesai',
                'answers'      => null,
            ]);
            $attemptCount++;
        }

        $this->command->info("✓ 3 quiz dibuat dengan " . (count($quiz1Questions) + count($quiz2Questions) + count($quiz3Questions)) . " soal dan {$attemptCount} attempts");
    }
}
