<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplexityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'old_score',
        'new_score',
        'change_type',
        'calculation_details',
        'changed_by',
        'notes',
    ];

    protected $casts = [
        'old_score' => 'decimal:2',
        'new_score' => 'decimal:2',
        'calculation_details' => 'array',
    ];

    // Relationships
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
