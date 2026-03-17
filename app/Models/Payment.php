<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'amount', 'payment_method', 'payment_type',
        'transaction_id', 'provider_reference', 'status', 'provider_response', 'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'provider_response' => 'array',
        'metadata' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}