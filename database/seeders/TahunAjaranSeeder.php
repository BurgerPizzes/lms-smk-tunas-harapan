<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjarans = [
            [
                'nama'          => '2024/2025 Ganjil',
                'tahun_mulai'   => 2024,
                'tahun_selesai' => 2025,
                'semester'      => 'ganjil',
                'aktif'         => true,
            ],
            [
                'nama'          => '2024/2025 Genap',
                'tahun_mulai'   => 2024,
                'tahun_selesai' => 2025,
                'semester'      => 'genap',
                'aktif'         => false,
            ],
            [
                'nama'          => '2023/2024 Ganjil',
                'tahun_mulai'   => 2023,
                'tahun_selesai' => 2024,
                'semester'      => 'ganjil',
                'aktif'         => false,
            ],
            [
                'nama'          => '2023/2024 Genap',
                'tahun_mulai'   => 2023,
                'tahun_selesai' => 2024,
                'semester'      => 'genap',
                'aktif'         => false,
            ],
        ];

        foreach ($tahunAjarans as $ta) {
            TahunAjaran::create($ta);
        }

        $this->command->info('✓ 4 tahun ajaran berhasil dibuat');
    }
}
