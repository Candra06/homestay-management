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
        Schema::create('tax_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->unique()->constrained()->cascadeOnDelete();
            
            $table->string('nsfp', 16)->unique(); // Nomor Seri Faktur Pajak
            $table->string('buyer_npwp', 20)->nullable();
            $table->string('buyer_nik', 16)->nullable();
            $table->string('buyer_name'); 
            $table->text('buyer_address');
            
            $table->enum('djp_status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_invoices');
    }
};
