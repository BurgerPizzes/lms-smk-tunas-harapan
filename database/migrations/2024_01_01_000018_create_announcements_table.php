<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Pengumuman (Announcements)
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->nullable()->constrained('kelas')->cascadeOnDelete()->comment('FK ke kelas, null = pengumuman global');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (pembuat pengumuman)');
            $table->string('judul')->comment('Judul pengumuman');
            $table->longText('konten')->comment('Konten pengumuman');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->comment('Prioritas pengumuman');
            $table->boolean('is_published')->default(false)->comment('Status publish pengumuman');
            $table->timestamp('published_at')->nullable()->comment('Waktu publish pengumuman');
            $table->timestamps();

            $table->index('user_id');
            $table->index('priority');
            $table->index('is_published');
            $table->index('published_at');
            $table->index(['class_id', 'is_published'], 'announcement_class_published_index');
            $table->index(['is_published', 'published_at'], 'announcement_published_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
