<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('transaction_id');
            $table->string('payment_type');
            $table->decimal('gross_amount', 12, 2);
            $table->string('transaction_status');
            $table->string('fraud_status')->nullable();
            $table->json('midtrans_response')->nullable(); // Store full response dari Midtrans
            $table->timestamps();

            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
