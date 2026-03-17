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
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('client_id', Auth::id())
                       ->with(['basket', 'driver'])
                       ->latest()
                       ->paginate(10);
        
        return view('client.orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if order belongs to authenticated user
        if ($order->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('client.orders.show', compact('order'));
    }

    /**
     * Track order status
     */
    public function track(Order $order)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if order belongs to authenticated user
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
        // Check if order belongs to authenticated user
        if ($order->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        // Load relationships
        $order->load(['client', 'basket', 'pickupLocation', 'deliveryLocation']);
        
        $pdf = Pdf::loadView('invoices.order', compact('order'));
        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'pickup_location_id' => 'required|exists:locations,id',
            'delivery_location_id' => 'required|exists:locations,id',
            'basket_id' => 'required|exists:baskets,id',
            'special_items' => 'nullable|array',
            'special_items.*' => 'exists:special_items,id',
            'pickup_scheduled_at' => 'required|date|after:now',
            'payment_method' => 'required|in:mtn,airtel,zamtel,wallet',
            'payment_type' => 'required|in:prepaid,postpaid',
            'special_instructions' => 'nullable|string|max:500'
        ]);

        $basket = Basket::findOrFail($request->basket_id);
        
        // Calculate price
        $subtotal = $basket->base_price;
        $surcharges = 0;
        
        if ($request->has('special_items')) {
            $specialItems = SpecialItem::whereIn('id', $request->special_items)->get();
            $surcharges = $specialItems->sum('surcharge');
        }
        
        $total = $subtotal + $surcharges;

        DB::beginTransaction();
        
        try {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'client_id' => Auth::id(),
                'basket_id' => $basket->id,
                'pickup_location_id' => $request->pickup_location_id,
                'delivery_location_id' => $request->delivery_location_id,
                'subtotal' => $subtotal,
                'surcharges' => $surcharges,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_type' => $request->payment_type,
                'payment_status' => 'pending',
                'pickup_scheduled_at' => $request->pickup_scheduled_at,
                'special_instructions' => $request->special_instructions,
                'special_items' => $request->special_items ?? [],
                'status' => 'pickup_scheduled',
                'tracking_history' => json_encode([[
                    'status' => 'pickup_scheduled',
                    'timestamp' => now()->toDateTimeString(),
                    'note' => 'Order created'
                ]])
            ]);

            // If payment method is wallet and has sufficient balance
            if ($request->payment_method === 'wallet' && Auth::user()->wallet) {
                $wallet = Auth::user()->wallet;
                if ($wallet->balance >= $total) {
                    $wallet->debit($total, "Payment for order #{$order->order_number}");
                    $order->payment_status = 'paid';
                    $order->save();
                }
            }

            DB::commit();
            
            return redirect()->route('client.orders.show', $order)
                           ->with('success', 'Order created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
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
            'special_items.*' => 'exists:special_items,id'
        ]);

        $basket = Basket::findOrFail($request->basket_id);
        $subtotal = $basket->base_price;
        $surcharges = 0;
        
        if ($request->has('special_items')) {
            $specialItems = SpecialItem::whereIn('id', $request->special_items)->get();
            $surcharges = $specialItems->sum('surcharge');
        }
        
        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'surcharges' => $surcharges,
            'total' => $subtotal + $surcharges
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if order belongs to authenticated user
        if ($order->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pickup_scheduled', 'driver_assigned'])) {
            return back()->with('error', 'Order cannot be cancelled at this stage.');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order cancelled successfully.');
    }
}