@extends('layouts.driver')

@section('title', 'Driver Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2">Welcome back, {{ auth()->user()->name }}! 🚚</h2>
                            <p class="mb-0">Ready for another day of deliveries? Let's make it happen!</p>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">K{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</h3>
                            <p class="mb-0">Wallet Balance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs text-primary text-uppercase mb-1">Available Orders</div>
                            <div class="h3 mb-0">{{ $availableOrders->count() ?? 0 }}</div>
                        </div>
                        <i class="fas fa-shopping-bag fa-2x text-primary-light"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs text-success text-uppercase mb-1">Active Deliveries</div>
                            <div class="h3 mb-0">{{ $activeDeliveries->count() ?? 0 }}</div>
                        </div>
                        <i class="fas fa-truck fa-2x text-success-light"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs text-info text-uppercase mb-1">Completed Today</div>
                            <div class="h3 mb-0">{{ $completedToday ?? 0 }}</div>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-info-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Available Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-bag text-primary me-2"></i>
                        Available Orders
                    </h5>
                    <a href="{{ route('driver.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($availableOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Pickup</th>
                                        <th>Amount</th>
                                        <th>Scheduled</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableOrders->take(5) as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->pickupLocation->name ?? 'N/A' }}</td>
                                        <td>K{{ number_format($order->total, 2) }}</td>
                                        <td>
                                            @if($order->pickup_scheduled_at)
                                                <small>{{ $order->pickup_scheduled_at->format('M j, g:i A') }}</small>
                                            @else
                                                <small>ASAP</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-check-circle me-1"></i>Accept
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($availableOrders->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('driver.orders.index') }}" class="btn btn-link">
                                    View all {{ $availableOrders->count() }} available orders
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                            <h6>No Available Orders</h6>
                            <p class="text-muted small">Check back later for new orders</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Active Deliveries -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-truck text-success me-2"></i>
                        My Active Deliveries
                    </h5>
                    <a href="{{ route('driver.orders.index') }}?tab=active" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body">
                    @if($activeDeliveries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeDeliveries->take(5) as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->client->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $order->status === 'collected' ? 'info' : 
                                                ($order->status === 'driver_assigned' ? 'warning' : 
                                                ($order->status === 'out_for_delivery' ? 'primary' : 'secondary')) 
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($activeDeliveries->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('driver.orders.index') }}?tab=active" class="btn btn-link">
                                    View all {{ $activeDeliveries->count() }} active deliveries
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-truck fa-4x text-muted mb-3"></i>
                            <h6>No Active Deliveries</h6>
                            <p class="text-muted small">Accept an order to get started</p>
                            <a href="{{ route('driver.orders.index') }}" class="btn btn-primary btn-sm mt-2">
                                Browse Available Orders
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mt-2">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Today's Earnings</h6>
                            <h4 class="mb-0">K{{ number_format($todayEarnings ?? 0, 2) }}</h4>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">This Week</h6>
                            <h4 class="mb-0">K{{ number_format($weekEarnings ?? 0, 2) }}</h4>
                        </div>
                        <i class="fas fa-calendar-week fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">This Month</h6>
                            <h4 class="mb-0">K{{ number_format($monthEarnings ?? 0, 2) }}</h4>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection