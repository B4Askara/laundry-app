<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanans';

    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pemesanan',
        'id_layanan',
        'berat_jumlah',
        'harga',
        'subtotal',
        'pakai_reward',
    ];

    protected $casts = [
        'pakai_reward' => 'boolean',
        'berat_jumlah' => 'decimal:2',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(
            Pemesanan::class,
            'id_pemesanan',
            'id_pemesanan'
        );
    }

    public function layanan()
    {
        return $this->belongsTo(
            Layanan::class,
            'id_layanan',
            'id_layanan'
        );
    }
}