<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    // Tambahkan ini supaya bisa input data ke kolom-kolomnya
    protected $fillable = [
        'nama_bahan', 
        'stok_kg', 
        'total_biaya_pengeluaran'
    ];
}
