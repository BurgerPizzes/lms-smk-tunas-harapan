<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tugas (Assignments)
     */
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('kelas')->cascadeOnDelete()->comment('FK ke tabel kelas');
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete()->comment('FK ke tabel mapels');
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (guru pembuat)');
            $table->string('judul')->comment('Judul tugas');
            $table->longText('deskripsi')->nullable()->comment('Deskripsi tugas');
            $table->text('instruksi')->nullable()->comment('Instruksi pengerjaan tugas');
            $table->string('file_attachment')->nullable()->comment('Path file lampiran tugas');
            $table->dateTime('deadline')->nullable()->comment('Batas waktu pengumpulan');
            $table->enum('tipe', ['tugas', 'proyek', 'quiz', 'ujian'])->default('tugas')->comment('Tipe tugas');
            $table->integer('nilai_maks')->default(100)->comment('Nilai maksimal');
            $table->boolean('allow_late')->default(false)->comment('Izinkan pengumpulan terlambat');
            $table->boolean('is_published')->default(false)->comment('Status publish tugas');
            $table->timestamps();

            $table->index(['class_id', 'mapel_id'], 'tugas_class_mapel_index');
            $table->index('guru_id');
            $table->index('deadline');
            $table->index('is_published');
            $table->index(['class_id', 'mapel_id', 'is_published'], 'tugas_class_mapel_published_index');
            $table->index(['class_id', 'mapel_id', 'deadline'], 'tugas_class_mapel_deadline_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
