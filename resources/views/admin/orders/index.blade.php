@extends('layouts.driver')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="orderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="available-tab" data-bs-toggle="tab" data-bs-target="#available" type="button" role="tab">
                                Available Orders
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                                Active Orders
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                                Completed
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="orderTabsContent">
                        <!-- Available Orders Tab -->
                        <div class="tab-pane fade show active" id="available" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Pickup Location</th>
                                            <th>Delivery Location</th>
                                            <th>Amount</th>
                                            <th>Scheduled</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($availableOrders ?? [] as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->pickupLocation->name ?? 'N/A' }}</td>
                                            <td>{{ $order->deliveryLocation->name ?? 'N/A' }}</td>
                                            <td>K{{ number_format($order->total, 2) }}</td>
                                            <td>{{ $order->pickup_scheduled_at ? $order->pickup_scheduled_at->format('M j, g:i A') : 'ASAP' }}</td>
                                            <td>
                                                <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p>No available orders at the moment</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Active Orders Tab -->
                        <div class="tab-pane fade" id="active" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Pickup Location</th>
                                            <th>Delivery Location</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($activeOrders ?? [] as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->pickupLocation->name ?? 'N/A' }}</td>
                                            <td>{{ $order->deliveryLocation->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'collected' ? 'info' : 'warning' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                                <p>No active orders</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Completed Orders Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Completed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($completedOrders ?? [] as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->client->name ?? 'N/A' }}</td>
                                            <td>K{{ number_format($order->total, 2) }}</td>
                                            <td>{{ $order->delivery_completed_at ? $order->delivery_completed_at->format('M j, Y') : 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                                <p>No completed orders yet</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection