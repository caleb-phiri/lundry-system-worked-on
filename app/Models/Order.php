<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'client_id', 'driver_id', 'basket_id', 'pickup_location_id',
        'delivery_location_id', 'subtotal', 'surcharges', 'discount', 'total',
        'wallet_used', 'payment_method', 'payment_type', 'payment_status',
        'transaction_reference', 'status', 'pickup_scheduled_at', 'pickup_completed_at',
        'delivery_scheduled_at', 'delivery_completed_at', 'special_instructions',
        'special_items', 'tracking_history', 'is_subscription', 'cancelled_at',
        'rating', 'review', 'rated_at'
    ];

    protected $casts = [
        'pickup_scheduled_at' => 'datetime',
        'pickup_completed_at' => 'datetime',
        'delivery_scheduled_at' => 'datetime',
        'delivery_completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rated_at' => 'datetime',
        'special_items' => 'array',
        'tracking_history' => 'array',  // This ensures it's cast to array
        'subtotal' => 'decimal:2',
        'surcharges' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'wallet_used' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function deliveryLocation()
    {
        return $this->belongsTo(Location::class, 'delivery_location_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateStatus($newStatus, $userId = null)
    {
        $oldStatus = $this->status;
        
        // Update timestamps based on status
        switch ($newStatus) {
            case 'collected':
                $this->pickup_completed_at = now();
                break;
            case 'delivered':
                $this->delivery_completed_at = now();
                break;
            case 'cancelled':
                $this->cancelled_at = now();
                break;
        }
        
        $this->status = $newStatus;
        $this->save();

        // Track history
        $history = $this->tracking_history ?? [];
        if (!is_array($history)) {
            $history = json_decode($history, true) ?? [];
        }
        
        $history[] = [
            'status' => $newStatus,
            'old_status' => $oldStatus,
            'changed_by' => $userId,
            'changed_at' => now()->toDateTimeString()
        ];
        
        $this->tracking_history = $history;
        $this->save();

        return $this;
    }

    public function canBeDelivered()
    {
        if ($this->payment_type === 'postpaid' && $this->payment_status !== 'paid') {
            return false;
        }
        return true;
    }
}