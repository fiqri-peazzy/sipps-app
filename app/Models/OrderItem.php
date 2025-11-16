<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'produk_id',
        'quantity',
        'ukuran_kaos',
        'warna_kaos',
        'harga_satuan',
        'subtotal',
        'design_config', // JSON field
        'catatan_item',
        'priority_score',
        'deadline',
        'production_status',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'deadline' => 'datetime',
        'design_config' => 'array', // Cast to array
    ];

    // Helper methods
    public function hasDesign($area)
    {
        if (!$this->design_config) return false;
        return isset($this->design_config[$area]);
    }

    public function getDesignForArea($area)
    {
        return $this->design_config[$area] ?? null;
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Accessors
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }
}
