<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
'order_id', 
    'product_id',
    'product_name', 
    'weight', 
    'quantity', 
    'price'
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Product (PENTING: Agar bisa ambil nama Biji Kopi)
    // app/Models/OrderItem.php
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
