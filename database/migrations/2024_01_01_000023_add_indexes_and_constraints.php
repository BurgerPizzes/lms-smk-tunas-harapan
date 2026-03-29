<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan index tambahan untuk optimasi performa
     * pada kolom-kolom yang sering di-query.
     */
    public function up(): void
    {
        // --- users ---
        Schema::table('users', function (Blueprint $table) {
            $table->index('name', 'users_name_index');
            $table->index('created_at', 'users_created_at_index');
            $table->index(['jurusan_id', 'tingkat'], 'users_jurusan_tingkat_index');
        });

        // --- kelas ---
        Schema::table('kelas', function (Blueprint $table) {
            // Composite index untuk mencari kelas aktif berdasarkan jurusan dan tahun ajaran
            $table->index(['jurusan_id', 'tahun_ajaran_id', 'is_active'], 'kelas_jurusan_ta_active_index');
            $table->index('created_at', 'kelas_created_at_index');
        });

        // --- mapels ---
        Schema::table('mapels', function (Blueprint $table) {
            $table->index(['is_active', 'semester'], 'mapels_active_semester_index');
            $table->index('nama', 'mapels_nama_index');
        });

        // --- tahun_ajarans ---
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->index(['aktif', 'semester'], 'tahun_ajarans_active_semester_index');
        });

        // --- materi ---
        Schema::table('materis', function (Blueprint $table) {
            $table->index('tipe', 'materis_tipe_index');
            $table->index('created_at', 'materis_created_at_index');
        });

        // --- tugas ---
        Schema::table('tugas', function (Blueprint $table) {
            $table->index('tipe', 'tugas_tipe_index');
            $table->index('created_at', 'tugas_created_at_index');
        });

        // --- submissions ---
        Schema::table('submissions', function (Blueprint $table) {
            $table->index('nilai', 'submissions_nilai_index');
            $table->index('created_at', 'submissions_created_at_index');
            $table->index('updated_at', 'submissions_updated_at_index');
        });

        // --- comments ---
        Schema::table('comments', function (Blueprint $table) {
            $table->index('created_at', 'comments_created_at_index');
        });

        // --- attendances ---
        Schema::table('attendances', function (Blueprint $table) {
            $table->index('created_at', 'attendances_created_at_index');
        });

        // --- attendance_details ---
        Schema::table('attendance_details', function (Blueprint $table) {
            $table->index('created_at', 'attendance_details_created_at_index');
            $table->index(['attendance_id', 'status'], 'attendance_details_attendance_status_index');
        });

        // --- announcements ---
        Schema::table('announcements', function (Blueprint $table) {
            $table->index('created_at', 'announcements_created_at_index');
        });

        // --- quizzes ---
        Schema::table('quizzes', function (Blueprint $table) {
            $table->index('created_at', 'quizzes_created_at_index');
        });

        // --- quiz_questions ---
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->index('poin', 'quiz_questions_poin_index');
        });

        // --- quiz_attempts ---
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index('created_at', 'quiz_attempts_created_at_index');
            $table->index('updated_at', 'quiz_attempts_updated_at_index');
        });

        // --- activity_logs ---
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('ip_address', 'activity_logs_ip_index');
        });

        // --- notifications ---
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('type', 'notifications_type_index');
        });

        // --- class_guru_mapel ---
        Schema::table('class_guru_mapel', function (Blueprint $table) {
            $table->index('is_primary', 'cgm_is_primary_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // --- users ---
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['jurusan_id', 'tingkat']);
        });

        // --- kelas ---
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropIndex(['jurusan_id', 'tahun_ajaran_id', 'is_active']);
            $table->dropIndex(['created_at']);
        });

        // --- mapels ---
        Schema::table('mapels', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'semester']);
            $table->dropIndex(['nama']);
        });

        // --- tahun_ajarans ---
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->dropIndex(['aktif', 'semester']);
        });

        // --- materi ---
        Schema::table('materis', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropIndex(['created_at']);
        });

        // --- tugas ---
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropIndex(['created_at']);
        });

        // --- submissions ---
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex(['nilai']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
        });

        // --- comments ---
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        // --- attendances ---
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        // --- attendance_details ---
        Schema::table('attendance_details', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['attendance_id', 'status']);
        });

        // --- announcements ---
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        // --- quizzes ---
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        // --- quiz_questions ---
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropIndex(['poin']);
        });

        // --- quiz_attempts ---
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
        });

        // --- activity_logs ---
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
        });

        // --- notifications ---
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        // --- class_guru_mapel ---
        Schema::table('class_guru_mapel', function (Blueprint $table) {
            $table->dropIndex(['is_primary']);
        });
    }
};
