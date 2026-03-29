<?php

namespace Database\Seeders;

use App\Models\ClassGuruMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassGuruMapelSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::where('aktif', true)->first();
        $gurus = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))
            ->orderBy('id')
            ->get();

        $kelasPPLGX1  = Kelas::where('nama', 'PPLG X-1')->first();
        $kelasPPLGX2  = Kelas::where('nama', 'PPLG X-2')->first();
        $kelasPPLGY1  = Kelas::where('nama', 'PPLG XI-1')->first();
        $kelasPPLGY2  = Kelas::where('nama', 'PPLG XI-2')->first();
        $kelasPPLGZ1  = Kelas::where('nama', 'PPLG XII-1')->first();
        $kelasPPLGZ2  = Kelas::where('nama', 'PPLG XII-2')->first();
        $kelasTJKTX1  = Kelas::where('nama', 'TJKT X-1')->first();
        $kelasTJKTX2  = Kelas::where('nama', 'TJKT X-2')->first();
        $kelasTJKTY1  = Kelas::where('nama', 'TJKT XI-1')->first();

        $mapels = Mapel::all()->keyBy('kode');

        $assignments = [];

        // Guru 1: Budi Santoso -> Pemrograman Dasar, Pemrograman Web (PPLG X)
        $g1 = $gurus[0];
        $assignments[] = [$kelasPPLGX1, $g1, $mapels['PD-01'], true];
        $assignments[] = [$kelasPPLGX1, $g1, $mapels['PW-01'], true];
        $assignments[] = [$kelasPPLGX2, $g1, $mapels['PD-01'], true];
        $assignments[] = [$kelasPPLGX2, $g1, $mapels['PW-01'], true];

        // Guru 2: Siti Nurhaliza -> Bahasa Indonesia, PKN (PPLG X)
        $g2 = $gurus[1];
        $assignments[] = [$kelasPPLGX1, $g2, $mapels['BIN'], true];
        $assignments[] = [$kelasPPLGX2, $g2, $mapels['BIN'], true];
        $assignments[] = [$kelasTJKTX1, $g2, $mapels['BIN'], true];
        $assignments[] = [$kelasTJKTX2, $g2, $mapels['BIN'], true];
        $assignments[] = [$kelasTJKTY1, $g2, $mapels['BIN'], true];
        $assignments[] = [$kelasPPLGZ1, $g2, $mapels['BIN'], true];
        $assignments[] = [$kelasPPLGZ2, $g2, $mapels['BIN'], true];

        // Guru 3: Ahmad Hidayat -> Bahasa Inggris (all kelas)
        $g3 = $gurus[2];
        $assignments[] = [$kelasPPLGY1, $g3, $mapels['BIG'], true];
        $assignments[] = [$kelasPPLGY1, $g3, $mapels['MTK'], true];
        $assignments[] = [$kelasPPLGY2, $g3, $mapels['BIG'], true];
        $assignments[] = [$kelasPPLGZ1, $g3, $mapels['BIG-A'], true];
        $assignments[] = [$kelasPPLGZ2, $g3, $mapels['BIG-A'], true];
        $assignments[] = [$kelasTJKTX1, $g3, $mapels['BIG'], true];
        $assignments[] = [$kelasTJKTX2, $g3, $mapels['BIG'], true];
        $assignments[] = [$kelasTJKTY1, $g3, $mapels['BIG'], true];

        // Guru 4: Dewi Kartika -> PBO, Basis Data (PPLG XI)
        $g4 = $gurus[3];
        $assignments[] = [$kelasPPLGY1, $g4, $mapels['PBO-01'], true];
        $assignments[] = [$kelasPPLGY1, $g4, $mapels['BD-01'], true];
        $assignments[] = [$kelasPPLGY2, $g4, $mapels['PBO-01'], true];
        $assignments[] = [$kelasPPLGY2, $g4, $mapels['BD-01'], true];

        // Guru 5: Rahmat Widodo -> Pemrograman Mobile, Game Dev (PPLG XII)
        $g5 = $gurus[4];
        $assignments[] = [$kelasPPLGZ1, $g5, $mapels['PM-01'], true];
        $assignments[] = [$kelasPPLGZ1, $g5, $mapels['GD-01'], true];
        $assignments[] = [$kelasPPLGZ2, $g5, $mapels['PM-01'], true];
        $assignments[] = [$kelasPPLGZ2, $g5, $mapels['DVC-01'], true];

        // Guru 6: Endang Sulistyowati -> PAI, Pendidikan Pancasila
        $g6 = $gurus[5];
        $assignments[] = [$kelasPPLGX1, $g6, $mapels['PAI'], true];
        $assignments[] = [$kelasPPLGX1, $g6, $mapels['P5'], true];
        $assignments[] = [$kelasPPLGX2, $g6, $mapels['PAI'], true];
        $assignments[] = [$kelasPPLGX2, $g6, $mapels['P5'], true];
        $assignments[] = [$kelasTJKTX1, $g6, $mapels['PAI'], true];
        $assignments[] = [$kelasTJKTX2, $g6, $mapels['PAI'], true];
        $assignments[] = [$kelasTJKTY1, $g6, $mapels['PAI'], true];
        $assignments[] = [$kelasPPLGZ1, $g6, $mapels['P5'], true];
        $assignments[] = [$kelasPPLGZ2, $g6, $mapels['PAI'], true];

        // Guru 7: Hendra Gunawan -> Desain UI/UX (PPLG XI)
        $g7 = $gurus[6];
        $assignments[] = [$kelasPPLGY1, $g7, $mapels['UIX-01'], true];
        $assignments[] = [$kelasPPLGY2, $g7, $mapels['UIX-01'], true];

        // Guru 8: Yuliana Puspitasari -> Fisika, Kimia (TJKT X)
        $g8 = $gurus[7];
        $assignments[] = [$kelasTJKTX1, $g8, $mapels['FIS'], true];
        $assignments[] = [$kelasTJKTX1, $g8, $mapels['KIM'], true];
        $assignments[] = [$kelasTJKTX2, $g8, $mapels['FIS'], true];
        $assignments[] = [$kelasTJKTX2, $g8, $mapels['KIM'], true];

        // Guru 9: Agus Prasetyo -> Matematika (TJKT)
        $g9 = $gurus[8];
        $assignments[] = [$kelasTJKTX1, $g9, $mapels['MTK-A'], true];
        $assignments[] = [$kelasTJKTX2, $g9, $mapels['MTK-A'], true];

        // Guru 10: Rina Wulandari -> PJOK, SBD
        $g10 = $gurus[9];
        $assignments[] = [$kelasPPLGX1, $g10, $mapels['SBD'], true];
        $assignments[] = [$kelasPPLGX1, $g10, $mapels['PJOK'], true];
        $assignments[] = [$kelasPPLGX2, $g10, $mapels['SBD'], true];
        $assignments[] = [$kelasPPLGX2, $g10, $mapels['PJOK'], true];
        $assignments[] = [$kelasTJKTX1, $g10, $mapels['PJOK'], true];
        $assignments[] = [$kelasTJKTX2, $g10, $mapels['PJOK'], true];

        // Insert using firstOrCreate to avoid duplicates
        foreach ($assignments as $a) {
            ClassGuruMapel::firstOrCreate([
                'class_id'        => $a[0]->id,
                'guru_id'         => $a[1]->id,
                'mapel_id'        => $a[2]->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
            ], [
                'is_primary' => $a[3],
            ]);
        }

        $this->command->info('✓ ' . count($assignments) . ' guru-mapel-kelas assignments berhasil dibuat');
    }
}
