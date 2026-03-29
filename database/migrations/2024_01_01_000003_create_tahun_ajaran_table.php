<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->comment('Nama tahun ajaran, contoh: 2024/2025 Ganjil');
            $table->string('tahun_mulai', 4)->comment('Tahun mulai, contoh: 2024');
            $table->string('tahun_selesai', 4)->comment('Tahun selesai, contoh: 2025');
            $table->enum('semester', ['ganjil', 'genap'])->comment('Semester ganjil atau genap');
            $table->boolean('aktif')->default(true)->comment('Status aktif tahun ajaran');
            $table->timestamps();

            $table->unique(['tahun_mulai', 'tahun_selesai', 'semester'], 'tahun_ajaran_unique');
            $table->index('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajarans');
    }
};
