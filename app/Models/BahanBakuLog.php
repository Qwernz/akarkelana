<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBakuLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bahan_baku_id',
        'nama_bahan',
        'jumlah_beli',
        'harga_beli',
        'lokasi'
    ];
}
