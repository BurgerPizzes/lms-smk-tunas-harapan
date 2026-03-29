<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Soal Kuis (Quiz Questions)
     */
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete()->comment('FK ke tabel quizzes');
            $table->text('pertanyaan')->comment('Teks pertanyaan');
            $table->enum('tipe', ['pilihan_ganda', 'essay', 'true_false'])->default('pilihan_ganda')->comment('Tipe soal');
            $table->text('pilihan_a')->nullable()->comment('Pilihan jawaban A');
            $table->text('pilihan_b')->nullable()->comment('Pilihan jawaban B');
            $table->text('pilihan_c')->nullable()->comment('Pilihan jawaban C');
            $table->text('pilihan_d')->nullable()->comment('Pilihan jawaban D');
            $table->text('pilihan_e')->nullable()->comment('Pilihan jawaban E');
            $table->text('jawaban_benar')->comment('Jawaban yang benar');
            $table->text('pembahasan')->nullable()->comment('Pembahasan jawaban');
            $table->integer('poin')->default(10)->comment('Poin per soal');
            $table->integer('urutan')->default(0)->comment('Urutan tampilan soal');
            $table->timestamps();

            $table->index('quiz_id');
            $table->index('tipe');
            $table->index(['quiz_id', 'urutan'], 'quiz_question_quiz_urutan_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
