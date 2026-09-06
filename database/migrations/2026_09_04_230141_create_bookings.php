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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code');
            $table->foreignId('guest_id')->constrained('guests');
            $table->enum('book_reff',['direct_walkin','direct_wa','ota']);
            $table->string('ota_name')->nullable();
            $table->string('external_booking_id')->nullable();
            $table->enum('payment_method',["Cash","Bank Transfer", "QRIS"])->default('Cash');
            $table->enum('booking_status',["Pending", "Approved", "Cancelled", "Completed"])->default('Pending');
            $table->enum('payment_status',["Pending", "Unpaid", "Paid", "Refund","Part Paid"])->default('Unpaid');
            $table->double("subtotal")->default(0);
            $table->double("tax")->default(0);
            $table->double("total_payment")->default(0);
            $table->double("amount_paid")->default(0);
            $table->double("amount_refunded")->default(0);
            $table->double("down_payment")->default(0);
            $table->double("discount_amount")->default(0);
            $table->timestamp("paid_at")->nullable();
            $table->timestamp("down_payment_paid_at")->nullable();
            $table->string('note')->nullable();
            $table->foreignId('promo_id')->nullable()->constrained('promos');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers');
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
        Schema::dropIfExists('bookings');
    }
};
