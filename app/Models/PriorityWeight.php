<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weight_urgency',
        'weight_complexity',
        'weight_waiting_time',
        'weight_quantity',
        'is_active',
        'description',
    ];

    protected $casts = [
        'weight_urgency' => 'decimal:2',
        'weight_complexity' => 'decimal:2',
        'weight_waiting_time' => 'decimal:2',
        'weight_quantity' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Get active weight configuration
    public static function getActive()
    {
        return self::where('is_active', true)->first()
            ?? self::where('name', 'default')->first();
    }

    // Validate weights sum to 1.0
    public function validateWeights()
    {
        $sum = $this->weight_urgency +
            $this->weight_complexity +
            $this->weight_waiting_time +
            $this->weight_quantity;

        return abs($sum - 1.0) < 0.01; // Toleransi 0.01
    }
}
