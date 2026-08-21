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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_menu_id')->constrained('groups_menu')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->string('url');
            $table->enum('have_list',['Y','N']);
            $table->enum('have_create',['Y','N']);
            $table->enum('have_edit',['Y','N']);
            $table->enum('have_delete',['Y','N']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
