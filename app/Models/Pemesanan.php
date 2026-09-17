<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Pengambilan;
use App\Models\Reward;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';

    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_user',
        'id_pelanggan',
        'id_layanan',
        'id_pengambilan',
        'id_reward',
        'berat_jumlah',
        'subtotal',
        'ongkir',
        'total_harga',
        'tanggal',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(
            Pelanggan::class,
            'id_pelanggan',
            'id_pelanggan'
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

    public function pengambilan()
    {
        return $this->belongsTo(
            Pengambilan::class,
            'id_pengambilan',
            'id_pengambilan'
        );
    }

    public function reward()
    {
        return $this->belongsTo(
            Reward::class,
            'id_reward',
            'id_reward'
        );
    }

    public function detailPemesanan()
    {
        return $this->hasMany(
            DetailPemesanan::class,
            'id_pemesanan',
            'id_pemesanan'
        );
    }

    // Relasi ke detail pemesanan
    public function details()
    {
        return $this->hasMany(
            DetailPemesanan::class,
            'id_pemesanan',
            'id_pemesanan'
        );
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pemesanan',
            'id_pemesanan'
        );
    }
}