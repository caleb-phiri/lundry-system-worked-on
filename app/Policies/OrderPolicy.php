<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function view(User $user, Order $order)
    {
        return $user->id === $order->client_id || 
               $user->id === $order->driver_id || 
               $user->role === 'admin';
    }

    public function updateStatus(User $user, Order $order)
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'driver' && $user->id === $order->driver_id) {
            return in_array($order->status, [
                'driver_assigned',
                'collected',
                'out_for_delivery'
            ]);
        }

        return false;
    }

    public function assignDriver(User $user, Order $order)
    {
        return $user->role === 'admin';
    }
}