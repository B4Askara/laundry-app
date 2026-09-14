<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards';

    protected $primaryKey = 'id_reward';

    protected $fillable = [
        'id_layanan',
        'nama_reward',
        'minimal_stempel',
        'keterangan',
    ];

    protected $casts = [
        'minimal_stempel' => 'integer',
    ];

    public function layanan()
    {
        return $this->belongsTo(
            Layanan::class,
            'id_layanan',
            'id_layanan'
        );
    }
}