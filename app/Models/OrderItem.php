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

        'is_return_item',
        'parent_item_id',
        'return_reason',
    ];

    protected $casts = [
        'harga_satuan'                  => 'decimal:2',
        'subtotal'                      => 'decimal:2',
        'deadline'                      => 'datetime',
        'design_config'                 => 'array',
        'complexity_reviewed_at'        => 'datetime',
        'production_started_at'         => 'datetime',
        'last_priority_calculated_at'   => 'datetime',
        'is_return_item'                => 'boolean'
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

    public function complexityLogs()
    {
        return $this->hasMany(ComplexityLog::class);
    }

    public function priorityLogs()
    {
        return $this->hasMany(PriorityLog::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function complexityReviewedBy()
    {
        return $this->belongsTo(User::class, 'complexity_reviewed_by');
    }

    public function customerReturns()
    {
        return $this->hasMany(CustomerReturn::class, 'order_item_id');
    }

    public function hasCustomerReturn()
    {
        return $this->customerReturns()->exists();
    }

    public function parentItem()
    {
        return $this->belongsTo(OrderItem::class, 'parent_item_id');
    }
    public function returnItems()
    {
        return $this->hasMany(OrderItem::class, 'parent_item_id');
    }


    // Helper method untuk cek apakah sudah direview
    public function hasComplexityReview()
    {
        return !is_null($this->manual_complexity_score);
    }

    // Get latest complexity log
    public function getLatestComplexityLog()
    {
        return $this->complexityLogs()->latest()->first();
    }

    // Get latest priority log
    public function getLatestPriorityLog()
    {
        return $this->priorityLogs()->latest()->first();
    }

    // Check if has pending return request
    public function hasPendingReturn()
    {
        return $this->returnRequests()->where('status', 'pending')->exists();
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