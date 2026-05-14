<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Update enum status (Raw SQL agar aman di MySQL/MariaDB)
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
                'pending_payment', 
                'paid', 
                'verified', 
                'in_production', 
                'ready_to_ship', 
                'shipped', 
                'completed', 
                'cancelled', 
                'cancel_requested',
                'return_requested', 
                'returned'
            ) DEFAULT 'pending_payment'");

            $table->string('refund_proof')->nullable()->after('cancel_reason');
            $table->timestamp('refunded_at')->nullable()->after('refund_proof');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['refund_proof', 'refunded_at']);
            
            // Kembalikan enum status ke awal
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
                'pending_payment', 
                'paid', 
                'verified', 
                'in_production', 
                'ready_to_ship', 
                'shipped', 
                'completed', 
                'cancelled', 
                'return_requested', 
                'returned'
            ) DEFAULT 'pending_payment'");
        });
    }
};
