<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Basket;
use App\Models\SpecialItem;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of client orders
     */
   public function clientOrders()
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $orders = Order::where('client_id', Auth::id())
                   ->with(['basket', 'driver'])
                   ->latest()
                   ->paginate(10);
    
    // Decode special_items for each order
    foreach ($orders as $order) {
        if (is_string($order->special_items)) {
            $order->special_items = json_decode($order->special_items, true) ?? [];
        }
        if (!is_array($order->special_items)) {
            $order->special_items = [];
        }
    }
    
    return view('client.orders.index', compact('orders'));
}

    /**
     * Display the specified order
     */
    public function show(Order $order)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    if ($order->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized access.');
    }
    
    // Decode special_items
    if (is_string($order->special_items)) {
        $order->special_items = json_decode($order->special_items, true) ?? [];
    }
    if (!is_array($order->special_items)) {
        $order->special_items = [];
    }
    
    return view('client.orders.show', compact('order'));
}

    /**
     * Track order status
     */
    public function track(Order $order)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($order->client_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'driver') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('client.orders.track', compact('order'));
    }

    /**
     * Generate invoice for an order
     */
    public function invoice(Order $order)
    {
        if ($order->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $order->load(['client', 'basket', 'pickupLocation', 'deliveryLocation']);
        
        $pdf = Pdf::loadView('invoices.order', compact('order'));
        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please login first'], 401);
            }
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'pickup_location_id' => 'nullable|exists:locations,id',
            'pickup_latitude' => 'nullable|numeric',
            'pickup_longitude' => 'nullable|numeric',
            'pickup_address' => 'nullable|string|max:500',
            'pickup_name' => 'nullable|string|max:255',
            'delivery_location_id' => 'nullable|exists:locations,id',
            'delivery_latitude' => 'nullable|numeric',
            'delivery_longitude' => 'nullable|numeric',
            'delivery_address' => 'nullable|string|max:500',
            'delivery_name' => 'nullable|string|max:255',
            'basket_id' => 'required|exists:baskets,id',
            'special_items' => 'nullable|array',
            'special_items.*' => 'exists:special_items,id',
            'pickup_scheduled_at' => 'required|date|after:now',
            'payment_method' => 'required|in:mtn,airtel,zamtel,wallet',
            'payment_type' => 'required|in:prepaid,postpaid',
            'special_instructions' => 'nullable|string|max:500'
        ]);

        $basket = Basket::findOrFail($request->basket_id);
        
        $subtotal = $basket->base_price;
        $surcharges = 0;
        
        if ($request->has('special_items')) {
            $specialItems = SpecialItem::whereIn('id', $request->special_items)->get();
            $surcharges = $specialItems->sum('surcharge');
        }
        
        $total = $subtotal + $surcharges;
        
        if ($request->payment_type === 'prepaid') {
            $total = $total * 0.95;
        }

        DB::beginTransaction();
        
        try {
            // Handle pickup location
            $pickupLocationId = $request->pickup_location_id;
            if ((!$pickupLocationId || $pickupLocationId === '') && $request->pickup_latitude && $request->pickup_longitude) {
                $pickupLocation = Location::create([
                    'user_id' => Auth::id(),
                    'name' => $request->pickup_name ?: 'Map Location',
                    'address' => $request->pickup_address ?: 'Address from map',
                    'latitude' => $request->pickup_latitude,
                    'longitude' => $request->pickup_longitude,
                    'is_default' => false,
                    'contact_phone' => Auth::user()->phone ?? 'N/A',
                    'contact_email' => Auth::user()->email ?? 'N/A',
                ]);
                $pickupLocationId = $pickupLocation->id;
            }
            
            // Handle delivery location
            $deliveryLocationId = $request->delivery_location_id;
            if ((!$deliveryLocationId || $deliveryLocationId === '') && $request->delivery_latitude && $request->delivery_longitude) {
                $deliveryLocation = Location::create([
                    'user_id' => Auth::id(),
                    'name' => $request->delivery_name ?: 'Map Location',
                    'address' => $request->delivery_address ?: 'Address from map',
                    'latitude' => $request->delivery_latitude,
                    'longitude' => $request->delivery_longitude,
                    'is_default' => false,
                    'contact_phone' => Auth::user()->phone ?? 'N/A',
                    'contact_email' => Auth::user()->email ?? 'N/A',
                ]);
                $deliveryLocationId = $deliveryLocation->id;
            }
            
            if (!$pickupLocationId || !$deliveryLocationId) {
                throw new \Exception('Both pickup and delivery locations are required.');
            }
            
            // Use 'pickup_scheduled' as the status (this matches your database constraint)
            $orderStatus = 'pickup_scheduled';
            
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'client_id' => Auth::id(),
                'basket_id' => $basket->id,
                'pickup_location_id' => $pickupLocationId,
                'delivery_location_id' => $deliveryLocationId,
                'subtotal' => $subtotal,
                'surcharges' => $surcharges,
                'total' => round($total, 2),
                'payment_method' => $request->payment_method,
                'payment_type' => $request->payment_type,
                'payment_status' => 'pending',
                'pickup_scheduled_at' => $request->pickup_scheduled_at,
                'special_instructions' => $request->special_instructions,
                'special_items' => json_encode($request->special_items ?? []),
                'status' => $orderStatus,
                'tracking_history' => json_encode([[
                    'status' => $orderStatus,
                    'timestamp' => now()->toDateTimeString(),
                    'note' => 'Order created successfully'
                ]]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($request->payment_method === 'wallet' && Auth::user()->wallet) {
                $wallet = Auth::user()->wallet;
                if ($wallet->balance >= $total) {
                    $wallet->debit($total, "Payment for order #{$order->order_number}");
                    $order->payment_status = 'paid';
                    $order->save();
                }
            }

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully!',
                    'redirect_url' => route('client.orders.show', $order)
                ]);
            }
            
            return redirect()->route('client.orders.show', $order)->with('success', 'Order created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create order: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create order: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Calculate price for AJAX requests
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'basket_id' => 'required|exists:baskets,id',
            'special_items' => 'nullable|array',
            'special_items.*' => 'exists:special_items,id',
            'payment_type' => 'nullable|in:prepaid,postpaid'
        ]);

        $basket = Basket::findOrFail($request->basket_id);
        $subtotal = $basket->base_price;
        $surcharges = 0;
        
        if ($request->has('special_items')) {
            $specialItems = SpecialItem::whereIn('id', $request->special_items)->get();
            $surcharges = $specialItems->sum('surcharge');
        }
        
        $total = $subtotal + $surcharges;
        
        if ($request->payment_type === 'prepaid') {
            $total = $total * 0.95;
        }
        
        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'surcharges' => $surcharges,
            'total' => round($total, 2)
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($order->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($order->status, ['pickup_scheduled', 'driver_assigned'])) {
            return back()->with('error', 'Order cannot be cancelled at this stage.');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order cancelled successfully.');
    }
    
}