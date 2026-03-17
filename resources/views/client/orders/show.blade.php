@extends('layouts.client')

@section('title', 'Order #' . $order->order_number)

@section('client-content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                @if(in_array($order->status, ['pickup_scheduled', 'driver_assigned']))
                    <form action="{{ route('client.orders.cancel', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="fas fa-times me-2"></i>Cancel Order
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                <!-- Order Status Timeline -->
                <div class="mb-4">
                    <h6 class="mb-3">Order Status</h6>
                    <div class="progress mb-3" style="height: 10px;">
                        @php
                            $statuses = ['pickup_scheduled', 'driver_assigned', 'collected', 'in_laundry', 'out_for_delivery', 'delivered'];
                            $currentIndex = array_search($order->status, $statuses);
                            $progress = $currentIndex !== false ? (($currentIndex + 1) / count($statuses)) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>
                    
                    <div class="row mt-4">
                        @foreach(['pickup_scheduled', 'driver_assigned', 'collected', 'in_laundry', 'out_for_delivery', 'delivered'] as $index => $status)
                            @php
                                $statusTexts = [
                                    'pickup_scheduled' => 'Pickup Scheduled',
                                    'driver_assigned' => 'Driver Assigned',
                                    'collected' => 'Collected',
                                    'in_laundry' => 'In Laundry',
                                    'out_for_delivery' => 'Out for Delivery',
                                    'delivered' => 'Delivered'
                                ];
                                $isCompleted = array_search($order->status, $statuses) >= $index;
                                $isCurrent = $order->status === $status;
                            @endphp
                            <div class="col-2 text-center">
                                <div class="mb-2">
                                    <div class="rounded-circle mx-auto {{ $isCompleted ? 'bg-success' : ($isCurrent ? 'bg-primary' : 'bg-light') }}" 
                                         style="width: 30px; height: 30px; line-height: 30px;">
                                        @if($isCompleted)
                                            <i class="fas fa-check text-white"></i>
                                        @else
                                            <span class="{{ $isCurrent ? 'text-white' : 'text-muted' }}">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                </div>
                                <small class="{{ $isCompleted ? 'text-success' : ($isCurrent ? 'text-primary fw-bold' : 'text-muted') }}">
                                    {{ $statusTexts[$status] }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <hr>
                
                <!-- Order Details -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Pickup Information</h6>
                        <p class="mb-1"><strong>Location:</strong> {{ $order->pickupLocation->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $order->pickupLocation->address ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Scheduled:</strong> {{ \Carbon\Carbon::parse($order->pickup_scheduled_at)->format('M d, Y H:i') }}</p>
                        @if($order->pickup_completed_at)
                            <p class="mb-1"><strong>Completed:</strong> {{ \Carbon\Carbon::parse($order->pickup_completed_at)->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Delivery Information</h6>
                        <p class="mb-1"><strong>Location:</strong> {{ $order->deliveryLocation->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $order->deliveryLocation->address ?? 'N/A' }}</p>
                        @if($order->delivery_completed_at)
                            <p class="mb-1"><strong>Delivered:</strong> {{ \Carbon\Carbon::parse($order->delivery_completed_at)->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                </div>
                
                @if($order->special_instructions)
                    <div class="mt-3">
                        <h6 class="mb-2">Special Instructions</h6>
                        <p class="mb-0">{{ $order->special_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Basket ({{ $order->basket->name ?? 'N/A' }})</span>
                    <span>K{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->surcharges > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Surcharges</span>
                        <span>K{{ number_format($order->surcharges, 2) }}</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Total</strong>
                    <strong class="text-primary">K{{ number_format($order->total, 2) }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Payment Method</span>
                    <span class="badge bg-info">{{ strtoupper($order->payment_method) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Payment Type</span>
                    <span class="badge bg-secondary">{{ ucfirst($order->payment_type) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Payment Status</span>
                    @php
                        $paymentColors = [
                            'pending' => 'warning',
                            'paid' => 'success',
                            'failed' => 'danger',
                            'refunded' => 'info'
                        ];
                    @endphp
                    <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Driver Information -->
        @if($order->driver)
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Driver Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $order->driver->name }}</h6>
                            <p class="mb-0 small">{{ $order->driver->phone }}</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-phone me-2"></i>Contact Driver
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection