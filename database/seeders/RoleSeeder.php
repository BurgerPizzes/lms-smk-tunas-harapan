<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Akses penuh ke sistem',
                'guard_name'   => 'web',
            ],
            [
                'name'         => 'guru',
                'display_name' => 'Guru/Pengajar',
                'description'  => 'Mengelola kelas, materi, dan penilaian',
                'guard_name'   => 'web',
            ],
            [
                'name'         => 'siswa',
                'display_name' => 'Siswa',
                'description'  => 'Mengakses materi dan mengerjakan tugas',
                'guard_name'   => 'web',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $this->command->info('✓ 3 roles berhasil dibuat (admin, guru, siswa)');
    }
}
