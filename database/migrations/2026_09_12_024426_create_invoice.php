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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete(); // Relasi ke pemesanan
            $table->string('invoice_number')->unique(); // Contoh: INV-202609-0001
            $table->date('issue_date');
            $table->date('due_date');
            
            // Kolom Finansial
            $table->decimal('subtotal', 15, 2); // Harga dasar sebelum pajak
            $table->decimal('service_charge', 15, 2)->default(0); 
            $table->decimal('tax_amount', 15, 2); // Total pajak
            $table->decimal('grand_total', 15, 2); // Total akhir yang harus dibayar
            $table->decimal('amount_paid', 15, 2)->default(0); // Total yang sudah masuk
            
            $table->enum('status', ['Unpaid', 'Paid', 'Cancelled','Partial'])->default('Unpaid');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('invoice');
    }
};
