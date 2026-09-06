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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('description');
            $table->enum('type',['percentage','fixed']);
            $table->double('value')->default(0);
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->default(0);
            $table->integer('usage_count')->default(0);
            $table->integer('max_discount')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
