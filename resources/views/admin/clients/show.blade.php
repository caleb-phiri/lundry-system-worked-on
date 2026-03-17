@extends('layouts.admin')

@section('title', 'Client Details - ' . $client->name)
@section('page-title', 'Client Details')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Client Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="bg-primary-light rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user fa-4x text-primary"></i>
                </div>
                <h4>{{ $client->name }}</h4>
                <p class="text-muted mb-2">{{ $client->email }}</p>
                <p class="text-muted mb-3">{{ $client->phone ?? 'No phone number' }}</p>
                
                <div class="mb-3">
                    @if($client->status === 'active')
                        <span class="badge bg-success p-2">Active</span>
                    @elseif($client->status === 'inactive')
                        <span class="badge bg-secondary p-2">Inactive</span>
                    @else
                        <span class="badge bg-danger p-2">Suspended</span>
                    @endif
                </div>
                
                <!-- Update Status Form -->
                <form action="{{ route('admin.clients.status', $client) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <select name="status" class="form-select">
                            <option value="active" {{ $client->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $client->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ $client->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Client Stats</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-primary mb-1">{{ $stats['total_orders'] }}</h3>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-success mb-1">{{ $stats['completed_orders'] }}</h3>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-warning mb-1">K{{ number_format($stats['total_spent'], 2) }}</h3>
                            <small class="text-muted">Total Spent</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Wallet Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h2 class="text-primary mb-2">K{{ number_format($stats['wallet_balance'], 2) }}</h2>
                    <p class="text-muted">Current Balance</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Driver</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>K{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->driver->name ?? 'Unassigned' }}</td>
                                <td>{{ $order->created_at->format('M j, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <p>No orders yet</p>
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
@endsection