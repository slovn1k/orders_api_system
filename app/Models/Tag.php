<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];
    protected array $dates = ['created_at', 'updated_at'];

    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('added_time')
            ->withTimestamps();
    }
}
