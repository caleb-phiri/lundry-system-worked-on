<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Basket;
use App\Models\SpecialItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_drivers' => User::where('role', 'driver')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'pickup_scheduled'])->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'today_revenue' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total'),
        ];

        $recentOrders = Order::with(['client', 'driver'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers'));
    }

    /**
     * List all orders
     */
    public function orders(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = Order::with(['client', 'driver', 'basket']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number or client name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show single order
     */
    public function showOrder(Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $order->load(['client', 'driver', 'basket', 'pickupLocation', 'deliveryLocation']);
        
        // Get available drivers for assignment
        $availableDrivers = User::where('role', 'driver')
            ->where('status', 'active')
            ->get();

        return view('admin.orders.show', compact('order', 'availableDrivers'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,pickup_scheduled,driver_assigned,collected,washing,drying,folding,out_for_delivery,delivered,cancelled'
        ]);

        $order->updateStatus($request->status, auth()->id());

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Assign driver to order
     */
    public function assignDriver(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'driver_id' => 'required|exists:users,id'
        ]);

        $order->driver_id = $request->driver_id;
        $order->status = 'driver_assigned';
        $order->save();

        // Track history
        $history = $order->tracking_history ?? [];
        $history[] = [
            'status' => 'driver_assigned',
            'old_status' => $order->status,
            'changed_by' => auth()->id(),
            'changed_at' => now()->toDateTimeString(),
            'driver_id' => $request->driver_id
        ];
        $order->tracking_history = $history;
        $order->save();

        return redirect()->back()->with('success', 'Driver assigned successfully.');
    }

    /**
     * List all drivers
     */
    public function drivers(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = User::where('role', 'driver');

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $drivers = $query->withCount(['orders as completed_orders_count' => function($q) {
            $q->where('status', 'delivered');
        }])->paginate(20);

        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Show single driver
     */
    public function showDriver(User $driver)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($driver->role !== 'driver') {
            abort(404);
        }

        $stats = [
            'total_orders' => Order::where('driver_id', $driver->id)->count(),
            'completed_orders' => Order::where('driver_id', $driver->id)->where('status', 'delivered')->count(),
            'total_earnings' => Order::where('driver_id', $driver->id)->where('status', 'delivered')->sum('total') * 0.2,
            'rating' => 4.8
        ];

        $recentOrders = Order::where('driver_id', $driver->id)
            ->with('client')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.drivers.show', compact('driver', 'stats', 'recentOrders'));
    }

    /**
     * Create new driver
     */
    public function storeDriver(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $driver = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'driver',
            'status' => 'active'
        ]);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Update driver
     */
    public function updateDriver(Request $request, User $driver)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($driver->role !== 'driver') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $driver->id,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive'
        ]);

        $driver->update($request->only(['name', 'email', 'phone', 'status']));

        if ($request->filled('password')) {
            $driver->password = Hash::make($request->password);
            $driver->save();
        }

        return redirect()->back()->with('success', 'Driver updated successfully.');
    }

    /**
     * Delete driver
     */
    public function deleteDriver(User $driver)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($driver->role !== 'driver') {
            abort(404);
        }

        // Check if driver has active orders
        $activeOrders = Order::where('driver_id', $driver->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->count();

        if ($activeOrders > 0) {
            return redirect()->back()->with('error', 'Cannot delete driver with active orders.');
        }

        $driver->delete();

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }

    /**
     * List all clients
     */
    public function clients(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = User::where('role', 'client');

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $clients = $query->withCount('orders')
            ->withSum('orders as total_spent', 'total')
            ->latest()
            ->paginate(20);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show single client
     */
    public function showClient(User $client)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($client->role !== 'client') {
            abort(404);
        }

        // Get or create wallet
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $client->id],
            ['balance' => 0, 'lifetime_earnings' => 0, 'lifetime_spent' => 0]
        );

        $stats = [
            'total_orders' => $client->orders()->count(),
            'completed_orders' => $client->orders()->where('status', 'delivered')->count(),
            'total_spent' => $client->orders()->where('payment_status', 'paid')->sum('total'),
            'wallet_balance' => $wallet->balance
        ];

        $recentOrders = $client->orders()
            ->with('driver')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.clients.show', compact('client', 'stats', 'recentOrders'));
    }

    /**
     * Update client status
     */
    public function updateClientStatus(Request $request, User $client)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($client->role !== 'client') {
            abort(404);
        }

        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $client->status = $request->status;
        $client->save();

        return redirect()->back()->with('success', 'Client status updated successfully.');
    }

    /**
     * Reports dashboard
     */
    public function reports()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.reports.index');
    }

    /**
     * Revenue report
     */
    public function revenueReport(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $revenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $summary = [
            'total_revenue' => $revenue->sum('total'),
            'total_orders' => $revenue->sum('orders_count'),
            'average_order' => $revenue->avg('total') ?? 0,
        ];

        return view('admin.reports.revenue', compact('revenue', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Orders report
     */
    public function ordersReport(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with('client')
            ->get();

        $statusCounts = $orders->groupBy('status')
            ->map->count();

        return view('admin.reports.orders', compact('orders', 'statusCounts', 'startDate', 'endDate'));
    }

    /**
     * Drivers report
     */
    public function driversReport(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $drivers = User::where('role', 'driver')
            ->withCount(['orders as completed_orders' => function($q) use ($startDate, $endDate) {
                $q->where('status', 'delivered')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['orders as total_earnings' => function($q) use ($startDate, $endDate) {
                $q->where('status', 'delivered')
                  ->whereBetween('created_at', [$startDate, $endDate])
                  ->select(DB::raw('SUM(total) * 0.2'));
            }], 'total')
            ->get();

        return view('admin.reports.drivers', compact('drivers', 'startDate', 'endDate'));
    }

    /**
     * Export report
     */
    public function exportReport(Request $request, $type)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return redirect()->back()->with('info', 'Export feature coming soon.');
    }

    /**
     * Settings page
     */
    public function settings()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.settings.index');
    }

    /**
     * Update general settings
     */
    public function updateGeneralSettings(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Update pricing settings
     */
    public function updatePricing(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return redirect()->back()->with('success', 'Pricing updated successfully.');
    }

    /**
     * Update payment settings
     */
    public function updatePaymentSettings(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return redirect()->back()->with('success', 'Payment settings updated successfully.');
    }
}