<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_sablon_id',
        'ukuran_id',
        'tipe_layanan',
        'harga',
        'estimasi_waktu',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'harga' => 'decimal:2',
    ];

    public function jenisSablon()
    {
        return $this->belongsTo(JenisSablon::class);
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class);
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getEstimasiHariAttribute()
    {
        return $this->estimasi_waktu / 24;
    }

    public function getTipeLayananLabelAttribute()
    {
        return $this->tipe_layanan === 'regular' ? 'Regular' : 'Express';
    }
}
