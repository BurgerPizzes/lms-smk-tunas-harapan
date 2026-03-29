<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Pivot table: kelas <=> siswa (users)
     */
    public function up(): void
    {
        Schema::create('class_user', function (Blueprint $table) {
            $table->foreignId('class_id')->constrained('kelas')->cascadeOnDelete()->comment('FK ke tabel kelas');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (siswa)');
            $table->timestamp('joined_at')->useCurrent()->comment('Waktu bergabung ke kelas');
            $table->timestamps();

            $table->unique(['class_id', 'user_id'], 'class_user_unique');

            $table->index('joined_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_user');
    }
};
