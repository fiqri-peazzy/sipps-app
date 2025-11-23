<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Complexity scoring
            $table->decimal('complexity_score', 5, 2)->default(0)->after('priority_score')
                ->comment('Final complexity score (hybrid)');
            $table->decimal('auto_complexity_score', 5, 2)->default(0)->after('complexity_score')
                ->comment('Auto-calculated complexity');
            $table->decimal('manual_complexity_score', 5, 2)->nullable()->after('auto_complexity_score')
                ->comment('Manual override by admin');
            $table->unsignedBigInteger('complexity_reviewed_by')->nullable()->after('manual_complexity_score');
            $table->timestamp('complexity_reviewed_at')->nullable()->after('complexity_reviewed_by');

            // Tracking fields
            $table->integer('waiting_time_hours')->default(0)->after('complexity_reviewed_at')
                ->comment('Hours waiting in queue');
            $table->timestamp('production_started_at')->nullable()->after('waiting_time_hours');
            $table->integer('returned_count')->default(0)->after('production_started_at')
                ->comment('How many times returned to queue');
            $table->timestamp('last_priority_calculated_at')->nullable()->after('returned_count');

            // Foreign key
            $table->foreign('complexity_reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['complexity_reviewed_by']);
            $table->dropColumn([
                'complexity_score',
                'auto_complexity_score',
                'manual_complexity_score',
                'complexity_reviewed_by',
                'complexity_reviewed_at',
                'waiting_time_hours',
                'production_started_at',
                'returned_count',
                'last_priority_calculated_at',
            ]);
        });
    }
};
