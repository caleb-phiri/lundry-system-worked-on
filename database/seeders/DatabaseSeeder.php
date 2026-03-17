<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Basket;
use App\Models\SpecialItem;
use App\Models\Location;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@laundry.com',
            'phone' => '+260977123456',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);
        Wallet::create(['user_id' => $admin->id]);

        // Create test drivers
        for ($i = 1; $i <= 3; $i++) {
            $driver = User::create([
                'name' => "Driver {$i}",
                'email' => "driver{$i}@laundry.com",
                'phone' => "+26097712345{$i}",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'is_active' => true
            ]);
            Wallet::create(['user_id' => $driver->id]);
        }

        // Create test clients
        for ($i = 1; $i <= 5; $i++) {
            $client = User::create([
                'name' => "Client {$i}",
                'email' => "client{$i}@laundry.com",
                'phone' => "+2609771234{$i}{$i}",
                'password' => Hash::make('password'),
                'role' => 'client',
                'is_active' => true
            ]);
            $client->generateReferralCode();
            Wallet::create(['user_id' => $client->id]);

            // Add locations for clients
            Location::create([
                'user_id' => $client->id,
                'name' => 'Home',
                'address' => "123 Main Street, Lusaka",
                'latitude' => -15.3875259,
                'longitude' => 28.3228165,
                'contact_phone' => $client->phone,
                'instructions' => 'Ring the bell',
                'is_default' => true
            ]);
        }

        // Create baskets
        $baskets = [
            ['name' => 'Small Basket', 'size' => 'small', 'base_price' => 50.00, 'capacity_kg' => 5],
            ['name' => 'Medium Basket', 'size' => 'medium', 'base_price' => 80.00, 'capacity_kg' => 10],
            ['name' => 'Large Basket', 'size' => 'large', 'base_price' => 120.00, 'capacity_kg' => 15],
        ];

        foreach ($baskets as $basket) {
            Basket::create($basket);
        }

        // Create special items
        $specialItems = [
            ['name' => 'Bedding Set', 'category' => 'bedding', 'surcharge' => 30.00],
            ['name' => 'Suit', 'category' => 'suits', 'surcharge' => 25.00],
            ['name' => 'Curtains', 'category' => 'curtains', 'surcharge' => 40.00],
            ['name' => 'Duvet', 'category' => 'bedding', 'surcharge' => 35.00],
            ['name' => 'Formal Suit', 'category' => 'suits', 'surcharge' => 30.00],
        ];

        foreach ($specialItems as $item) {
            SpecialItem::create($item);
        }
    }
}