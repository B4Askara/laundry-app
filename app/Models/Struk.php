<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    use HasFactory;

    protected $table = 'struks';
    protected $primaryKey = 'id_struk';

    protected $fillable = [
        'id_pembayaran',
        'nomor_struk',
        'tanggal_cetak',
    ];

    protected $casts = [
        'tanggal_cetak' => 'datetime',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(
            Pembayaran::class,
            'id_pembayaran',
            'id_pembayaran'
        );
    }
}