<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Pivot table for Spatie laravel-permission: model_has_roles
     */
    public function up(): void
    {
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete()->comment('FK ke tabel roles');

            $table->string('model_type')->comment('Tipe model (App\\Models\\User)');
            $table->unsignedBigInteger('model_id')->comment('ID model pengguna');

            $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');

            $table->foreign('model_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_roles');
    }
};
