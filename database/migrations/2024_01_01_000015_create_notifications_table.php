<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Notifikasi (Notifications)
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (penerima notifikasi)');
            $table->string('type')->comment('Tipe notifikasi (class name)');
            $table->string('title')->comment('Judul notifikasi');
            $table->text('message')->comment('Isi pesan notifikasi');
            $table->json('data')->nullable()->comment('Data tambahan notifikasi (JSON)');
            $table->boolean('is_read')->default(false)->comment('Status sudah dibaca');
            $table->timestamp('read_at')->nullable()->comment('Waktu dibaca');
            $table->string('link')->nullable()->comment('Link tujuan notifikasi');
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_read');
            $table->index(['user_id', 'is_read'], 'notifications_user_read_index');
            $table->index(['user_id', 'is_read', 'created_at'], 'notifications_user_read_created_index');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
