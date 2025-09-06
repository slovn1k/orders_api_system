<?php

namespace App\Models;

use Illuminate\Container\Attributes\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = ['order_number', 'status', 'total_amount'];
    protected $casts = [
        'status' => '',
        'total_amount' => 'decimal:2',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'orders_tags_mapping')
            ->withPivot('added_time')
            ->withTimestamps();
    }

    public function items()
    {
        return $this->belongsToMany(OrderItems::class);
    }
}
