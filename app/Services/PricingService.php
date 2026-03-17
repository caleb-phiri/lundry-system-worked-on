<?php

namespace App\Services;

use App\Models\Basket;
use App\Models\SpecialItem;
use Illuminate\Support\Collection;

class PricingService
{
    public function calculatePrice(Basket $basket, Collection $specialItems)
    {
        $subtotal = $basket->base_price;
        $surcharges = 0;

        foreach ($specialItems as $item) {
            $surcharges += $item->surcharge;
        }

        $total = $subtotal + $surcharges;

        return [
            'subtotal' => $subtotal,
            'surcharges' => $surcharges,
            'total' => $total,
            'basket' => [
                'name' => $basket->name,
                'size' => $basket->size,
                'base_price' => $basket->base_price
            ],
            'special_items' => $specialItems->map(function($item) {
                return [
                    'name' => $item->name,
                    'category' => $item->category,
                    'surcharge' => $item->surcharge
                ];
            })
        ];
    }
}