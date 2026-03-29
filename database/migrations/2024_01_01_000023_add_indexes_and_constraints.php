<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // ADD FOREIGN KEYS (after all tables are created)
        // ============================================================

        // users → jurusan, tahun_ajaran, kelas
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('jurusan_id')->references('id')->on('jurusans')->nullOnDelete();
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajarans')->nullOnDelete();
            $table->foreign('kelas_id')->references('id')->on('kelas')->nullOnDelete();
        });

        // kelas → jurusan, tahun_ajaran, users (guru_id)
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign('jurusan_id')->references('id')->on('jurusans')->cascadeOnDelete();
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajarans')->cascadeOnDelete();
            $table->foreign('guru_id')->references('id')->on('users')->nullOnDelete();
        });

        // ============================================================
        // ADD EXTRA INDEXES FOR PERFORMANCE
        // ============================================================

        Schema::table('users', function (Blueprint $table) {
            $table->index('name', 'users_name_index');
            $table->index('created_at', 'users_created_at_index');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->index(['jurusan_id', 'tahun_ajaran_id', 'is_active'], 'kelas_jurusan_ta_active_index');
            $table->index('created_at', 'kelas_created_at_index');
        });

        Schema::table('mapels', function (Blueprint $table) {
            $table->index(['is_active', 'semester'], 'mapels_active_semester_index');
            $table->index('nama', 'mapels_nama_index');
        });

        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->index(['aktif', 'semester'], 'tahun_ajarans_active_semester_index');
        });

        Schema::table('materis', function (Blueprint $table) {
            $table->index('tipe', 'materis_tipe_index');
            $table->index('created_at', 'materis_created_at_index');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->index('tipe', 'tugas_tipe_index');
            $table->index('deadline', 'tugas_deadline_index');
            $table->index('created_at', 'tugas_created_at_index');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->index('nilai', 'submissions_nilai_index');
            $table->index('created_at', 'submissions_created_at_index');
            $table->index('updated_at', 'submissions_updated_at_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index('created_at', 'comments_created_at_index');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->index('created_at', 'attendances_created_at_index');
        });

        Schema::table('attendance_details', function (Blueprint $table) {
            $table->index('created_at', 'attendance_details_created_at_index');
            $table->index(['attendance_id', 'status'], 'attendance_details_attendance_status_index');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->index('created_at', 'announcements_created_at_index');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->index('created_at', 'quizzes_created_at_index');
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->index('poin', 'quiz_questions_poin_index');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index('created_at', 'quiz_attempts_created_at_index');
            $table->index('updated_at', 'quiz_attempts_updated_at_index');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('ip_address', 'activity_logs_ip_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('type', 'notifications_type_index');
        });

        Schema::table('class_guru_mapel', function (Blueprint $table) {
            $table->index('is_primary', 'cgm_is_primary_index');
        });
    }

    public function down(): void
    {
        // Drop foreign keys first
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropIndex(['name']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['guru_id']);
            $table->dropIndex(['jurusan_id', 'tahun_ajaran_id', 'is_active']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('mapels', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'semester']);
            $table->dropIndex(['nama']);
        });

        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->dropIndex(['aktif', 'semester']);
        });

        Schema::table('materis', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropIndex(['deadline']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex(['nilai']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('attendance_details', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['attendance_id', 'status']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropIndex(['poin']);
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('class_guru_mapel', function (Blueprint $table) {
            $table->dropIndex(['is_primary']);
        });
    }
};
