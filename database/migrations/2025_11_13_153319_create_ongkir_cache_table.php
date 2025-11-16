<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ongkir_cache', function (Blueprint $table) {
            $table->id();
            $table->string('origin', 10); // Kota asal (Gorontalo)
            $table->string('destination', 10); // Kota tujuan
            $table->string('kurir', 50); // JNE, JNT, dll
            $table->string('service', 25); // REG, YES, OKE
            $table->decimal('cost', 12, 2);
            $table->integer('estimasi_hari');
            $table->timestamp('expired_at'); // Cache expired setelah X hari
            $table->timestamps();

            $table->index(['origin', 'destination', 'kurir', 'service']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ongkir_cache');
    }
};
