<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Status Order
            $table->enum('status', [
                'pending_payment',      // Menunggu pembayaran
                'paid',                 // Sudah dibayar
                'verified',             // Diverifikasi admin
                'in_production',        // Sedang produksi
                'ready_to_ship',        // Siap dikirim
                'shipped',              // Sedang dikirim
                'completed',            // Selesai
                'cancelled',            // Dibatalkan
                'return_requested',     // Ajuan return
                'returned'              // Dikembalikan
            ])->default('pending_payment');

            // Pricing
            $table->decimal('subtotal', 12, 2); // Total harga produk
            $table->decimal('ongkir', 12, 2)->default(0); // Ongkos kirim
            $table->decimal('total_harga', 12, 2); // Subtotal + Ongkir
            $table->integer('total_item');
            $table->text('catatan')->nullable();

            // MIDTRANS PAYMENT
            $table->enum('metode_pembayaran', [
                'credit_card',
                'bank_transfer',
                'gopay',
                'shopeepay',
                'qris',
                'alfamart',
                'indomaret',
                'cod' // Cash on delivery untuk dalam kota
            ])->nullable();
            $table->string('snap_token')->nullable(); // Midtrans snap token
            $table->string('transaction_id')->nullable(); // Midtrans transaction ID
            $table->enum('payment_status', [
                'pending',
                'settlement',
                'capture',
                'deny',
                'cancel',
                'expire',
                'failure'
            ])->default('pending');
            $table->timestamp('payment_expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            // SHIPPING INFO
            $table->enum('tipe_pengiriman', ['dalam_kota', 'antar_kota'])->nullable();
            $table->string('kurir')->nullable(); // JNE, JNT, SiCepat, Kurir Lokal, dll
            $table->string('service_kurir')->nullable(); // REG, YES, OKE, dll
            $table->string('resi')->nullable(); // Nomor resi
            $table->integer('estimasi_pengiriman')->nullable(); // dalam hari
            $table->enum('status_pengiriman', [
                'pending',
                'picked_up',
                'in_transit',
                'delivered',
                'returned'
            ])->default('pending');

            // Alamat Pengiriman
            $table->string('penerima_nama');
            $table->string('penerima_telepon');
            $table->text('alamat_lengkap');
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota');
            $table->string('provinsi')->default('Gorontalo');
            $table->string('kode_pos')->nullable();

            // Timestamps
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('order_number');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
