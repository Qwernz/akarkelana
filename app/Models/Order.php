<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Tambahkan baris fillable ini agar data pembeli bisa masuk
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'note',
        'total_price',
        'snap_token',
        'status',
        'rating',
        'review',
        'user_id'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
