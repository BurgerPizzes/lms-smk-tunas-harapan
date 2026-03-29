<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Detail Absensi (Attendance details per siswa)
     */
    public function up(): void
    {
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->cascadeOnDelete()->comment('FK ke tabel attendances');
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (siswa)');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir')->comment('Status kehadiran');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan');
            $table->timestamps();

            $table->unique(['attendance_id', 'siswa_id'], 'attendance_detail_attendance_siswa_unique');

            $table->index('siswa_id');
            $table->index('status');
            $table->index(['siswa_id', 'status'], 'attendance_detail_siswa_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
};
