<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengambilan extends Model
{
    use HasFactory;

    protected $table = 'pengambilans';

    protected $primaryKey = 'id_pengambilan';

    protected $fillable = [
        'jenis_pengambilan',
        'alamat',
        'ongkir',
    ];
}