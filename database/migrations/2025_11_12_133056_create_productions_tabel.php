<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_sablons', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ukurans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_sablon_id')->constrained('jenis_sablons')->onDelete('cascade');
            $table->foreignId('ukuran_id')->constrained('ukurans')->onDelete('cascade');
            $table->enum('tipe_layanan', ['regular', 'express']);
            $table->decimal('harga', 10, 2);
            $table->integer('estimasi_waktu');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
        Schema::dropIfExists('ukurans');
        Schema::dropIfExists('jenis_sablons');
    }
};
