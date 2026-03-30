<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Pengumpulan Tugas (Submissions)
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->cascadeOnDelete()->comment('FK ke tabel tugas');
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (siswa)');
            $table->text('konten')->nullable()->comment('Jawaban dalam bentuk teks');
            $table->string('file_path')->nullable()->comment('Path file yang diupload siswa');
            $table->dateTime('submitted_at')->nullable()->comment('Waktu pengumpulan');
            $table->enum('status', ['submitted', 'late', 'not_submitted'])->default('not_submitted')->comment('Status pengumpulan');
            $table->integer('nilai')->nullable()->comment('Nilai yang diberikan guru');
            $table->text('feedback')->nullable()->comment('Feedback dari guru');
            $table->text('catatan_guru')->nullable()->comment('Catatan tambahan dari guru');
            $table->timestamps();

            $table->unique(['tugas_id', 'siswa_id'], 'submission_tugas_siswa_unique');

            $table->index('status');
            $table->index('siswa_id');
            $table->index('submitted_at');
            $table->index(['siswa_id', 'status'], 'submission_siswa_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
