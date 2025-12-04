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
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_return_item')->default(false)->after('production_status');
            $table->foreignId('parent_item_id')->nullable()->constrained('order_items')->onDelete('set null')->after('is_return_item');
            $table->text('return_reason')->nullable()->after('parent_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //

            $table->dropForeign(['parent_item_id']);
            $table->dropColumn(['is_return_item', 'parent_item_id', 'return_reason']);
        });
    }
};
