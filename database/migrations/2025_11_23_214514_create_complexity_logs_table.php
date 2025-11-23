<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complexity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->decimal('old_score', 5, 2)->nullable();
            $table->decimal('new_score', 5, 2);
            $table->enum('change_type', ['auto_calculated', 'manual_override', 'recalculated'])->default('auto_calculated');
            $table->json('calculation_details')->nullable()->comment('Details of calculation factors');
            $table->unsignedBigInteger('changed_by')->nullable()->comment('Admin user_id who made change');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['order_item_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complexity_logs');
    }
};
