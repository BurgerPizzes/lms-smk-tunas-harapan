<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // ADD FOREIGN KEYS (after all tables exist)
        // ============================================================

        // users -> jurusan, tahun_ajaran, kelas
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('jurusan_id')->references('id')->on('jurusans')->nullOnDelete();
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajarans')->nullOnDelete();
            $table->foreign('kelas_id')->references('id')->on('kelas')->nullOnDelete();
        });

        // kelas -> jurusan, tahun_ajaran, users (guru_id)
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign('jurusan_id')->references('id')->on('jurusans')->cascadeOnDelete();
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajarans')->cascadeOnDelete();
            $table->foreign('guru_id')->references('id')->on('users')->nullOnDelete();
        });

        // ============================================================
        // ADD EXTRA INDEXES (only non-duplicate ones)
        // ============================================================

        Schema::table('users', function (Blueprint $table) {
            $table->index('name', 'users_name_index');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->index(['jurusan_id', 'tahun_ajaran_id', 'is_active'], 'kelas_jurusan_ta_active_index');
        });

        Schema::table('mapels', function (Blueprint $table) {
            $table->index('nama', 'mapels_nama_index');
        });

        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->index(['aktif', 'semester'], 'tahun_ajarans_active_semester_index');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->index('nilai', 'submissions_nilai_index');
        });

        Schema::table('class_guru_mapel', function (Blueprint $table) {
            $table->index('is_primary', 'cgm_is_primary_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropIndex('users_name_index');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['guru_id']);
            $table->dropIndex('kelas_jurusan_ta_active_index');
        });

        Schema::table('mapels', function (Blueprint $table) {
            $table->dropIndex('mapels_nama_index');
        });

        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->dropIndex('tahun_ajarans_active_semester_index');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex('submissions_nilai_index');
        });

        Schema::table('class_guru_mapel', function (Blueprint $table) {
            $table->dropIndex('cgm_is_primary_index');
        });
    }
};
