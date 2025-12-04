<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'reason',
        'reason_detail',
        'evidence_photos',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
        'replacement_order_item_id',
        'priority_boost',
    ];

    protected $casts = [
        'evidence_photos' => 'array',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function replacementItem()
    {
        return $this->belongsTo(OrderItem::class, 'replacement_order_item_id');
    }

    // Accessors
    public function getReasonLabelAttribute()
    {
        $labels = [
            'wrong_size' => 'Ukuran Salah',
            'wrong_color' => 'Warna Tidak Sesuai',
            'print_quality' => 'Kualitas Cetakan Buruk',
            'damage' => 'Produk Rusak/Cacat',
            'not_as_described' => 'Tidak Sesuai Deskripsi',
            'other' => 'Lainnya',
        ];
        return $labels[$this->reason] ?? $this->reason;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'completed' => 'success',
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}
