<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Komentar - Polymorphic (materi / tugas / announcement)
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commentable_id')->comment('ID entitas yang dikomentari');
            $table->string('commentable_type')->comment('Tipe entitas: materi/tugas/announcement');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (pembuat komentar)');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete()->comment('FK ke komentar induk (untuk balasan)');
            $table->text('body')->comment('Isi komentar');
            $table->boolean('is_edited')->default(false)->comment('Apakah komentar pernah diedit');
            $table->timestamps();

            $table->index(['commentable_id', 'commentable_type'], 'comments_commentable_index');
            $table->index('user_id');
            $table->index('parent_id');
            $table->index(['commentable_id', 'commentable_type', 'parent_id'], 'comments_commentable_parent_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
