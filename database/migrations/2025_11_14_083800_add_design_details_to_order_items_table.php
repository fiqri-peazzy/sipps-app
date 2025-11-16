<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Remove old single file design
            $table->dropColumn('file_desain');

            // Add JSON column untuk multiple designs
            $table->json('design_config')->nullable()->after('subtotal');
            // Structure contoh:
            // {
            //   "front": {
            //     "type": "image",
            //     "file": "path/to/file.png",
            //     "position": {"x": 50, "y": 100},
            //     "size": {"width": 200, "height": 200},
            //     "rotation": 0
            //   },
            //   "back": {
            //     "type": "text",
            //     "text": "RONALDO 7",
            //     "position": {"x": 150, "y": 50},
            //     "font": "Arial",
            //     "fontSize": 48,
            //     "color": "#FFFFFF"
            //   }
            // }

            // Ukuran kaos
            $table->enum('ukuran_kaos', ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'])->nullable()->after('quantity');

            // Warna kaos
            $table->string('warna_kaos')->nullable()->after('ukuran_kaos');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['design_config', 'ukuran_kaos', 'warna_kaos']);
            $table->string('file_desain')->nullable();
        });
    }
};
