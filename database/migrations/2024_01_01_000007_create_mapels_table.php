<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mata Pelajaran (Subjects)
     */
    public function up(): void
    {
        Schema::create('mapels', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique()->comment('Kode mata pelajaran');
            $table->string('nama')->comment('Nama mata pelajaran');
            $table->text('deskripsi')->nullable()->comment('Deskripsi mata pelajaran');
            $table->enum('kategori', ['normatif', 'adaptif', 'produktif'])->comment('Kategori: normatif/adaptif/produktif');
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->nullOnDelete()->comment('FK ke jurusan, null = semua jurusan');
            $table->integer('semester')->comment('Semester mata pelajaran');
            $table->integer('kkm')->default(75)->comment('Kriteria Ketuntasan Minimal');
            $table->boolean('is_active')->default(true)->comment('Status aktif mata pelajaran');
            $table->timestamps();

            $table->index('kategori');
            $table->index('is_active');
            $table->index(['jurusan_id', 'semester'], 'mapel_jurusan_semester_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapels');
    }
};
