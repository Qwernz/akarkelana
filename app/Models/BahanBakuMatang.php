<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBakuMatang extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan kolom diisi
    protected $fillable = [
        'nama_biji',
        'stok_kg',
    ];
}
