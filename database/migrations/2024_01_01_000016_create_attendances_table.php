<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Absensi (Attendance sessions)
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('kelas')->cascadeOnDelete()->comment('FK ke tabel kelas');
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete()->comment('FK ke tabel mapels');
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (guru pengajar)');
            $table->date('tanggal')->comment('Tanggal absensi');
            $table->integer('pertemuan_ke')->comment('Pertemuan ke-berapa');
            $table->text('catatan')->nullable()->comment('Catatan tambahan dari guru');
            $table->timestamps();

            $table->unique(['class_id', 'mapel_id', 'tanggal'], 'attendance_class_mapel_tanggal_unique');

            $table->index('guru_id');
            $table->index('tanggal');
            $table->index(['class_id', 'tanggal'], 'attendance_class_tanggal_index');
            $table->index(['guru_id', 'tanggal'], 'attendance_guru_tanggal_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
