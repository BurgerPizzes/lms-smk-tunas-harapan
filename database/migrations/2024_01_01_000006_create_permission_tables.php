<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Spatie laravel-permission: permissions, model_has_permissions, role_has_permissions
     */
    public function up(): void
    {
        // --- permissions ---
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nama permission');
            $table->string('display_name')->nullable()->comment('Nama tampilan permission');
            $table->string('guard_name')->default('web')->comment('Guard yang digunakan');
            $table->string('description')->nullable()->comment('Deskripsi permission');
            $table->timestamps();

            $table->index(['guard_name'], 'permissions_guard_name_index');
        });

        // --- model_has_permissions ---
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete()->comment('FK ke tabel permissions');

            $table->string('model_type')->comment('Tipe model');
            $table->unsignedBigInteger('model_id')->comment('ID model pengguna');

            $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');

            $table->foreign('model_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
        });

        // --- role_has_permissions ---
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete()->comment('FK ke tabel permissions');
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete()->comment('FK ke tabel roles');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_role_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('permissions');
    }
};
