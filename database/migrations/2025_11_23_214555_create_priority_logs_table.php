<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('priority_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->integer('old_score')->nullable();
            $table->integer('new_score');
            $table->json('factors')->comment('Breakdown of priority calculation');
            $table->enum('trigger', ['order_verified', 'scheduled_update', 'manual_recalc', 'after_return'])->default('scheduled_update');
            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');

            $table->index(['order_item_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('priority_logs');
    }
};
