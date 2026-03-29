<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Pivot table: kelas <=> guru <=> mata pelajaran per tahun ajaran
     */
    public function up(): void
    {
        Schema::create('class_guru_mapel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('kelas')->cascadeOnDelete()->comment('FK ke tabel kelas');
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete()->comment('FK ke tabel users (guru)');
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete()->comment('FK ke tabel mapels');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete()->comment('FK ke tabel tahun ajaran');
            $table->boolean('is_primary')->default(true)->comment('Guru pengampu utama');
            $table->timestamps();

            $table->unique(['class_id', 'guru_id', 'mapel_id', 'tahun_ajaran_id'], 'cgm_unique');

            $table->index(['guru_id', 'tahun_ajaran_id'], 'cgm_guru_ta_index');
            $table->index(['class_id', 'mapel_id', 'tahun_ajaran_id'], 'cgm_class_mapel_ta_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_guru_mapel');
    }
};
