<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'surcharge', 'description', 'is_active'
    ];

    protected $casts = [
        'surcharge' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_special_items')
                    ->withPivot('quantity', 'surcharge_applied')
                    ->withTimestamps();
    }
}