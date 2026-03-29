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
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique()->comment('Kode jurusan, contoh: PPLG');
            $table->string('nama')->comment('Nama jurusan, contoh: Pemrograman Perangkat Lunak dan Gim');
            $table->text('deskripsi')->nullable()->comment('Deskripsi jurusan');
            $table->boolean('aktif')->default(true)->comment('Status aktif jurusan');
            $table->timestamps();

            $table->index('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusans');
    }
};
