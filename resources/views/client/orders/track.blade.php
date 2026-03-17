@extends('layouts.client')

@section('title', 'Track Order #' . $order->order_number)

@push('styles')
<style>
    :root {
        --primary: #4361ee;
        --primary-dark: #3730a3;
        --primary-light: #e0e7ff;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    .tracking-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
    }

    .order-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tracking-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .tracking-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }

    .tracking-card-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tracking-card-body {
        padding: 1.5rem;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding: 1rem 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border);
        z-index: 1;
    }

    .timeline-item {
        position: relative;
        padding-left: 4rem;
        margin-bottom: 2rem;
        z-index: 2;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: 16px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--border);
        z-index: 3;
        transition: all 0.3s;
    }

    .timeline-item.completed .timeline-marker {
        background: var(--success);
        border-color: var(--success);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .timeline-item.active .timeline-marker {
        background: var(--primary);
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-light);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 var(--primary-light); }
        70% { box-shadow: 0 0 0 10px rgba(67, 97, 238, 0); }
        100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
    }

    .timeline-content {
        background: var(--light);
        border-radius: 1rem;
        padding: 1rem;
    }

    .timeline-time {
        font-size: 0.75rem;
        color: var(--gray);
        margin-top: 0.25rem;
    }

    /* Driver Card */
    .driver-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 1rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .driver-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.2);
    }

    .driver-info h4 {
        margin: 0 0 0.5rem;
        font-weight: 600;
    }

    .driver-contact {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .driver-contact a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Map Container */
    .live-map {
        height: 300px;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-pickup_scheduled { background: #cfe2ff; color: #084298; }
    .status-collected { background: #d1e7dd; color: #0f5132; }
    .status-washing { background: #e0f2fe; color: #0369a1; }
    .status-drying { background: #f3e8ff; color: #6b21a8; }
    .status-folding { background: #fff1f2; color: #9f1239; }
    .status-out_for_delivery { background: #fef9c3; color: #854d0e; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    /* Progress Bar */
    .progress-container {
        margin: 2rem 0;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .progress-step {
        text-align: center;
        font-size: 0.875rem;
        color: var(--gray);
        flex: 1;
    }

    .progress-step.active {
        color: var(--primary);
        font-weight: 600;
    }

    .progress-bar-custom {
        height: 8px;
        background: var(--border);
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    /* Refresh Button */
    .refresh-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        transition: all 0.3s;
        z-index: 1000;
    }

    .refresh-btn:hover {
        transform: rotate(180deg) scale(1.1);
    }

    .refresh-btn:active {
        transform: rotate(180deg) scale(0.9);
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        background: var(--light);
        border-radius: 1rem;
        padding: 1rem;
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--gray);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark);
    }

    .no-driver-card {
        background: var(--light);
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
    }

    .no-driver-icon {
        font-size: 3rem;
        color: var(--gray);
        margin-bottom: 1rem;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('client-content')
<div class="container py-4">
    <!-- Tracking Header -->
    <div class="tracking-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="order-badge">
                        <i class="fas fa-hashtag"></i> Order #{{ $order->order_number }}
                    </span>
                    <span class="order-badge">
                        <i class="far fa-calendar"></i> {{ $order->created_at->format('M j, Y') }}
                    </span>
                </div>
                <h2 class="mb-2">Track Your Order</h2>
                <p class="mb-0 opacity-75">Current Status: 
                    <span class="status-badge status-{{ $order->status }} ms-2">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('client.orders.show', $order) }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Order
                </a>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="tracking-card">
        <div class="tracking-card-body">
            <div class="progress-container">
                @php
                    $statusFlow = [
                        'pending' => 0,
                        'pickup_scheduled' => 16,
                        'driver_assigned' => 32,
                        'collected' => 48,
                        'washing' => 64,
                        'drying' => 72,
                        'folding' => 80,
                        'out_for_delivery' => 90,
                        'delivered' => 100
                    ];
                    
                    $currentProgress = $statusFlow[$order->status] ?? 0;
                    
                    $steps = [
                        'pending' => 'Order Placed',
                        'pickup_scheduled' => 'Pickup Scheduled',
                        'collected' => 'Collected',
                        'washing' => 'Processing',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Delivered'
                    ];
                    
                    $statusKeys = array_keys($steps);
                    $currentStepIndex = array_search($order->status, $statusKeys) !== false 
                        ? array_search($order->status, $statusKeys) 
                        : 0;
                @endphp

                <div class="progress-steps">
                    @foreach($steps as $key => $label)
                        <div class="progress-step {{ array_search($key, $statusKeys) <= $currentStepIndex ? 'active' : '' }}">
                            {{ $label }}
                        </div>
                    @endforeach
                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill" style="width: {{ $currentProgress }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Live Map (if driver assigned and out for delivery) -->
            @if(in_array($order->status, ['driver_assigned', 'out_for_delivery']) && $order->driver)
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <h5>
                            <i class="fas fa-map-marked-alt" style="color: var(--primary);"></i>
                            Live Tracking
                        </h5>
                    </div>
                    <div class="tracking-card-body">
                        <div id="live-map" class="live-map"></div>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-sync-alt fa-spin me-1"></i>
                                Driver location updates every 30 seconds
                            </small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="tracking-card">
                <div class="tracking-card-header">
                    <h5>
                        <i class="fas fa-clock" style="color: var(--primary);"></i>
                        Order Timeline
                    </h5>
                </div>
                <div class="tracking-card-body">
                    <div class="timeline">
                        @php
                            $timelineStatuses = [
                                'pending' => 'Order Placed',
                                'pickup_scheduled' => 'Pickup Scheduled',
                                'driver_assigned' => 'Driver Assigned',
                                'collected' => 'Laundry Collected',
                                'washing' => 'Washing',
                                'drying' => 'Drying',
                                'folding' => 'Folding',
                                'out_for_delivery' => 'Out for Delivery',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled'
                            ];
                            
                            $currentStatusIndex = array_search($order->status, array_keys($timelineStatuses)) !== false 
                                ? array_search($order->status, array_keys($timelineStatuses)) 
                                : -1;
                        @endphp

                        @foreach($timelineStatuses as $statusKey => $statusLabel)
                            @php
                                $statusIndex = array_search($statusKey, array_keys($timelineStatuses));
                                $isCompleted = $statusIndex <= $currentStatusIndex && $order->status !== 'cancelled';
                                $isActive = $statusKey === $order->status;
                                $showItem = $order->status !== 'cancelled' || $statusKey === 'cancelled' || $statusIndex <= $currentStatusIndex;
                                
                                $statusTime = null;
                                if ($order->tracking_history && is_array($order->tracking_history)) {
                                    $historyItem = collect($order->tracking_history)
                                        ->where('status', $statusKey)
                                        ->last();
                                    if ($historyItem) {
                                        $statusTime = isset($historyItem['changed_at']) 
                                            ? \Carbon\Carbon::parse($historyItem['changed_at']) 
                                            : null;
                                    }
                                }
                            @endphp

                            @if($showItem)
                                <div class="timeline-item {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $statusLabel }}</h6>
                                                @if($statusTime)
                                                    <div class="timeline-time">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ $statusTime->format('M j, Y g:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if($isCompleted && $statusKey !== $order->status)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                            @if($isActive)
                                                <span class="badge bg-primary">Current</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Driver Information -->
            @if($order->driver)
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <h5>
                            <i class="fas fa-user" style="color: var(--primary);"></i>
                            Driver Information
                        </h5>
                    </div>
                    <div class="tracking-card-body">
                        <div class="driver-card">
                            <div class="driver-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="driver-info">
                                <h4>{{ $order->driver->name }}</h4>
                                @if($order->driver->phone)
                                <div class="driver-contact">
                                    <a href="tel:{{ $order->driver->phone }}">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    <a href="sms:{{ $order->driver->phone }}">
                                        <i class="fas fa-comment"></i> Message
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="tracking-card">
                    <div class="tracking-card-body">
                        <div class="no-driver-card">
                            <div class="no-driver-icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <h6>Finding a Driver</h6>
                            <p class="text-muted small mb-0">
                                We're looking for a nearby driver to pick up your laundry. You'll be notified once a driver is assigned.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order Information -->
            <div class="tracking-card">
                <div class="tracking-card-header">
                    <h5>
                        <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                        Order Information
                    </h5>
                </div>
                <div class="tracking-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Basket</div>
                            <div class="info-value">{{ $order->basket->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Total Amount</div>
                            <div class="info-value text-primary">K{{ number_format($order->total, 2) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Method</div>
                            <div class="info-value">
                                @switch($order->payment_method)
                                    @case('mtn') 📱 MTN @break
                                    @case('airtel') 📱 Airtel @break
                                    @case('zamtel') 📱 Zamtel @break
                                    @case('wallet') 💰 Wallet @break
                                    @default {{ ucfirst($order->payment_method ?? 'N/A') }}
                                @endswitch
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Payment Status</div>
                            <div class="info-value">
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($order->payment_status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Details -->
                    <div class="mt-3">
                        <h6 class="mb-2">Pickup Details</h6>
                        <p class="mb-1"><strong>{{ $order->pickupLocation->name ?? 'N/A' }}</strong></p>
                        <p class="text-muted small mb-2">{{ $order->pickupLocation->address ?? 'N/A' }}</p>
                        @if($order->pickup_scheduled_at)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">
                                <i class="far fa-calendar me-1"></i>
                                {{ $order->pickup_scheduled_at->format('M j, Y') }}
                            </span>
                            <span class="text-muted small">
                                <i class="far fa-clock me-1"></i>
                                {{ $order->pickup_scheduled_at->format('g:i A') }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Delivery Details -->
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="mb-2">Delivery Details</h6>
                        <p class="mb-1"><strong>{{ $order->deliveryLocation->name ?? 'N/A' }}</strong></p>
                        <p class="text-muted small mb-2">{{ $order->deliveryLocation->address ?? 'N/A' }}</p>
                        @if($order->delivery_scheduled_at)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $order->delivery_scheduled_at->format('M j, Y') }}
                                </span>
                                <span class="text-muted small">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $order->delivery_scheduled_at->format('g:i A') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Need Help? -->
            <div class="tracking-card">
                <div class="tracking-card-body">
                    <div class="text-center">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h6>Need Help?</h6>
                        <p class="text-muted small mb-3">Contact our support team</p>
                        <a href="tel:+260123456789" class="btn btn-outline btn-sm w-100">
                            <i class="fas fa-phone me-2"></i>Call Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refresh Button -->
<button class="refresh-btn" onclick="refreshTracking()" title="Refresh Status">
    <i class="fas fa-sync-alt"></i>
</button>

@push('scripts')
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map = null;
let driverMarker = null;
let pickupMarker = null;
let deliveryMarker = null;
let hasDriver = @json($order->driver !== null);

document.addEventListener('DOMContentLoaded', function() {
    @if(in_array($order->status, ['driver_assigned', 'out_for_delivery']) && $order->driver)
        initializeMap();
        startLiveTracking();
    @endif
});

function initializeMap() {
    const defaultCenter = [-15.3875259, 28.3228165]; // Lusaka coordinates
    
    map = L.map('live-map').setView(defaultCenter, 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);
    
    // Add pickup marker
    @if($order->pickupLocation)
    pickupMarker = L.marker([
        {{ $order->pickupLocation->latitude ?? -15.3875259 }},
        {{ $order->pickupLocation->longitude ?? 28.3228165 }}
    ], {
        icon: L.divIcon({
            className: 'custom-marker pickup',
            html: '<i class="fas fa-map-marker-alt" style="color: white;"></i>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        })
    }).addTo(map).bindPopup('Pickup Location: {{ $order->pickupLocation->name ?? "Pickup" }}');
    @endif
    
    // Add delivery marker
    @if($order->deliveryLocation)
    deliveryMarker = L.marker([
        {{ $order->deliveryLocation->latitude ?? -15.3875259 }},
        {{ $order->deliveryLocation->longitude ?? 28.3228165 }}
    ], {
        icon: L.divIcon({
            className: 'custom-marker delivery',
            html: '<i class="fas fa-flag-checkered" style="color: white;"></i>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        })
    }).addTo(map).bindPopup('Delivery Location: {{ $order->deliveryLocation->name ?? "Delivery" }}');
    @endif
    
    // Fit bounds to show all markers
    const markers = [];
    @if($order->pickupLocation) markers.push(pickupMarker); @endif
    @if($order->deliveryLocation) markers.push(deliveryMarker); @endif
    
    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.2));
    }
}

function startLiveTracking() {
    // Simulate driver location updates
    setInterval(updateDriverLocation, 30000);
}

function updateDriverLocation() {
    @if($order->driver)
        // For demo purposes, simulate driver movement
        // In production, you would fetch real driver location from server
        simulateDriverMovement();
    @endif
}

function updateDriverMarker(lat, lng) {
    if (!map) return;
    
    @if($order->driver)
    if (driverMarker) {
        driverMarker.setLatLng([lat, lng]);
    } else {
        driverMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'custom-marker driver',
                html: '<i class="fas fa-motorcycle" style="color: white;"></i>',
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        }).addTo(map).bindPopup('Driver: {{ $order->driver->name ?? "Driver" }}');
    }
    
    // Center map on driver
    map.setView([lat, lng], 15);
    @endif
}

function simulateDriverMovement() {
    @if($order->driver && $order->pickupLocation && $order->deliveryLocation)
    if (!driverMarker) {
        driverMarker = L.marker([
            {{ $order->pickupLocation->latitude ?? -15.3875259 }},
            {{ $order->pickupLocation->longitude ?? 28.3228165 }}
        ], {
            icon: L.divIcon({
                className: 'custom-marker driver',
                html: '<i class="fas fa-motorcycle" style="color: white;"></i>',
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        }).addTo(map).bindPopup('Driver: {{ $order->driver->name ?? "Driver" }}');
    }
    
    // Simulate movement towards delivery location
    const deliveryLat = {{ $order->deliveryLocation->latitude ?? -15.3875259 }};
    const deliveryLng = {{ $order->deliveryLocation->longitude ?? 28.3228165 }};
    
    const currentPos = driverMarker.getLatLng();
    const newLat = currentPos.lat + (deliveryLat - currentPos.lat) * 0.1;
    const newLng = currentPos.lng + (deliveryLng - currentPos.lng) * 0.1;
    
    driverMarker.setLatLng([newLat, newLng]);
    map.setView([newLat, newLng], 15);
    @endif
}

function refreshTracking() {
    const btn = document.querySelector('.refresh-btn i');
    btn.classList.add('fa-spin');
    
    // Reload the page to get latest status
    setTimeout(() => {
        location.reload();
    }, 500);
}

// Auto refresh every 60 seconds if order is not completed or cancelled
@if(!in_array($order->status, ['delivered', 'cancelled']))
    setInterval(() => {
        refreshTracking();
    }, 60000);
@endif
</script>
@endpush
@endsection