<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TahunAjaran;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates:
     *  - 1 admin
     *  - 10 guru
     *  - 50 siswa
     */
    public function run(): void
    {
        $faker      = Factory::create('id_ID');
        $adminRole  = Role::where('name', 'admin')->first();
        $guruRole   = Role::where('name', 'guru')->first();
        $siswaRole  = Role::where('name', 'siswa')->first();
        $tahunAjaran = TahunAjaran::where('aktif', true)->first();

        // ─── 1. Admin ──────────────────────────────────────────────
        $admin = User::create([
            'name'             => 'Administrator LMS',
            'email'            => 'admin@smktunas.sch.id',
            'password'         => Hash::make('password'),
            'nip'              => '0000000000000000',
            'nis'              => null,
            'nisn'             => null,
            'kelas_id'         => null,
            'jurusan_id'       => null,
            'tahun_ajaran_id'  => null,
            'jenis_kelamin'    => 'laki-laki',
            'tempat_lahir'     => 'Jakarta',
            'tanggal_lahir'    => '1985-01-15',
            'alamat'           => 'Jl. Pendidikan No. 1, Jakarta Selatan',
            'no_hp'            => '081234567890',
            'is_active'        => true,
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach($adminRole);

        // ─── 2. Guru (10 orang) ────────────────────────────────────
        $guruData = [
            ['name' => 'Budi Santoso, S.Kom',       'nip' => '197805152003121001', 'jk' => 'laki-laki'],
            ['name' => 'Siti Nurhaliza, S.Pd',       'nip' => '198203102006042002', 'jk' => 'perempuan'],
            ['name' => 'Ahmad Hidayat, M.Pd',        'nip' => '197601202005011003', 'jk' => 'laki-laki'],
            ['name' => 'Dewi Kartika, S.Kom',        'nip' => '198507152010012004', 'jk' => 'perempuan'],
            ['name' => 'Rahmat Widodo, S.T',         'nip' => '198009302008011005', 'jk' => 'laki-laki'],
            ['name' => 'Endang Sulistyowati, M.Pd',  'nip' => '197412182003122006', 'jk' => 'perempuan'],
            ['name' => 'Hendra Gunawan, S.Kom',      'nip' => '198208252009011007', 'jk' => 'laki-laki'],
            ['name' => 'Yuliana Puspitasari, S.Pd',  'nip' => '198603172011012008', 'jk' => 'perempuan'],
            ['name' => 'Agus Prasetyo, M.Cs',        'nip' => '197906052007011009', 'jk' => 'laki-laki'],
            ['name' => 'Rina Wulandari, S.Pd',       'nip' => '198411282012012010', 'jk' => 'perempuan'],
        ];

        $guruIds = [];
        $kotaList = ['Bandung', 'Jakarta', 'Surabaya', 'Semarang', 'Yogyakarta', 'Malang', 'Bekasi', 'Bogor', 'Depok', 'Tangerang'];

        foreach ($guruData as $i => $guru) {
            $user = User::create([
                'name'             => $guru['name'],
                'email'            => 'guru' . ($i + 1) . '@smktunas.sch.id',
                'password'         => Hash::make('password123'),
                'nip'              => $guru['nip'],
                'nis'              => null,
                'nisn'             => null,
                'kelas_id'         => null,
                'jurusan_id'       => null,
                'tahun_ajaran_id'  => null,
                'jenis_kelamin'    => $guru['jk'],
                'tempat_lahir'     => $kotaList[$i],
                'tanggal_lahir'    => $faker->date('Y-m-d', '1990-01-01'),
                'alamat'           => $faker->address(),
                'no_hp'            => '08' . $faker->numerify('##########'),
                'is_active'        => true,
                'email_verified_at' => now(),
            ]);
            $user->roles()->attach($guruRole);
            $guruIds[] = $user->id;
        }

        // ─── 3. Siswa (50 orang) ───────────────────────────────────
        // Distribute across kelas after KelasSeeder runs.
        // For now, we create siswa without kelas_id (will be enrolled via class_user pivot in KelasSeeder).
        // But we still set jurusan and tahun_ajaran.

        $namaDepanL = ['Ahmad', 'Budi', 'Cahyo', 'Dimas', 'Eko', 'Fajar', 'Galih', 'Hasan', 'Irfan', 'Joko',
                        'Kevin', 'Lukman', 'Muhammad', 'Naufal', 'Oscar', 'Prasetyo', 'Rizky', 'Surya', 'Taufik', 'Umar',
                        'Vino', 'Wahyu', 'Yoga', 'Zainal', 'Arif'];
        $namaDepanP = ['Aisyah', 'Bella', 'Citra', 'Dina', 'Ela', 'Fitri', 'Gita', 'Hana', 'Indah', 'Jasmine',
                        'Kartika', 'Lestari', 'Maya', 'Nadia', 'Olivia', 'Putri', 'Qori', 'Ratna', 'Sari', 'Tika',
                        'Umi', 'Vera', 'Wulan', 'Yanti', 'Zahra'];
        $namaBelakang = ['Pratama', 'Ramadhan', 'Kurniawan', 'Hidayat', 'Saputra', 'Wibowo', 'Setiawan', 'Putra',
                         'Nugroho', 'Susanto', 'Anggraeni', 'Permata', 'Maharani', 'Kusuma', 'Utami', 'Handayani',
                         'Safitri', 'Wardani', 'Puspita', 'Nirmala', 'Rahayu', 'Lestari', 'Wijaya', 'Suryadi', 'Hartono'];

        $siswaIds = [];
        $jurusanIds = \App\Models\Jurusan::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $isMale   = $i < 25;
            $firstName = $isMale
                ? $namaDepanL[$i % count($namaDepanL)]
                : $namaDepanP[($i - 25) % count($namaDepanP)];
            $lastName  = $namaBelakang[$i % count($namaBelakang)];

            $nomorUrut = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            $nis       = '2024' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);
            $nisn      = '00' . $faker->numerify('################');

            $siswa = User::create([
                'name'             => $firstName . ' ' . $lastName,
                'email'            => 'siswa' . ($i + 1) . '@smktunas.sch.id',
                'password'         => Hash::make('password123'),
                'nip'              => null,
                'nis'              => $nis,
                'nisn'             => $nisn,
                'kelas_id'         => null,
                'jurusan_id'       => null, // assigned in KelasSeeder
                'tahun_ajaran_id'  => $tahunAjaran->id,
                'jenis_kelamin'    => $isMale ? 'laki-laki' : 'perempuan',
                'tempat_lahir'     => $faker->city(),
                'tanggal_lahir'    => $faker->date('Y-m-d', '2008-06-30'),
                'alamat'           => $faker->address(),
                'no_hp'            => '08' . $faker->numerify('##########'),
                'is_active'        => true,
                'email_verified_at' => now(),
            ]);
            $siswa->roles()->attach($siswaRole);
            $siswaIds[] = $siswa->id;
        }

        $this->command->info('✓ 1 admin, 10 guru, 50 siswa berhasil dibuat');
    }
}
