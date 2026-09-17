<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pemesanan',
        'jumlah_bayar',
        'metode_pembayaran',
        'tanggal_pembayaran',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(
            Pemesanan::class,
            'id_pemesanan',
            'id_pemesanan'
        );
    }

    public function struk()
    {
        return $this->hasOne(
            Struk::class,
            'id_pembayaran',
            'id_pembayaran'
        );
    }
}