<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Run all seeders in proper dependency order with foreign key handling.
     */
    public function run(): void
    {
        // Disable lazy loading prevention during seeding.
        // Seeders access relationships (e.g. $tugas->kelas->siswas) without
        // eager loading, which triggers violations when Model::shouldBeStrict()
        // is enabled in AppServiceProvider.
        Model::preventLazyLoading(false);

        $this->command->info('🔄 Memulai seeding database LMS SMK Tunas Harapan...');

        // Disable foreign key checks to allow truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('🗑️  Mengosongkan tabel...');

        // Truncate tables in reverse dependency order
        $tables = [
            'quiz_attempts',
            'quiz_questions',
            'quizzes',
            'comments',
            'attendance_details',
            'attendances',
            'notifications',
            'announcements',
            'submissions',
            'tugas',
            'materis',
            'class_guru_mapel',
            'class_user',
            'activity_logs',
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions',
            'users',
            'mapels',
            'kelas',
            'tahun_ajarans',
            'jurusans',
            'permissions',
            'roles',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Tabel berhasil dikosongkan.');
        $this->command->info('');

        // ─── Seed in dependency order ───────────────────────────────
        $this->command->info('📦 Menjalankan seeder...');

        // 1. Roles (no dependencies)
        $this->call([
            RoleSeeder::class,
        ]);

        // 2. Jurusan & Tahun Ajaran (no dependencies)
        $this->call([
            JurusanSeeder::class,
            TahunAjaranSeeder::class,
        ]);

        // 3. Users (depends on roles, jurusan, tahun_ajaran)
        $this->call([
            UserSeeder::class,
        ]);

        // 4. Mapel (depends on jurusan)
        $this->call([
            MapelSeeder::class,
        ]);

        // 5. Kelas (depends on jurusan, tahun_ajaran, users/guru)
        //    This also enrolls siswa via class_user pivot
        $this->call([
            KelasSeeder::class,
        ]);

        // 6. ClassGuruMapel (depends on kelas, users/guru, mapel, tahun_ajaran)
        $this->call([
            ClassGuruMapelSeeder::class,
        ]);

        // 7. Materi (depends on kelas, mapel, guru)
        $this->call([
            MateriSeeder::class,
        ]);

        // 8. Tugas (depends on kelas, mapel, guru)
        $this->call([
            TugasSeeder::class,
        ]);

        // 9. Submissions (depends on tugas, siswa)
        $this->call([
            SubmissionSeeder::class,
        ]);

        // 10. Notifications (depends on users, tugas, materi)
        $this->call([
            NotificationSeeder::class,
        ]);

        // 11. Attendance (depends on kelas, mapel, guru, siswa)
        $this->call([
            AttendanceSeeder::class,
        ]);

        // 12. Comments (depends on materi, tugas, users)
        $this->call([
            CommentSeeder::class,
        ]);

        // 13. Announcements (depends on users, kelas)
        $this->call([
            AnnouncementSeeder::class,
        ]);

        // 14. Quizzes (depends on kelas, mapel, guru, siswa)
        $this->call([
            QuizSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Semua seeder berhasil dijalankan!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📧 Admin : admin@smktunas.sch.id / password');
        $this->command->info('👨‍🏫 Guru  : guru1-10@smktunas.sch.id / password123');
        $this->command->info('👨‍🎓 Siswa : siswa1-50@smktunas.sch.id / password123');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
