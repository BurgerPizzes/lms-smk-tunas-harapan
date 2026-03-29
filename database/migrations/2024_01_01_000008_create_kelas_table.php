<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->comment('Nama kelas, contoh: PPLG X-1');
            $table->unsignedBigInteger('jurusan_id')->comment('FK ke jurusan');
            $table->unsignedBigInteger('tahun_ajaran_id')->comment('FK ke tahun ajaran');
            $table->tinyInteger('tingkat')->comment('Tingkat kelas: 10, 11, atau 12');
            $table->string('ruangan')->nullable()->comment('Nama/nomor ruangan');
            $table->integer('kapasitas')->default(36)->comment('Kapasitas maksimal siswa');
            $table->string('kode_unik', 6)->unique()->comment('Kode unik 6 karakter untuk join');
            $table->text('deskripsi')->nullable()->comment('Deskripsi kelas');
            $table->boolean('is_active')->default(true)->comment('Status aktif kelas');
            $table->string('cover_image')->nullable()->comment('Path gambar cover kelas');
            $table->unsignedBigInteger('guru_id')->nullable()->comment('FK ke users (wali kelas)');
            $table->timestamps();

            $table->index('is_active');
            $table->index('tingkat');
            $table->index(['jurusan_id', 'tahun_ajaran_id'], 'kelas_jurusan_ta_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
