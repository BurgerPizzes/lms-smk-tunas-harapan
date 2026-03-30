<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama lengkap pengguna');
            $table->string('email')->unique()->comment('Email pengguna');
            $table->timestamp('email_verified_at')->nullable()->comment('Waktu verifikasi email');
            $table->string('password')->comment('Password ter-hash');
            $table->string('nip', 50)->unique()->nullable()->comment('NIP untuk guru');
            $table->string('nis', 50)->unique()->nullable()->comment('NIS untuk siswa');
            $table->string('nisn', 50)->unique()->nullable()->comment('NISN untuk siswa');
            $table->unsignedBigInteger('kelas_id')->nullable()->comment('FK ke tabel kelas');
            $table->unsignedBigInteger('jurusan_id')->nullable()->comment('FK ke tabel jurusan');
            $table->unsignedBigInteger('tahun_ajaran_id')->nullable()->comment('FK ke tabel tahun ajaran');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable()->comment('Jenis kelamin');
            $table->string('tempat_lahir')->nullable()->comment('Tempat lahir');
            $table->date('tanggal_lahir')->nullable()->comment('Tanggal lahir');
            $table->text('alamat')->nullable()->comment('Alamat lengkap');
            $table->string('no_hp', 20)->nullable()->comment('Nomor handphone');
            $table->string('foto')->nullable()->comment('Path foto profil');
            $table->boolean('is_active')->default(true)->comment('Status aktif pengguna');
            $table->timestamp('last_login_at')->nullable()->comment('Waktu login terakhir');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('jenis_kelamin');
            $table->index(['kelas_id', 'jurusan_id', 'tahun_ajaran_id'], 'user_class_jurusan_ta_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
