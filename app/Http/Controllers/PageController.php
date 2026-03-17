<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\SpecialItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function clientDashboard()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user is client
        if (Auth::user()->role !== 'client') {
            return redirect()->route(Auth::user()->role . '.dashboard');
        }
        
        $user = Auth::user();
        
        $baskets = Basket::where('is_active', true)->get();
        $specialItems = SpecialItem::where('is_active', true)->get();
        $recentOrders = Order::where('client_id', $user->id)
                            ->with(['basket', 'driver'])
                            ->latest()
                            ->limit(5)
                            ->get();
        
        $stats = [
            'total_orders' => Order::where('client_id', $user->id)->count(),
            'completed_orders' => Order::where('client_id', $user->id)
                                       ->where('status', 'delivered')
                                       ->count(),
            'total_spent' => Order::where('client_id', $user->id)
                                  ->where('payment_status', 'paid')
                                  ->sum('total'),
            'referral_earnings' => $user->wallet ? $user->wallet->transactions()
                                        ->where('description', 'like', '%referral%')
                                        ->sum('amount') : 0
        ];
        
        return view('client.dashboard', compact('baskets', 'specialItems', 'recentOrders', 'stats'));
    }
    
    public function driverDashboard()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user is driver
        if (Auth::user()->role !== 'driver') {
            return redirect()->route(Auth::user()->role . '.dashboard');
        }
        
        $user = Auth::user();
        
        $availableOrders = Order::whereNull('driver_id')
                                ->where('status', 'pickup_scheduled')
                                ->with(['client', 'pickupLocation'])
                                ->latest()
                                ->get();
        
        $activeDeliveries = Order::where('driver_id', $user->id)
                                 ->whereIn('status', ['driver_assigned', 'collected', 'out_for_delivery'])
                                 ->with(['client', 'pickupLocation', 'deliveryLocation'])
                                 ->get();
        
        $completedToday = Order::where('driver_id', $user->id)
                               ->where('status', 'delivered')
                               ->whereDate('delivery_completed_at', today())
                               ->count();
        
        return view('driver.dashboard', compact('availableOrders', 'activeDeliveries', 'completedToday'));
    }
    
    public function adminDashboard()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route(Auth::user()->role . '.dashboard');
        }
        
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pickup_scheduled', 'driver_assigned'])->count(),
            'active_drivers' => User::where('role', 'driver')->where('is_active', true)->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_clients' => User::where('role', 'client')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];
        
        $recentOrders = Order::with(['client', 'driver'])
                            ->latest()
                            ->limit(10)
                            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
    
}