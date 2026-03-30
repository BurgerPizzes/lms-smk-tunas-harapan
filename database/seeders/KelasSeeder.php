<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates:
     *  - 9 kelas across PPLG and TJKT
     *  - Assigns wali kelas from guru
     *  - Enrolls siswa into classes via class_user pivot
     */
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::where('aktif', true)->first();
        $pplgJurusan = Jurusan::where('kode', 'PPLG')->first();
        $tjktJurusan = Jurusan::where('kode', 'TJKT')->first();

        // Get guru users (IDs 2-11 from UserSeeder)
        $gurus = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))
            ->orderBy('id')
            ->get();

        // Get siswa users (IDs 12-61 from UserSeeder)
        $siswas = User::whereHas('roles', fn($q) => $q->where('name', 'siswa'))
            ->orderBy('id')
            ->get();

        // ─── Define kelas ───────────────────────────────────────────
        $kelasData = [
            // PPLG
            ['nama' => 'PPLG X-1',  'jurusan' => $pplgJurusan, 'tingkat' => 10, 'ruangan' => 'Lab Komputer 1',  'wali' => 0],
            ['nama' => 'PPLG X-2',  'jurusan' => $pplgJurusan, 'tingkat' => 10, 'ruangan' => 'Lab Komputer 2',  'wali' => 1],
            ['nama' => 'PPLG XI-1', 'jurusan' => $pplgJurusan, 'tingkat' => 11, 'ruangan' => 'Lab Komputer 3',  'wali' => 2],
            ['nama' => 'PPLG XI-2', 'jurusan' => $pplgJurusan, 'tingkat' => 11, 'ruangan' => 'Lab Komputer 4',  'wali' => 3],
            ['nama' => 'PPLG XII-1','jurusan' => $pplgJurusan, 'tingkat' => 12, 'ruangan' => 'Lab Komputer 5',  'wali' => 4],
            ['nama' => 'PPLG XII-2','jurusan' => $pplgJurusan, 'tingkat' => 12, 'ruangan' => 'Ruang Teori 1',   'wali' => 5],
            // TJKT
            ['nama' => 'TJKT X-1', 'jurusan' => $tjktJurusan, 'tingkat' => 10, 'ruangan' => 'Lab Jaringan 1',  'wali' => 6],
            ['nama' => 'TJKT X-2', 'jurusan' => $tjktJurusan, 'tingkat' => 10, 'ruangan' => 'Lab Jaringan 2',  'wali' => 7],
            ['nama' => 'TJKT XI-1','jurusan' => $tjktJurusan, 'tingkat' => 11, 'ruangan' => 'Lab Jaringan 3',  'wali' => 8],
        ];

        $createdKelas = [];
        $kodeUnikList = [
            'PPLGX1', 'PPLGX2', 'PPLGY1', 'PPLGY2', 'PPLGZ1', 'PPLGZ2',
            'TJKTX1', 'TJKTX2', 'TJKTY1',
        ];

        foreach ($kelasData as $idx => $data) {
            $waliGuru = $gurus[$data['wali']] ?? $gurus->first();

            $kelas = Kelas::create([
                'nama'           => $data['nama'],
                'jurusan_id'     => $data['jurusan']->id,
                'tahun_ajaran_id'=> $tahunAjaran->id,
                'tingkat'        => $data['tingkat'],
                'ruangan'        => $data['ruangan'],
                'kapasitas'      => 36,
                'kode_unik'      => $kodeUnikList[$idx],
                'deskripsi'      => "Kelas {$data['nama']} - {$data['jurusan']->nama} - SMK Telekomunikasi Tunas Harapan",
                'is_active'      => true,
                'cover_image'    => null,
                'guru_id'        => $waliGuru->id,
            ]);

            $createdKelas[] = $kelas;
        }

        // ─── Enroll siswa into classes ──────────────────────────────
        // Distribute 50 siswa:
        //   PPLG X-1: siswa 0-5 (6 siswa)
        //   PPLG X-2: siswa 6-11 (6 siswa)
        //   PPLG XI-1: siswa 12-17 (6 siswa)
        //   PPLG XI-2: siswa 18-23 (6 siswa)
        //   PPLG XII-1: siswa 24-27 (4 siswa)
        //   PPLG XII-2: siswa 28-31 (4 siswa)
        //   TJKT X-1: siswa 32-37 (6 siswa)
        //   TJKT X-2: siswa 38-43 (6 siswa)
        //   TJKT XI-1: siswa 44-49 (6 siswa)

        $enrollmentMap = [
            0  => [0, 5],    // PPLG X-1
            1  => [6, 11],   // PPLG X-2
            2  => [12, 17],  // PPLG XI-1
            3  => [18, 23],  // PPLG XI-2
            4  => [24, 27],  // PPLG XII-1
            5  => [28, 31],  // PPLG XII-2
            6  => [32, 37],  // TJKT X-1
            7  => [38, 43],  // TJKT X-2
            8  => [44, 49],  // TJKT XI-1
        ];

        foreach ($enrollmentMap as $kelasIdx => [$start, $end]) {
            $kelas = $createdKelas[$kelasIdx];
            $jurusanId = $kelas->jurusan_id;

            for ($i = $start; $i <= $end && $i < $siswas->count(); $i++) {
                $siswa = $siswas[$i];

                // Update siswa's jurusan and kelas
                $siswa->update([
                    'kelas_id'   => $kelas->id,
                    'jurusan_id' => $jurusanId,
                ]);

                // Sync to class_user pivot
                $kelas->siswas()->syncWithoutDetaching([
                    $siswa->id => [
                        'joined_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }

        $this->command->info('✓ 9 kelas berhasil dibuat dengan wali kelas dan siswa terdaftar');
    }
}
