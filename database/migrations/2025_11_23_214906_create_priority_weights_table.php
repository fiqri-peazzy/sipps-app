<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('priority_weights', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('weight_urgency', 3, 2)->default(0.40)->comment('Weight for deadline urgency');
            $table->decimal('weight_complexity', 3, 2)->default(0.30)->comment('Weight for design complexity');
            $table->decimal('weight_waiting_time', 3, 2)->default(0.20)->comment('Weight for waiting time');
            $table->decimal('weight_quantity', 3, 2)->default(0.10)->comment('Weight for order quantity');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default weights
        DB::table('priority_weights')->insert([
            'name' => 'default',
            'weight_urgency' => 0.40,
            'weight_complexity' => 0.30,
            'weight_waiting_time' => 0.20,
            'weight_quantity' => 0.10,
            'is_active' => true,
            'description' => 'Default priority weights for Dynamic Priority Scheduling',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('priority_weights');
    }
};
