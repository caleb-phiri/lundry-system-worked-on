@extends('layouts.client')

@section('title', 'Track Order #' . $order->order_number)

@push('styles')
<style>
    :root {
        --primary: #0f3b2c;
        --primary-dark: #0a2c20;
        --primary-light: #e8f3ef;
        --secondary: #2c7a4b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    /* Page Background */
    .tracking-page-wrapper {
        background: linear-gradient(135deg, #e8f3ef 0%, #d1fae5 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Header Animation */
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(15, 59, 44, 0.3);
        }
        50% {
            box-shadow: 0 0 0 12px rgba(15, 59, 44, 0);
        }
    }

    /* Header */
    .tracking-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.75rem;
        border-radius: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 24px rgba(15, 59, 44, 0.2);
        animation: slideInDown 0.6s ease;
    }

    .order-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.4rem 0.875rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .order-badge:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.25);
    }

    /* Cards */
    .tracking-card {
        background: white;
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease;
    }

    .tracking-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px -8px rgba(0, 0, 0, 0.12);
    }

    .tracking-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .tracking-card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #1e293b;
    }

    .tracking-card-header h5 i {
        color: var(--primary);
    }

    .tracking-card-body {
        padding: 1.25rem;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding: 0.5rem 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, var(--primary), var(--secondary), #e2e8f0);
        z-index: 1;
    }

    .timeline-item {
        position: relative;
        padding-left: 3.5rem;
        margin-bottom: 1.5rem;
        z-index: 2;
        animation: fadeInUp 0.4s ease;
        animation-fill-mode: both;
    }

    .timeline-item:nth-child(1) { animation-delay: 0.05s; }
    .timeline-item:nth-child(2) { animation-delay: 0.1s; }
    .timeline-item:nth-child(3) { animation-delay: 0.15s; }
    .timeline-item:nth-child(4) { animation-delay: 0.2s; }
    .timeline-item:nth-child(5) { animation-delay: 0.25s; }
    .timeline-item:nth-child(6) { animation-delay: 0.3s; }
    .timeline-item:nth-child(7) { animation-delay: 0.35s; }
    .timeline-item:nth-child(8) { animation-delay: 0.4s; }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: 13px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        border: 2px solid #e2e8f0;
        z-index: 3;
        transition: all 0.3s;
    }

    .timeline-item.completed .timeline-marker {
        background: var(--success);
        border-color: var(--success);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        animation: pulseGlow 2s infinite;
    }

    .timeline-item.active .timeline-marker {
        background: var(--primary);
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(15, 59, 44, 0.2);
        animation: pulseGlow 2s infinite;
    }

    .timeline-content {
        background: #f8fafc;
        border-radius: 0.875rem;
        padding: 0.875rem;
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .timeline-time {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    /* Driver Card */
    .driver-card {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .driver-card:hover {
        transform: scale(1.01);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .driver-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        box-shadow: 0 6px 14px rgba(15, 59, 44, 0.2);
        transition: all 0.3s ease;
    }

    .driver-avatar:hover {
        transform: rotate(8deg) scale(1.03);
    }

    .driver-info h4 {
        margin: 0 0 0.25rem;
        font-weight: 700;
        font-size: 1rem;
        color: #1e293b;
    }

    .driver-contact {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .driver-contact a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.875rem;
        background: white;
        border-radius: 2rem;
        transition: all 0.3s ease;
    }

    .driver-contact a:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Live Map */
    .live-map {
        height: 280px;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .live-map:hover {
        border-color: var(--primary);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    /* Status Badges */
    .status-badge {
        padding: 0.3rem 0.875rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-pickup_scheduled { background: #dbeafe; color: #1e40af; }
    .status-driver_assigned { background: #e0e7ff; color: #3730a3; }
    .status-collected { background: #d1fae5; color: #065f46; }
    .status-washing { background: #fed7aa; color: #9a3412; }
    .status-drying { background: #fce7f3; color: #9d174d; }
    .status-folding { background: #f3e8ff; color: #6b21a8; }
    .status-out_for_delivery { background: #fef3c7; color: #92400e; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    /* Progress Bar */
    .progress-container {
        margin: 1rem 0;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .progress-step {
        text-align: center;
        font-size: 0.7rem;
        color: #64748b;
        flex: 1;
        font-weight: 500;
        position: relative;
        padding-bottom: 0.35rem;
    }

    .progress-step.active {
        color: var(--primary);
        font-weight: 700;
    }

    .progress-step.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 2px;
        background: var(--primary);
        border-radius: 2px;
    }

    .progress-bar-custom {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 3px;
        transition: width 0.6s ease;
    }

    /* Refresh Button */
    .refresh-btn {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        box-shadow: 0 6px 16px rgba(15, 59, 44, 0.3);
        transition: all 0.3s;
        z-index: 1000;
    }

    .refresh-btn:hover {
        transform: rotate(180deg) scale(1.08);
        box-shadow: 0 10px 24px rgba(15, 59, 44, 0.4);
    }

    .refresh-btn:active {
        transform: rotate(180deg) scale(0.96);
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .info-item {
        background: #f8fafc;
        border-radius: 0.875rem;
        padding: 0.875rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .info-item:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .info-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .info-value {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.875rem;
    }

    .no-driver-card {
        background: linear-gradient(135deg, #fef3c7, #fffbeb);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
    }

    .no-driver-icon {
        font-size: 2.5rem;
        color: var(--warning);
        margin-bottom: 0.75rem;
        animation: pulseGlow 2s infinite;
    }

    /* Custom Map Markers */
    .custom-marker {
        background: transparent;
        border: none;
    }

    .custom-marker i {
        font-size: 1.75rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 59, 44, 0.3);
    }

    .btn-outline-primary {
        border: 1px solid var(--primary);
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tracking-header {
            padding: 1.25rem;
        }
        
        .progress-step {
            font-size: 0.6rem;
        }
        
        .driver-card {
            flex-direction: column;
            text-align: center;
        }
        
        .driver-contact {
            justify-content: center;
        }
        
        .timeline-item {
            padding-left: 2.8rem;
        }
        
        .timeline::before {
            left: 14px;
        }
        
        .timeline-marker {
            left: 7px;
        }
    }

    @media (max-width: 480px) {
        .progress-steps {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .progress-step.active::after {
            display: none;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .driver-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('client-content')
<div class="tracking-page-wrapper">
    <div class="container">
        <!-- Tracking Header -->
        <div class="tracking-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                        <span class="order-badge">
                            <i class="fas fa-hashtag"></i> Order #{{ $order->order_number }}
                        </span>
                        <span class="order-badge">
                            <i class="far fa-calendar"></i> {{ $order->created_at->format('M d, Y') }}
                        </span>
                        <span class="order-badge">
                            <i class="far fa-clock"></i> {{ $order->created_at->format('g:i A') }}
                        </span>
                    </div>
                    <h3 class="mb-1 fw-bold">Track Your Order</h3>
                    <p class="mb-0 opacity-75 small">Current Status: 
                        <span class="status-badge status-{{ $order->status }} ms-2">
                            <i class="fas fa-{{ $order->status == 'delivered' ? 'check-circle' : ($order->status == 'cancelled' ? 'times-circle' : 'sync-alt') }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('client.orders.show', $order) }}" class="btn btn-light rounded-pill px-3 py-2">
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
                            'pickup_scheduled' => 15,
                            'driver_assigned' => 30,
                            'collected' => 45,
                            'washing' => 60,
                            'drying' => 70,
                            'folding' => 80,
                            'out_for_delivery' => 90,
                            'delivered' => 100
                        ];
                        
                        $currentProgress = $statusFlow[$order->status] ?? 0;
                        
                        $steps = [
                            'pending' => 'Order Placed',
                            'pickup_scheduled' => 'Pickup',
                            'collected' => 'Collected',
                            'washing' => 'Cleaning',
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
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <i class="fas fa-percent me-1"></i>
                            {{ $currentProgress }}% Complete
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-7">
                <!-- Live Map -->
                @if(in_array($order->status, ['driver_assigned', 'out_for_delivery']) && $order->driver)
                    <div class="tracking-card">
                        <div class="tracking-card-header">
                            <h5>
                                <i class="fas fa-map-marked-alt"></i>
                                Live Tracking
                                <span class="badge bg-success ms-2" style="font-size: 0.6rem;">
                                    <i class="fas fa-circle fa-fw me-1" style="font-size: 0.5rem;"></i>
                                    Live
                                </span>
                            </h5>
                        </div>
                        <div class="tracking-card-body">
                            <div id="live-map" class="live-map"></div>
                            <div class="text-center mt-2">
                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-primary me-1" style="font-size: 0.8rem;"></i>
                                        <small class="text-muted">Driver Location</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-flag-checkered text-success me-1" style="font-size: 0.8rem;"></i>
                                        <small class="text-muted">Delivery Point</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-sync-alt fa-spin text-info me-1" style="font-size: 0.8rem;"></i>
                                        <small class="text-muted">Updates every 30s</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <h5>
                            <i class="fas fa-clock"></i>
                            Order Timeline
                        </h5>
                    </div>
                    <div class="tracking-card-body">
                        <div class="timeline">
                            @php
                                $timelineStatuses = [
                                    'pending' => ['label' => 'Order Placed', 'icon' => 'fa-shopping-cart'],
                                    'pickup_scheduled' => ['label' => 'Pickup Scheduled', 'icon' => 'fa-calendar-check'],
                                    'driver_assigned' => ['label' => 'Driver Assigned', 'icon' => 'fa-user-check'],
                                    'collected' => ['label' => 'Laundry Collected', 'icon' => 'fa-box'],
                                    'washing' => ['label' => 'Washing', 'icon' => 'fa-tshirt'],
                                    'drying' => ['label' => 'Drying', 'icon' => 'fa-sun'],
                                    'folding' => ['label' => 'Folding', 'icon' => 'fa-tshirt'],
                                    'out_for_delivery' => ['label' => 'Out for Delivery', 'icon' => 'fa-truck'],
                                    'delivered' => ['label' => 'Delivered', 'icon' => 'fa-check-circle'],
                                    'cancelled' => ['label' => 'Cancelled', 'icon' => 'fa-times-circle']
                                ];
                                
                                $currentStatusIndex = array_search($order->status, array_keys($timelineStatuses)) !== false 
                                    ? array_search($order->status, array_keys($timelineStatuses)) 
                                    : -1;
                            @endphp

                            @foreach($timelineStatuses as $statusKey => $statusData)
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
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <i class="fas {{ $statusData['icon'] }}" style="color: var(--primary); font-size: 0.8rem;"></i>
                                                        <h6 class="mb-0 fw-semibold" style="font-size: 0.85rem;">{{ $statusData['label'] }}</h6>
                                                    </div>
                                                    @if($statusTime)
                                                        <div class="timeline-time">
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ $statusTime->format('M d, Y \a\t g:i A') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($isCompleted && $statusKey !== $order->status)
                                                    <i class="fas fa-check-circle text-success" style="font-size: 1rem;"></i>
                                                @endif
                                                @if($isActive)
                                                    <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.65rem;">Current</span>
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

            <div class="col-lg-5">
                <!-- Driver Information -->
                @if($order->driver)
                    <div class="tracking-card">
                        <div class="tracking-card-header">
                            <h5>
                                <i class="fas fa-motorcycle"></i>
                                Delivery Partner
                            </h5>
                        </div>
                        <div class="tracking-card-body">
                            <div class="driver-card">
                                <div class="driver-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="driver-info">
                                    <h4>{{ $order->driver->name }}</h4>
                                    <div class="rating mb-1">
                                        <i class="fas fa-star text-warning" style="font-size: 0.7rem;"></i>
                                        <i class="fas fa-star text-warning" style="font-size: 0.7rem;"></i>
                                        <i class="fas fa-star text-warning" style="font-size: 0.7rem;"></i>
                                        <i class="fas fa-star text-warning" style="font-size: 0.7rem;"></i>
                                        <i class="fas fa-star-half-alt text-warning" style="font-size: 0.7rem;"></i>
                                        <span class="ms-1 text-muted" style="font-size: 0.65rem;">4.8</span>
                                    </div>
                                    @if($order->driver->phone)
                                    <div class="driver-contact">
                                        <a href="tel:{{ $order->driver->phone }}">
                                            <i class="fas fa-phone" style="font-size: 0.7rem;"></i> Call
                                        </a>
                                        <a href="sms:{{ $order->driver->phone }}">
                                            <i class="fas fa-comment" style="font-size: 0.7rem;"></i> Message
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
                                <h5 class="fw-bold mb-1" style="font-size: 1rem;">Finding a Driver</h5>
                                <p class="text-muted small mb-2">
                                    We're looking for a nearby driver to pick up your laundry.
                                </p>
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-bell me-1" style="font-size: 0.7rem;"></i>
                                    You'll be notified once a driver is assigned
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Order Information -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <h5>
                            <i class="fas fa-info-circle"></i>
                            Order Information
                        </h5>
                    </div>
                    <div class="tracking-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-tshirt me-1"></i> Basket
                                </div>
                                <div class="info-value">{{ $order->basket->name ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-coins me-1"></i> Total
                                </div>
                                <div class="info-value text-primary fw-bold">K{{ number_format($order->total, 2) }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-credit-card me-1"></i> Payment
                                </div>
                                <div class="info-value text-capitalize">{{ $order->payment_method }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-dollar-sign me-1"></i> Status
                                </div>
                                <div class="info-value">
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'secondary') }} rounded-pill" style="font-size: 0.65rem;">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Pickup Details -->
                        <div class="mt-3 p-2 bg-light rounded-3">
                            <h6 class="mb-1 fw-bold" style="font-size: 0.8rem;">
                                <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                Pickup Details
                            </h6>
                            <p class="mb-0 fw-semibold" style="font-size: 0.8rem;">{{ $order->pickupLocation->name ?? 'N/A' }}</p>
                            <p class="text-muted mb-1" style="font-size: 0.7rem;">{{ $order->pickupLocation->address ?? 'N/A' }}</p>
                            @if($order->pickup_scheduled_at)
                            <div class="d-flex justify-content-between align-items-center mt-1 pt-1 border-top">
                                <span class="text-muted" style="font-size: 0.65rem;">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($order->pickup_scheduled_at)->format('M d, Y') }}
                                </span>
                                <span class="text-muted" style="font-size: 0.65rem;">
                                    <i class="far fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($order->pickup_scheduled_at)->format('g:i A') }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Delivery Details -->
                        <div class="mt-2 p-2 bg-light rounded-3">
                            <h6 class="mb-1 fw-bold" style="font-size: 0.8rem;">
                                <i class="fas fa-flag-checkered text-success me-1"></i>
                                Delivery Details
                            </h6>
                            <p class="mb-0 fw-semibold" style="font-size: 0.8rem;">{{ $order->deliveryLocation->name ?? 'N/A' }}</p>
                            <p class="text-muted mb-0" style="font-size: 0.7rem;">{{ $order->deliveryLocation->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="tracking-card">
                    <div class="tracking-card-body">
                        <div class="text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-2 mb-2">
                                <i class="fas fa-headset fa-1x text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-1" style="font-size: 0.9rem;">Need Help?</h5>
                            <p class="text-muted mb-2" style="font-size: 0.7rem;">Our support team is available 24/7</p>
                            <div class="d-grid gap-2">
                                <a href="tel:+260123456789" class="btn btn-primary rounded-pill py-1">
                                    <i class="fas fa-phone me-1"></i>Call Support
                                </a>
                                <a href="#" class="btn btn-outline-primary rounded-pill py-1" onclick="startWhatsApp()">
                                    <i class="fab fa-whatsapp me-1"></i>WhatsApp Support
                                </a>
                            </div>
                        </div>
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
let refreshInterval = null;
let trackingInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    @if(in_array($order->status, ['driver_assigned', 'out_for_delivery']) && $order->driver)
        initializeMap();
        startLiveTracking();
    @endif
});

function initializeMap() {
    const defaultCenter = [-15.3875259, 28.3228165];
    
    map = L.map('live-map').setView(defaultCenter, 14);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap Contributors',
        maxZoom: 19
    }).addTo(map);
    
    const pickupIcon = L.divIcon({
        className: 'custom-marker pickup',
        html: '<i class="fas fa-map-marker-alt"></i>',
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -28]
    });
    
    const deliveryIcon = L.divIcon({
        className: 'custom-marker delivery',
        html: '<i class="fas fa-flag-checkered"></i>',
        iconSize: [28, 26],
        iconAnchor: [14, 28],
        popupAnchor: [0, -28]
    });
    
    @if($order->pickupLocation)
    pickupMarker = L.marker([
        {{ $order->pickupLocation->latitude ?? -15.3875259 }},
        {{ $order->pickupLocation->longitude ?? 28.3228165 }}
    ], { icon: pickupIcon }).addTo(map).bindPopup(`
        <strong>Pickup</strong><br>
        {{ $order->pickupLocation->name ?? 'Pickup' }}
    `);
    @endif
    
    @if($order->deliveryLocation)
    deliveryMarker = L.marker([
        {{ $order->deliveryLocation->latitude ?? -15.3875259 }},
        {{ $order->deliveryLocation->longitude ?? 28.3228165 }}
    ], { icon: deliveryIcon }).addTo(map).bindPopup(`
        <strong>Delivery</strong><br>
        {{ $order->deliveryLocation->name ?? 'Delivery' }}
    `);
    @endif
    
    const markers = [];
    @if($order->pickupLocation) markers.push(pickupMarker); @endif
    @if($order->deliveryLocation) markers.push(deliveryMarker); @endif
    
    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.2));
    }
}

function startLiveTracking() {
    @if($order->pickupLocation)
    updateDriverMarker(
        {{ $order->pickupLocation->latitude ?? -15.3875259 }},
        {{ $order->pickupLocation->longitude ?? 28.3228165 }}
    );
    @endif
    
    trackingInterval = setInterval(updateDriverLocation, 30000);
}

function updateDriverLocation() {
    @if($order->driver && $order->deliveryLocation)
    if (driverMarker) {
        const deliveryLat = {{ $order->deliveryLocation->latitude ?? -15.3875259 }};
        const deliveryLng = {{ $order->deliveryLocation->longitude ?? 28.3228165 }};
        
        const currentPos = driverMarker.getLatLng();
        const distance = Math.sqrt(
            Math.pow(deliveryLat - currentPos.lat, 2) + 
            Math.pow(deliveryLng - currentPos.lng, 2)
        );
        
        if (distance > 0.01) {
            const newLat = currentPos.lat + (deliveryLat - currentPos.lat) * 0.1;
            const newLng = currentPos.lng + (deliveryLng - currentPos.lng) * 0.1;
            updateDriverMarker(newLat, newLng);
        }
    } else {
        updateDriverMarker(
            {{ $order->pickupLocation->latitude ?? -15.3875259 }},
            {{ $order->pickupLocation->longitude ?? 28.3228165 }}
        );
    }
    @endif
}

function updateDriverMarker(lat, lng) {
    if (!map) return;
    
    const driverIcon = L.divIcon({
        className: 'custom-marker driver',
        html: '<i class="fas fa-motorcycle"></i>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });
    
    if (driverMarker) {
        driverMarker.setLatLng([lat, lng]);
    } else {
        driverMarker = L.marker([lat, lng], { icon: driverIcon })
            .addTo(map)
            .bindPopup(`
                <strong>Your Driver</strong><br>
                {{ $order->driver->name ?? 'Driver' }}
            `);
    }
    
    driverMarker.openPopup();
}

function refreshTracking() {
    const btn = document.querySelector('.refresh-btn i');
    btn.classList.add('fa-spin');
    
    setTimeout(() => {
        location.reload();
    }, 600);
}

function startWhatsApp() {
    const message = encodeURIComponent('Hello, I need help with my order #{{ $order->order_number }}');
    window.open(`https://wa.me/260970000000?text=${message}`, '_blank');
}

@if(!in_array($order->status, ['delivered', 'cancelled']))
    refreshInterval = setInterval(() => {
        refreshTracking();
    }, 60000);
@endif

window.addEventListener('beforeunload', function() {
    if (refreshInterval) clearInterval(refreshInterval);
    if (trackingInterval) clearInterval(trackingInterval);
});
</script>
@endpush
@endsection