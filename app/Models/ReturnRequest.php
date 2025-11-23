<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'requested_by',
        'reason_type',
        'reason_description',
        'status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
