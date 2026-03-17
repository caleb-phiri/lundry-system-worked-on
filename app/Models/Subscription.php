<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'frequency', 'basket_size', 'price',
        'preferences', 'next_pickup_date', 'last_pickup_date', 'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'preferences' => 'array',
        'next_pickup_date' => 'datetime',
        'last_pickup_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}