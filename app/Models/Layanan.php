<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans';

    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'laundry_id',
        'nama_layanan',
        'kategori',
        'harga',
        'satuan',
    ];

    public function laundry(): BelongsTo
    {
        return $this->belongsTo(Laundry::class, 'laundry_id');
    }
}
