<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            [
                'kode'      => 'PPLG',
                'nama'      => 'Pengembangan Perangkat Lunak dan Gim',
                'deskripsi' => 'Kompetensi keahlian dalam pengembangan perangkat lunak, aplikasi web, mobile, dan game development.',
                'aktif'     => true,
            ],
            [
                'kode'      => 'TJKT',
                'nama'      => 'Teknik Jaringan Komputer dan Telekomunikasi',
                'deskripsi' => 'Kompetensi keahlian dalam jaringan komputer, telekomunikasi, dan keamanan siber.',
                'aktif'     => true,
            ],
            [
                'kode'      => 'TKJ',
                'nama'      => 'Teknik Komputer dan Jaringan',
                'deskripsi' => 'Kompetensi keahlian dalam instalasi, konfigurasi, dan pemeliharaan jaringan komputer.',
                'aktif'     => true,
            ],
            [
                'kode'      => 'RPL',
                'nama'      => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Kompetensi keahlian dalam merancang, membangun, dan menguji perangkat lunak aplikasi.',
                'aktif'     => true,
            ],
            [
                'kode'      => 'MM',
                'nama'      => 'Multimedia',
                'deskripsi' => 'Kompetensi keahlian dalam desain grafis, animasi, fotografi, dan produksi multimedia.',
                'aktif'     => true,
            ],
        ];

        foreach ($jurusans as $jurusan) {
            Jurusan::create($jurusan);
        }

        $this->command->info('✓ 5 jurusan berhasil dibuat');
    }
}
