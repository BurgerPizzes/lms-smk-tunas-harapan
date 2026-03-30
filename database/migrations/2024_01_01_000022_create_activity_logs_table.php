<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Log Aktivitas (Activity Logs)
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('FK ke tabel users, null untuk sistem/anonymous');
            $table->string('model_type')->nullable()->comment('Tipe model yang terpengaruh');
            $table->unsignedBigInteger('model_id')->nullable()->comment('ID model yang terpengaruh');
            $table->string('action')->comment('Aksi yang dilakukan, contoh: created, updated, deleted');
            $table->text('description')->nullable()->comment('Deskripsi aktivitas');
            $table->json('properties')->nullable()->comment('Data tambahan aktivitas (JSON)');
            $table->string('ip_address', 45)->nullable()->comment('Alamat IP pengguna');
            $table->string('user_agent')->nullable()->comment('Browser user agent');
            $table->timestamps();

            $table->index('user_id');
            $table->index(['model_type', 'model_id'], 'activity_log_model_index');
            $table->index('action');
            $table->index('created_at');
            $table->index(['user_id', 'action'], 'activity_log_user_action_index');
            $table->index(['user_id', 'created_at'], 'activity_log_user_time_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
