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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_account_id')->constrained(); // Uang masuk/keluar dari dompet mana
    
            $table->date('transaction_date');
            $table->enum('transaction_type', ['income', 'expense']); 
            $table->decimal('amount', 15, 2);
            $table->string('description'); // cth: "Pelunasan Invoice #INV-001" atau "Beli Sabun"
            
            // (Opsional) Polymorphic relation jika ingin melacak referensi asal transaksi
            $table->nullableMorphs('reference'); // Menghasilkan reference_type dan reference_id
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
        Schema::dropIfExists('financial_transactions');
    }
};
