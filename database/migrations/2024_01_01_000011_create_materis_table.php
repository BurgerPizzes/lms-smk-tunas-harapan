<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Materi (Materials / Learning Resources)
     */
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('kelas')->cascadeOnDelete()->comment('FK ke tabel kelas');
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete()->comment('FK ke tabel mapels');
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (guru pembuat)');
            $table->string('judul')->comment('Judul materi');
            $table->text('deskripsi')->nullable()->comment('Deskripsi singkat materi');
            $table->longText('konten')->nullable()->comment('Konten materi (rich text/HTML)');
            $table->enum('tipe', ['file', 'video', 'text', 'link'])->default('text')->comment('Tipe materi');
            $table->string('file_path')->nullable()->comment('Path file materi');
            $table->string('video_url')->nullable()->comment('URL video materi');
            $table->integer('pertemuan_ke')->nullable()->comment('Pertemuan ke-berapa');
            $table->date('tanggal_dibuat')->nullable()->comment('Tanggal dibuat/diupload');
            $table->boolean('is_published')->default(false)->comment('Status publish materi');
            $table->integer('urutan')->default(0)->comment('Urutan tampilan materi');
            $table->timestamps();

            $table->index(['class_id', 'mapel_id'], 'materi_class_mapel_index');
            $table->index('guru_id');
            $table->index('is_published');
            $table->index(['class_id', 'mapel_id', 'is_published'], 'materi_class_mapel_published_index');
            $table->index(['class_id', 'mapel_id', 'pertemuan_ke'], 'materi_class_mapel_pertemuan_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
