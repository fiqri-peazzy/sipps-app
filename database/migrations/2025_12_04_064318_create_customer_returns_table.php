<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Return Info
            $table->enum('reason', [
                'wrong_size',
                'wrong_color',
                'print_quality',
                'damage',
                'not_as_described',
                'other'
            ]);
            $table->text('reason_detail');

            // Bukti Foto (wajib)
            $table->json('evidence_photos'); // Array path foto

            // Status & Review
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();

            // Replacement Item (setelah approved)
            $table->foreignId('replacement_order_item_id')->nullable()->constrained('order_items')->onDelete('set null');
            $table->integer('priority_boost')->default(50)->comment('Bonus priority untuk return item');

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index(['order_id', 'status']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_returns');
    }
};
