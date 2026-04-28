<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBakuLog extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar kolom-kolom ini diizinkan untuk disimpan
    protected $fillable = [
        'nama_bahan',
        'jumlah_beli',
        'harga_beli',
        'lokasi',
    ];
}
