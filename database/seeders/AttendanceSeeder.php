<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates 5 attendance sessions for PPLG X-1 with details for all siswa.
     */
    public function run(): void
    {
        $kelasPPLGX1 = Kelas::where('nama', 'PPLG X-1')->first();
        $mapelPD     = Mapel::where('kode', 'PD-01')->first();
        $mapelPW     = Mapel::where('kode', 'PW-01')->first();

        // Get guru who teaches PD-01 in PPLG X-1
        $guruPD = User::whereHas('roles', fn($q) => $q->where('name', 'guru'))
            ->where('name', 'like', 'Budi%')
            ->first();

        $guruPW = $guruPD; // Same guru teaches both

        $siswas = $kelasPPLGX1->siswas()->orderBy('id')->get();

        if ($siswas->isEmpty()) {
            $this->command->warn('Tidak ada siswa di PPLG X-1, skip attendance seeding.');
            return;
        }

        $statusOptions = ['hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha'];
        $keteranganOptions = [
            'izin'  => ['Acara keluarga', 'Keperluan mendesak', 'Izin orang tua', 'Keperluan dokter'],
            'sakit' => ['Demam', 'Sakit perut', 'Sakit kepala', 'Flu'],
            'alpha' => null,
        ];

        $sessions = [
            [
                'mapel' => $mapelPD, 'guru' => $guruPD,
                'tanggal' => '2024-08-05', 'pertemuan_ke' => 1,
                'catatan' => 'Pertemuan pertama semester ganjil',
            ],
            [
                'mapel' => $mapelPD, 'guru' => $guruPD,
                'tanggal' => '2024-08-12', 'pertemuan_ke' => 2,
                'catatan' => 'Materi variabel dan tipe data',
            ],
            [
                'mapel' => $mapelPW, 'guru' => $guruPW,
                'tanggal' => '2024-08-07', 'pertemuan_ke' => 1,
                'catatan' => 'Pengenalan HTML5',
            ],
            [
                'mapel' => $mapelPW, 'guru' => $guruPW,
                'tanggal' => '2024-08-14', 'pertemuan_ke' => 2,
                'catatan' => 'CSS3 Styling dasar',
            ],
            [
                'mapel' => $mapelPD, 'guru' => $guruPD,
                'tanggal' => '2024-08-19', 'pertemuan_ke' => 3,
                'catatan' => 'Percabangan if-else',
            ],
        ];

        $totalDetails = 0;

        foreach ($sessions as $session) {
            $attendance = Attendance::create([
                'class_id'      => $kelasPPLGX1->id,
                'mapel_id'      => $session['mapel']->id,
                'guru_id'       => $session['guru']->id,
                'tanggal'       => $session['tanggal'],
                'pertemuan_ke'  => $session['pertemuan_ke'],
                'catatan'       => $session['catatan'],
            ]);

            // Generate attendance for each siswa
            foreach ($siswas as $siswa) {
                $status = $statusOptions[mt_rand(0, count($statusOptions) - 1)];
                $keterangan = null;

                if ($status === 'izin' || $status === 'sakit') {
                    $keteranganOptionsForStatus = $keteranganOptions[$status] ?? [];
                    $keterangan = $keteranganOptionsForStatus[mt_rand(0, count($keteranganOptionsForStatus) - 1)] ?? null;
                }

                AttendanceDetail::create([
                    'attendance_id' => $attendance->id,
                    'siswa_id'      => $siswa->id,
                    'status'        => $status,
                    'keterangan'    => $keterangan,
                ]);

                $totalDetails++;
            }
        }

        $this->command->info("✓ 5 attendance sessions dengan {$totalDetails} detail berhasil dibuat untuk PPLG X-1");
    }
}
