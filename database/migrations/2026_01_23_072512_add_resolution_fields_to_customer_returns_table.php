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
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->string('resolution_type')->nullable()->after('admin_notes'); // 'replacement', 'refund'
            $table->decimal('refund_amount', 15, 2)->default(0)->after('resolution_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_returns', function (Blueprint $table) {
            //
        });
    }
};
