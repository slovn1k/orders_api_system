<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\OrderStatus;
use App\Models\Tag;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = ['order_number', 'status', 'total_amount'];
    protected $casts = [
        'status' => OrderStatus::class,
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
        return $this->hasMany(OrderItems::class);
    }
}
