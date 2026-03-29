<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kuis / Ujian (Quizzes)
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->nullable()->constrained('tugas')->cascadeOnDelete()->comment('FK ke tabel tugas (opsional)');
            $table->foreignId('class_id')->constrained('kelas')->cascadeOnDelete()->comment('FK ke tabel kelas');
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete()->comment('FK ke tabel mapels');
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (guru pembuat)');
            $table->string('judul')->comment('Judul kuis');
            $table->text('deskripsi')->nullable()->comment('Deskripsi kuis');
            $table->integer('durasi_menit')->default(60)->comment('Durasi pengerjaan dalam menit');
            $table->integer('jumlah_soal')->default(10)->comment('Jumlah soal');
            $table->boolean('random_soal')->default(false)->comment('Acak urutan soal');
            $table->boolean('show_result')->default(true)->comment('Tampilkan hasil setelah selesai');
            $table->boolean('is_published')->default(false)->comment('Status publish kuis');
            $table->dateTime('mulai_at')->nullable()->comment('Waktu mulai kuis');
            $table->dateTime('selesai_at')->nullable()->comment('Waktu selesai kuis');
            $table->timestamps();

            $table->index(['class_id', 'mapel_id'], 'quiz_class_mapel_index');
            $table->index('guru_id');
            $table->index('is_published');
            $table->index(['mulai_at', 'selesai_at'], 'quiz_time_range_index');
            $table->index(['class_id', 'is_published'], 'quiz_class_published_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
