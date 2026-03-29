<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Percobaan Kuis (Quiz Attempts)
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete()->comment('FK ke tabel quizzes');
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (siswa)');
            $table->integer('skor')->nullable()->comment('Skor total');
            $table->integer('total_benar')->default(0)->comment('Total jawaban benar');
            $table->integer('total_salah')->default(0)->comment('Total jawaban salah');
            $table->integer('total_soal')->default(0)->comment('Total soal yang dijawab');
            $table->dateTime('waktu_mulai')->nullable()->comment('Waktu mulai mengerjakan');
            $table->dateTime('waktu_selesai')->nullable()->comment('Waktu selesai mengerjakan');
            $table->integer('durasi_detik')->nullable()->comment('Durasi pengerjaan dalam detik');
            $table->enum('status', ['menunggu', 'dikerjakan', 'selesai', 'belum_mulai'])->default('belum_mulai')->comment('Status percobaan');
            $table->json('answers')->nullable()->comment('Jawaban siswa dalam format JSON');
            $table->timestamps();

            $table->unique(['quiz_id', 'siswa_id'], 'quiz_attempt_quiz_siswa_unique');

            $table->index('siswa_id');
            $table->index('status');
            $table->index('skor');
            $table->index(['siswa_id', 'status'], 'quiz_attempt_siswa_status_index');
            $table->index('waktu_mulai');
            $table->index('waktu_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
