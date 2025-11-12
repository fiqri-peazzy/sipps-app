<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSablon extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
