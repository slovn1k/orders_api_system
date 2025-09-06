<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = [
        'order_id',
        'product_name',
        'quantity',
        'price',
    ];

    protected $casts = ['price' => 'decimal:2'];

    public function order()
    {
        return $this->belongsToMany(Order::class);
    }
}
