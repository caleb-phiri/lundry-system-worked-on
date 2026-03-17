<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'size', 'base_price', 'capacity_kg', 'description', 'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'capacity_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}