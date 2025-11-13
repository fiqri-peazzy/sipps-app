<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->string('file_desain')->nullable();
            $table->text('catatan_item')->nullable();

            // Untuk Dynamic Priority Scheduling
            $table->integer('priority_score')->default(0); // Score prioritas
            $table->timestamp('deadline')->nullable(); // Deadline produksi
            $table->enum('production_status', [
                'waiting',
                'in_queue',
                'in_progress',
                'completed'
            ])->default('waiting');

            $table->timestamps();

            $table->index('priority_score');
            $table->index('production_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
