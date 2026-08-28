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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->enum('reff_feature', ['room-types', 'rooms', 'guests', 'facility', 'gallery','additional'])->default('room-types');
            $table->string('file_url');
            $table->string('file_name');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('reff_id');
            $table->double('file_size');
            $table->timestamps();
            $table->index(['reff_feature', 'reff_id'], 'idx_attachable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
