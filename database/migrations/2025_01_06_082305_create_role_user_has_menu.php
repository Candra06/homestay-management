<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_user_has_menu', function (Blueprint $table) {
            $table->id();
            $table->integer('id_role');
            $table->integer('id_menu');
            $table->enum('access_list',['Y','N'])->nullable();
            $table->enum('access_create',['Y','N'])->nullable();
            $table->enum('access_edit',['Y','N'])->nullable();
            $table->enum('access_delete',['Y','N'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user_has_menu');
    }
};
