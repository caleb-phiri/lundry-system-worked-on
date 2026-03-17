@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Order Info Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }} p-2">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Client Information</h6>
                        <p><strong>Name:</strong> {{ $order->client->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $order->client->email ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $order->client->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Order Summary</h6>
                        <p><strong>Basket:</strong> {{ $order->basket->name ?? 'N/A' }}</p>
                        <p><strong>Subtotal:</strong> K{{ number_format($order->subtotal, 2) }}</p>
                        @if($order->surcharges > 0)
                        <p><strong>Surcharges:</strong> +K{{ number_format($order->surcharges, 2) }}</p>
                        @endif
                        @if($order->discount > 0)
                        <p><strong>Discount:</strong> -K{{ number_format($order->discount, 2) }}</p>
                        @endif
                        <p><strong>Total:</strong> K{{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Location Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Pickup Location</h6>
                        <p><strong>{{ $order->pickupLocation->name ?? 'N/A' }}</strong></p>
                        <p>{{ $order->pickupLocation->address ?? 'N/A' }}</p>
                        @if($order->pickup_scheduled_at)
                        <p class="text-muted">
                            <i class="far fa-calendar me-2"></i>{{ $order->pickup_scheduled_at->format('M j, Y g:i A') }}
                        </p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Delivery Location</h6>
                        <p><strong>{{ $order->deliveryLocation->name ?? 'N/A' }}</strong></p>
                        <p>{{ $order->deliveryLocation->address ?? 'N/A' }}</p>
                        @if($order->delivery_scheduled_at)
                        <p class="text-muted">
                            <i class="far fa-calendar me-2"></i>{{ $order->delivery_scheduled_at->format('M j, Y g:i A') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @php
                        // Safely decode tracking history
                        $trackingHistory = [];
                        
                        if (!empty($order->tracking_history)) {
                            if (is_array($order->tracking_history)) {
                                $trackingHistory = $order->tracking_history;
                            } elseif (is_string($order->tracking_history)) {
                                $decoded = json_decode($order->tracking_history, true);
                                if (is_array($decoded)) {
                                    $trackingHistory = $decoded;
                                }
                            }
                        }
                        
                        // Sort by date if available
                        if (!empty($trackingHistory)) {
                            $trackingHistory = collect($trackingHistory)->sortByDesc('changed_at')->values()->all();
                        }
                    @endphp

                    @forelse($trackingHistory as $history)
                        @php
                            $status = $history['status'] ?? 'Unknown';
                            $oldStatus = $history['old_status'] ?? null;
                            $changedAt = isset($history['changed_at']) ? \Carbon\Carbon::parse($history['changed_at']) : null;
                            $changedBy = $history['changed_by'] ?? null;
                        @endphp
                        
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fas fa-circle text-{{ $loop->first ? 'success' : 'primary' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                                        @if($oldStatus && $oldStatus !== $status)
                                            <br>
                                            <small class="text-muted">
                                                Changed from {{ ucfirst(str_replace('_', ' ', $oldStatus)) }}
                                            </small>
                                        @endif
                                    </div>
                                    @if($changedAt)
                                        <small class="text-muted">
                                            {{ $changedAt->format('M j, Y g:i A') }}
                                        </small>
                                    @endif
                                </div>
                                @if($changedBy)
                                    <small class="text-muted d-block">
                                        By: {{ App\Models\User::find($changedBy)?->name ?? 'System' }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        
                        @if(!$loop->last)
                            <hr class="my-2">
                        @endif
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No tracking history available</p>
                        </div>
                    @endforelse

                    <!-- Current Status (always show) -->
                    @if($trackingHistory)
                        <hr class="my-3">
                    @endif
                    
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <i class="fas fa-circle text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>Current Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong>
                                </div>
                                <small class="text-muted">
                                    {{ now()->format('M j, Y g:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Update -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-select mb-3" required>
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="pickup_scheduled" {{ $order->status == 'pickup_scheduled' ? 'selected' : '' }}>Pickup Scheduled</option>
                        <option value="driver_assigned" {{ $order->status == 'driver_assigned' ? 'selected' : '' }}>Driver Assigned</option>
                        <option value="collected" {{ $order->status == 'collected' ? 'selected' : '' }}>Collected</option>
                        <option value="washing" {{ $order->status == 'washing' ? 'selected' : '' }}>Washing</option>
                        <option value="drying" {{ $order->status == 'drying' ? 'selected' : '' }}>Drying</option>
                        <option value="folding" {{ $order->status == 'folding' ? 'selected' : '' }}>Folding</option>
                        <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sync me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Driver Assignment -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Driver Assignment</h5>
            </div>
            <div class="card-body">
                @if($order->driver)
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-light rounded-circle p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $order->driver->name }}</h6>
                        <p class="text-muted small mb-0">{{ $order->driver->phone }}</p>
                        <p class="text-muted small mb-0">{{ $order->driver->email }}</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('admin.orders.assign-driver', $order) }}" method="POST">
                    @csrf
                    <select name="driver_id" class="form-select mb-3" {{ !isset($availableDrivers) || !$availableDrivers->count() ? 'disabled' : '' }}>
                        <option value="">Select Driver</option>
                        @if(isset($availableDrivers) && $availableDrivers->count() > 0)
                            @foreach($availableDrivers as $driver)
                            <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }} ({{ $driver->phone }})
                            </option>
                            @endforeach
                        @endif
                    </select>
                    <button type="submit" class="btn btn-success w-100" {{ !isset($availableDrivers) || !$availableDrivers->count() ? 'disabled' : '' }}>
                        <i class="fas fa-truck me-2"></i>{{ $order->driver ? 'Change Driver' : 'Assign Driver' }}
                    </button>
                </form>
                
                @if(!isset($availableDrivers) || !$availableDrivers->count())
                    <p class="text-warning small mt-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>No active drivers available
                    </p>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Method:</td>
                        <td class="text-end">
                            @switch($order->payment_method)
                                @case('mtn') 📱 MTN @break
                                @case('airtel') 📱 Airtel @break
                                @case('zamtel') 📱 Zamtel @break
                                @case('wallet') 💰 Wallet @break
                                @default {{ ucfirst($order->payment_method ?? 'N/A') }}
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Type:</td>
                        <td class="text-end">{{ ucfirst($order->payment_type ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status:</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                </table>
                
                @if($order->transaction_reference)
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted d-block">Transaction Reference</small>
                    <code class="small">{{ $order->transaction_reference }}</code>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection