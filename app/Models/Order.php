<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'ongkir',
        'total_harga',
        'total_item',
        'catatan',
        'metode_pembayaran',
        'snap_token',
        'transaction_id',
        'payment_status',
        'payment_expired_at',
        'paid_at',
        'tipe_pengiriman',
        'kurir',
        'service_kurir',
        'resi',
        'estimasi_pengiriman',
        'status_pengiriman',
        'penerima_nama',
        'penerima_telepon',
        'alamat_lengkap',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'verified_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'payment_expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function shippingTrackings()
    {
        return $this->hasMany(ShippingTracking::class);
    }

    // Accessors
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedOngkirAttribute()
    {
        return 'Rp ' . number_format($this->ongkir, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending_payment' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'verified' => 'Diverifikasi',
            'in_production' => 'Sedang Produksi',
            'ready_to_ship' => 'Siap Dikirim',
            'shipped' => 'Sedang Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'return_requested' => 'Ajuan Return',
            'returned' => 'Dikembalikan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending_payment' => 'warning',
            'paid' => 'info',
            'verified' => 'primary',
            'in_production' => 'secondary',
            'ready_to_ship' => 'info',
            'shipped' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'return_requested' => 'warning',
            'returned' => 'dark',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // Static method untuk generate order number
    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now())->latest()->first();
        $number = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;

        return 'ORD-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
