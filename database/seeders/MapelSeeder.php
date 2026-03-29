<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Mapel;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates mata pelajaran: Normatif, Adaptif, Produktif (PPLG).
     */
    public function run(): void
    {
        $pplgJurusan = Jurusan::where('kode', 'PPLG')->first();

        // ─── NORMATIF (common to all jurusan) ──────────────────────
        $normatif = [
            ['kode' => 'PKN',   'nama' => 'Pendidikan Kewarganegaraan',  'semester' => 1],
            ['kode' => 'BIN',   'nama' => 'Bahasa Indonesia',            'semester' => 1],
            ['kode' => 'BIG',   'nama' => 'Bahasa Inggris',              'semester' => 1],
            ['kode' => 'MTK',   'nama' => 'Matematika',                  'semester' => 1],
            ['kode' => 'PAI',   'nama' => 'Pendidikan Agama Islam',      'semester' => 1],
            ['kode' => 'SBD',   'nama' => 'Seni Budaya',                 'semester' => 1],
            ['kode' => 'PJOK',  'nama' => 'PJOK (Pend. Jasmani)',        'semester' => 1],
            ['kode' => 'P5',    'nama' => 'Pendidikan Pancasila',        'semester' => 1],
        ];

        foreach ($normatif as $item) {
            Mapel::create([
                'kode'      => $item['kode'],
                'nama'      => $item['nama'],
                'deskripsi' => "Mata pelajaran {$item['nama']} (Muatan Normatif)",
                'kategori'  => 'normatif',
                'jurusan_id' => null, // applies to all jurusan
                'semester'  => $item['semester'],
                'kkm'       => 75,
                'is_active' => true,
            ]);
        }

        // ─── ADAPTIF ────────────────────────────────────────────────
        $adaptif = [
            ['kode' => 'MTK-A',  'nama' => 'Matematika Lanjut',        'semester' => 1],
            ['kode' => 'FIS',    'nama' => 'Fisika',                    'semester' => 1],
            ['kode' => 'KIM',    'nama' => 'Kimia',                     'semester' => 1],
            ['kode' => 'BIG-A',  'nama' => 'Bahasa Inggris Lanjutan',   'semester' => 1],
            ['kode' => 'BIO',    'nama' => 'Biologi',                   'semester' => 1],
        ];

        foreach ($adaptif as $item) {
            Mapel::create([
                'kode'      => $item['kode'],
                'nama'      => $item['nama'],
                'deskripsi' => "Mata pelajaran {$item['nama']} (Muatan Adaptif)",
                'kategori'  => 'adaptif',
                'jurusan_id' => null,
                'semester'  => $item['semester'],
                'kkm'       => 75,
                'is_active' => true,
            ]);
        }

        // ─── PRODUKTIF (PPLG) ───────────────────────────────────────
        $produktif = [
            ['kode' => 'PD-01',  'nama' => 'Pemrograman Dasar',              'semester' => 1, 'desc' => 'Dasar algoritma dan pemrograman menggunakan bahasa pemrograman dasar'],
            ['kode' => 'PW-01',  'nama' => 'Pemrograman Web (HTML/CSS/JS)',  'semester' => 1, 'desc' => 'Pengembangan web menggunakan HTML, CSS, dan JavaScript'],
            ['kode' => 'PBO-01', 'nama' => 'Pemrograman Berorientasi Objek', 'semester' => 2, 'desc' => 'Konsep OOP dan implementasinya dalam bahasa pemrograman'],
            ['kode' => 'BD-01',  'nama' => 'Basis Data',                     'semester' => 2, 'desc' => 'Perancangan dan implementasi basis data relasional'],
            ['kode' => 'PM-01',  'nama' => 'Pemrograman Mobile',              'semester' => 3, 'desc' => 'Pengembangan aplikasi mobile Android'],
            ['kode' => 'UIX-01', 'nama' => 'Desain UI/UX',                   'semester' => 2, 'desc' => 'Prinsip dan praktik desain antarmuka pengguna dan pengalaman pengguna'],
            ['kode' => 'GD-01',  'nama' => 'Game Development',               'semester' => 3, 'desc' => 'Pengembangan game menggunakan game engine'],
            ['kode' => 'DVC-01', 'nama' => 'DevOps dan Cloud Computing',      'semester' => 3, 'desc' => 'Konsep DevOps, CI/CD, dan cloud computing'],
        ];

        foreach ($produktif as $item) {
            Mapel::create([
                'kode'      => $item['kode'],
                'nama'      => $item['nama'],
                'deskripsi' => $item['desc'],
                'kategori'  => 'produktif',
                'jurusan_id' => $pplgJurusan->id,
                'semester'  => $item['semester'],
                'kkm'       => 75,
                'is_active' => true,
            ]);
        }

        $this->command->info('✓ 8 normatif, 5 adaptif, 8 produktif mapel berhasil dibuat');
    }
}
