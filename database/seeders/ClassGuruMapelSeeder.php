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
    /**
     * Run the database seeds.
     *
     * Assigns guru to teach mapel in specific kelas.
     * Each guru teaches 2-4 mapel in 1-3 kelas.
     */
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

        // Mapel lookups
        $mapels = Mapel::all()->keyBy('kode');

        $assignments = [];

        // ─── Guru 1: Budi Santoso → Pemrograman Dasar, Pemrograman Web (PPLG X-1, X-2) ──
        $g1 = $gurus[0];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g1, 'mapel' => $mapels['PD-01'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g1, 'mapel' => $mapels['PW-01'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX2, 'guru' => $g1, 'mapel' => $mapels['PD-01'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX2, 'guru' => $g1, 'mapel' => $mapels['PW-01'], 'primary' => true];

        // ─── Guru 2: Siti Nurhaliza → Bahasa Indonesia, PKN (semua PPLG X) ──
        $g2 = $gurus[1];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g2, 'mapel' => $mapels['PKN'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX2, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY1, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => false];

        // ─── Guru 3: Ahmad Hidayat → Bahasa Inggris, Matematika (PPLG XI, XII) ──
        $g3 = $gurus[2];
        $assignments[] = ['class' => $kelasPPLGY1, 'guru' => $g3, 'mapel' => $mapels['BIG'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY1, 'guru' => $g3, 'mapel' => $mapels['MTK'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY2, 'guru' => $g3, 'mapel' => $mapels['BIG'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ1, 'guru' => $g3, 'mapel' => $mapels['BIG-A'], 'primary' => true];

        // ─── Guru 4: Dewi Kartika → PBO, Basis Data (PPLG XI) ──
        $g4 = $gurus[3];
        $assignments[] = ['class' => $kelasPPLGY1, 'guru' => $g4, 'mapel' => $mapels['PBO-01'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY1, 'guru' => $g4, 'mapel' => $mapels['BD-01'],  'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY2, 'guru' => $g4, 'mapel' => $mapels['PBO-01'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY2, 'guru' => $g4, 'mapel' => $mapels['BD-01'],  'primary' => true];

        // ─── Guru 5: Rahmat Widodo → Pemrograman Mobile, Game Dev (PPLG XII) ──
        $g5 = $gurus[4];
        $assignments[] = ['class' => $kelasPPLGZ1, 'guru' => $g5, 'mapel' => $mapels['PM-01'],  'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ1, 'guru' => $g5, 'mapel' => $mapels['GD-01'],  'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ2, 'guru' => $g5, 'mapel' => $mapels['PM-01'],  'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ2, 'guru' => $g5, 'mapel' => $mapels['DVC-01'], 'primary' => true];

        // ─── Guru 6: Endang Sulistyowati → PAI, Pendidikan Pancasila (PPLG X, XI) ──
        $g6 = $gurus[5];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g6, 'mapel' => $mapels['PAI'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g6, 'mapel' => $mapels['P5'],    'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX2, 'guru' => $g6, 'mapel' => $mapels['PAI'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY1, 'guru' => $g6, 'mapel' => $mapels['PAI'],   'primary' => false];
        $assignments[] = ['class' => $kelasPPLGY2, 'guru' => $g6, 'mapel' => $mapels['P5'],    'primary' => true];

        // ─── Guru 7: Hendra Gunawan → Desain UI/UX, DevOps (PPLG XI, XII) ──
        $g7 = $gurus[6];
        $assignments[] = ['class' => $kelasPPLGY1, 'guru' => $g7, 'mapel' => $mapels['UIX-01'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGY2, 'guru' => $g7, 'mapel' => $mapels['UIX-01'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ1, 'guru' => $g7, 'mapel' => $mapels['DVC-01'], 'primary' => true];

        // ─── Guru 8: Yuliana Puspitasari → Fisika, Kimia (TJKT X) ──
        $g8 = $gurus[7];
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g8, 'mapel' => $mapels['FIS'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g8, 'mapel' => $mapels['KIM'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX2, 'guru' => $g8, 'mapel' => $mapels['FIS'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX2, 'guru' => $g8, 'mapel' => $mapels['KIM'],   'primary' => true];

        // ─── Guru 9: Agus Prasetyo → Matematika Lanjut, Biologi (TJKT X, XI) ──
        $g9 = $gurus[8];
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g9, 'mapel' => $mapels['MTK-A'], 'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g9, 'mapel' => $mapels['BIO'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX2, 'guru' => $g9, 'mapel' => $mapels['MTK-A'], 'primary' => true];
        $assignments[] = ['class' => $kelasTJKTY1, 'guru' => $g9, 'mapel' => $mapels['MTK-A'], 'primary' => true];

        // ─── Guru 10: Rina Wulandari → Seni Budaya, PJOK, Matematika (semua kelas) ──
        $g10 = $gurus[9];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g10, 'mapel' => $mapels['SBD'],  'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX1, 'guru' => $g10, 'mapel' => $mapels['PJOK'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGX2, 'guru' => $g10, 'mapel' => $mapels['SBD'],  'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g10, 'mapel' => $mapels['PJOK'], 'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX2, 'guru' => $g10, 'mapel' => $mapels['PJOK'], 'primary' => true];

        // ─── Also assign some normatif to TJKT ──
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g3, 'mapel' => $mapels['BIG'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX2, 'guru' => $g3, 'mapel' => $mapels['BIG'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX2, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX1, 'guru' => $g6, 'mapel' => $mapels['PAI'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTX2, 'guru' => $g6, 'mapel' => $mapels['PAI'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTY1, 'guru' => $g6, 'mapel' => $mapels['PAI'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTY1, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => true];
        $assignments[] = ['class' => $kelasTJKTY1, 'guru' => $g3, 'mapel' => $mapels['BIG'],   'primary' => true];

        // ─── PPLG XII normatif / adaptif ──
        $assignments[] = ['class' => $kelasPPLGZ1, 'guru' => $g3, 'mapel' => $mapels['BIG-A'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ2, 'guru' => $g3, 'mapel' => $mapels['BIG-A'], 'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ1, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ2, 'guru' => $g2, 'mapel' => $mapels['BIN'],   'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ1, 'guru' => $g6, 'mapel' => $mapels['P5'],    'primary' => true];
        $assignments[] = ['class' => $kelasPPLGZ2, 'guru' => $g6, 'mapel' => $mapels['PAI'],   'primary' => true];

        // Insert all assignments
        foreach ($assignments as $a) {
            ClassGuruMapel::create([
                'class_id'        => $a['class']->id,
                'guru_id'         => $a['guru']->id,
                'mapel_id'        => $a['mapel']->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'is_primary'      => $a['primary'],
            ]);
        }

        $this->command->info('✓ ' . count($assignments) . ' guru-mapel-kelas assignments berhasil dibuat');
    }
}
