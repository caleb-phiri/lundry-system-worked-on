<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Show driver dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get available orders (no driver assigned)
        $availableOrders = Order::whereNull('driver_id')
            ->whereIn('status', ['pending', 'pickup_scheduled'])
            ->with(['client', 'pickupLocation', 'deliveryLocation'])
            ->latest()
            ->get();
        
        // Get active deliveries for this driver
        $activeDeliveries = Order::where('driver_id', $user->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->with(['client', 'pickupLocation', 'deliveryLocation'])
            ->latest()
            ->get();
        
        // Get completed today
        $completedToday = Order::where('driver_id', $user->id)
            ->where('status', 'delivered')
            ->whereDate('delivery_completed_at', today())
            ->count();
        
        return view('driver.dashboard', compact('availableOrders', 'activeDeliveries', 'completedToday'));
    }

    /**
     * List orders for driver
     */
    public function orders(Request $request)
    {
        $user = Auth::user();
        
        // Available orders (no driver assigned)
        $availableOrders = Order::whereNull('driver_id')
            ->whereIn('status', ['pending', 'pickup_scheduled'])
            ->with(['client', 'pickupLocation', 'deliveryLocation'])
            ->latest()
            ->get();
        
        // Active orders (assigned to this driver)
        $activeOrders = Order::where('driver_id', $user->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->with(['client', 'pickupLocation', 'deliveryLocation'])
            ->latest()
            ->get();
        
        // Completed orders
        $completedOrders = Order::where('driver_id', $user->id)
            ->where('status', 'delivered')
            ->with(['client'])
            ->latest()
            ->take(20)
            ->get();
        
        return view('driver.orders.index', compact('availableOrders', 'activeOrders', 'completedOrders'));
    }

    /**
     * Show single order details
     */
    public function show(Order $order)
    {
        // Check if order belongs to this driver or is available
        if ($order->driver_id && $order->driver_id !== auth()->id()) {
            abort(403, 'This order is assigned to another driver.');
        }
        
        $order->load(['client', 'pickupLocation', 'deliveryLocation', 'basket']);
        
        return view('driver.orders.show', compact('order'));
    }

    /**
     * Accept an order
     */
    public function accept(Order $order)
    {
        if ($order->driver_id) {
            return back()->with('error', 'This order is already taken.');
        }
        
        $order->driver_id = auth()->id();
        $order->status = 'driver_assigned';
        $order->save();
        
        // Update tracking history
        $history = $order->tracking_history;
        if (is_string($history)) {
            $history = json_decode($history, true) ?? [];
        } elseif (!is_array($history)) {
            $history = [];
        }
        
        $history[] = [
            'status' => 'driver_assigned',
            'timestamp' => now()->toDateTimeString(),
            'note' => 'Driver assigned: ' . auth()->user()->name
        ];
        $order->tracking_history = $history;
        $order->save();
        
        return redirect()->route('driver.orders.show', $order)
            ->with('success', 'Order accepted successfully!');
    }

    /**
     * Mark order as collected
     */
    public function markCollected(Order $order)
    {
        if ($order->driver_id !== auth()->id()) {
            abort(403);
        }
        
        $order->status = 'collected';
        $order->pickup_completed_at = now();
        $order->save();
        
        $history = $order->tracking_history;
        if (is_string($history)) {
            $history = json_decode($history, true) ?? [];
        } elseif (!is_array($history)) {
            $history = [];
        }
        
        $history[] = [
            'status' => 'collected',
            'timestamp' => now()->toDateTimeString(),
            'note' => 'Laundry collected by driver'
        ];
        $order->tracking_history = $history;
        $order->save();
        
        return redirect()->back()->with('success', 'Order marked as collected!');
    }

    /**
     * Mark order as delivered
     */
    public function markDelivered(Order $order)
    {
        if ($order->driver_id !== auth()->id()) {
            abort(403);
        }
        
        $order->status = 'delivered';
        $order->delivery_completed_at = now();
        $order->save();
        
        $history = $order->tracking_history;
        if (is_string($history)) {
            $history = json_decode($history, true) ?? [];
        } elseif (!is_array($history)) {
            $history = [];
        }
        
        $history[] = [
            'status' => 'delivered',
            'timestamp' => now()->toDateTimeString(),
            'note' => 'Order delivered successfully'
        ];
        $order->tracking_history = $history;
        $order->save();
        
        // Add earnings to driver's wallet
        $wallet = auth()->user()->wallet;
        if ($wallet) {
            $earnings = $order->total * 0.2; // 20% commission
            $wallet->balance += $earnings;
            $wallet->lifetime_earnings += $earnings;
            $wallet->save();
            
            $wallet->transactions()->create([
                'amount' => $earnings,
                'type' => 'credit',
                'description' => 'Earnings from order #' . $order->order_number,
                'status' => 'completed',
                'reference' => 'ORD-' . $order->id
            ]);
        }
        
        return redirect()->back()->with('success', 'Order marked as delivered!');
    }

    /**
     * Update driver location
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $user->current_latitude = $request->latitude;
        $user->current_longitude = $request->longitude;
        $user->location_updated_at = now();
        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Get driver location
     */
    public function getLocation(User $driver)
    {
        return response()->json([
            'lat' => $driver->current_latitude ?? -15.3875259,
            'lng' => $driver->current_longitude ?? 28.3228165,
            'updated_at' => $driver->location_updated_at
        ]);
    }

    /**
     * Show earnings page
     */
    public function earnings()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        $today = Order::where('driver_id', $user->id)
            ->where('status', 'delivered')
            ->whereDate('delivery_completed_at', today())
            ->sum('total') * 0.2;
        
        $week = Order::where('driver_id', $user->id)
            ->where('status', 'delivered')
            ->whereBetween('delivery_completed_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total') * 0.2;
        
        $month = Order::where('driver_id', $user->id)
            ->where('status', 'delivered')
            ->whereMonth('delivery_completed_at', now()->month)
            ->sum('total') * 0.2;
        
        $transactions = $wallet ? $wallet->transactions()->latest()->paginate(20) : collect([]);
        
        return view('driver.earnings', compact('wallet', 'today', 'week', 'month', 'transactions'));
    }

    /**
     * Show schedule page
     */
    public function schedule()
    {
        $user = Auth::user();
        
        $upcoming = Order::where('driver_id', $user->id)
            ->whereIn('status', ['driver_assigned', 'pickup_scheduled', 'collected'])
            ->with(['client', 'pickupLocation'])
            ->latest('pickup_scheduled_at')
            ->get();
        
        return view('driver.schedule', compact('upcoming'));
    }
  /**
 * Export earnings as CSV
 */
public function exportEarnings()
{
    $user = Auth::user();
    $wallet = $user->wallet;
    
    $transactions = $wallet ? $wallet->transactions()->latest()->get() : collect([]);
    
    $filename = 'earnings-' . now()->format('Y-m-d') . '.csv';
    $handle = fopen('php://temp', 'w+');
    
    // Add headers
    fputcsv($handle, ['Date', 'Description', 'Reference', 'Type', 'Amount', 'Balance', 'Status']);
    
    // Add transactions
    foreach ($transactions as $transaction) {
        fputcsv($handle, [
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->description,
            $transaction->reference,
            ucfirst($transaction->type),
            number_format($transaction->amount, 2),
            number_format($transaction->balance_after ?? 0, 2),
            ucfirst($transaction->status)
        ]);
    }
    
    rewind($handle);
    $content = stream_get_contents($handle);
    fclose($handle);
    
    return response($content)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}
/**
 * Show driver profile
 */
public function profile()
{
    return view('driver.profile');
}

/**
 * Update driver profile
 */
public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'password' => 'nullable|string|min:8',
    ]);
    
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }
    
    $user->save();
    
    return redirect()->back()->with('success', 'Profile updated successfully!');
}

}