<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards';

    protected $primaryKey = 'id_reward';

    protected $fillable = [
        'laundry_id',
        'id_layanan',
        'nama_reward',
        'minimal_stempel',
        'keterangan',
    ];

    protected $casts = [
        'minimal_stempel' => 'integer',
    ];

    public function laundry(): BelongsTo
    {
        return $this->belongsTo(Laundry::class, 'laundry_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(
            Layanan::class,
            'id_layanan',
            'id_layanan'
        );
    }
}
