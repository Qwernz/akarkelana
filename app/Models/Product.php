<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi melalui Mass Assignment.
     * Kita hapus 'price', 'stock', dan 'weight' karena data tersebut
     * sekarang dikelola oleh Model ProductVariant.
     */
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    /**
     * Relasi ke ProductVariant (Satu produk memiliki banyak varian berat/harga).
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
