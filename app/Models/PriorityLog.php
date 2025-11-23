<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'old_score',
        'new_score',
        'factors',
        'trigger',
    ];

    protected $casts = [
        'factors' => 'array',
    ];

    // Relationships
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
