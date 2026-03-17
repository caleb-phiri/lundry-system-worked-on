@extends('layouts.driver')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@push('styles')
<style>
    :root {
        --primary: #4361ee;
        --primary-dark: #3730a3;
        --primary-light: #e0e7ff;
        --success: #10b981;
        --success-light: #d1fae5;
        --warning: #f59e0b;
        --warning-light: #fed7aa;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --info: #3b82f6;
        --info-light: #dbeafe;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    /* Order Header */
    .order-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .order-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: rotate(30deg);
    }

    .order-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .order-status-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
    }

    /* Cards */
    .detail-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
    }

    .detail-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
    }

    .card-header-custom {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--light) 0%, white 100%);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
    }

    .card-header-custom i {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    /* Info Items */
    .info-item {
        display: flex;
        margin-bottom: 1rem;
        align-items: flex-start;
    }

    .info-icon {
        width: 32px;
        height: 32px;
        background: var(--light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark);
        line-height: 1.4;
    }

    .info-value a {
        color: var(--primary);
        text-decoration: none;
    }

    .info-value a:hover {
        text-decoration: underline;
    }

    /* Action Buttons */
    .action-btn {
        padding: 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        border: none;
        width: 100%;
        margin-bottom: 0.75rem;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .action-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .action-btn i {
        font-size: 1.1rem;
    }

    .btn-accept {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-collect {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .btn-deliver {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-call {
        background: linear-gradient(135deg, var(--info) 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-disabled {
        background: var(--border);
        color: var(--gray);
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-disabled:hover::before {
        display: none;
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
        background: linear-gradient(to bottom, var(--primary-light), var(--border));
        z-index: 1;
    }

    .timeline-item {
        position: relative;
        padding-left: 3.5rem;
        margin-bottom: 1.5rem;
        z-index: 2;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: 12px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--primary);
        z-index: 3;
        transition: all 0.3s;
    }

    .timeline-item.completed .timeline-marker {
        background: var(--success);
        border-color: var(--success);
        box-shadow: 0 0 0 3px var(--success-light);
    }

    .timeline-item.active .timeline-marker {
        background: var(--primary);
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
        animation: pulse 2s infinite;
    }

    .timeline-item.current .timeline-marker {
        background: var(--warning);
        border-color: var(--warning);
        box-shadow: 0 0 0 3px var(--warning-light);
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 var(--primary-light); }
        70% { box-shadow: 0 0 0 10px rgba(67, 97, 238, 0); }
        100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
    }

    .timeline-content {
        background: var(--light);
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .timeline-time {
        font-size: 0.75rem;
        color: var(--gray);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .timeline-note {
        font-size: 0.75rem;
        color: var(--gray);
        font-style: italic;
        margin-top: 0.25rem;
    }

    /* Quick Stats */
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .quick-stat {
        background: var(--light);
        border-radius: 0.75rem;
        padding: 0.75rem;
        text-align: center;
    }

    .quick-stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        line-height: 1;
    }

    .quick-stat-label {
        font-size: 0.625rem;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Map Placeholder */
    .map-placeholder {
        background: linear-gradient(135deg, var(--light) 0%, var(--border) 100%);
        height: 150px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray);
        font-size: 0.875rem;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1rem;
    }

    .map-placeholder:hover {
        background: linear-gradient(135deg, var(--border) 0%, var(--light) 100%);
        color: var(--dark);
    }

    /* Special Items */
    .special-item {
        background: linear-gradient(135deg, var(--warning-light), #fff3cd);
        color: #856404;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        display: inline-block;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .order-header {
            padding: 1rem;
        }

        .order-number {
            font-size: 1.25rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .card-header-custom {
            padding: 0.75rem 1rem;
        }

        .card-body-custom {
            padding: 1rem;
        }

        .info-item {
            margin-bottom: 0.75rem;
        }

        .info-icon {
            width: 28px;
            height: 28px;
            font-size: 0.875rem;
        }

        .info-value {
            font-size: 0.875rem;
        }

        .action-btn {
            padding: 0.75rem;
            font-size: 0.875rem;
        }

        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .timeline-item {
            padding-left: 3rem;
        }

        .timeline-marker {
            left: 10px;
            width: 14px;
            height: 14px;
        }
    }

    @media (max-width: 480px) {
        .quick-stats {
            grid-template-columns: 1fr;
        }

        .order-status-badge {
            width: 100%;
            text-align: center;
        }

        .map-placeholder {
            height: 120px;
            font-size: 0.75rem;
        }
    }

    /* Print Styles */
    @media print {
        .action-btn, .map-placeholder {
            display: none;
        }

        .detail-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .order-header {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* Loading Animation */
    .skeleton {
        background: linear-gradient(90deg, var(--border) 25%, var(--light) 50%, var(--border) 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 0.5rem;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Order Header -->
    <div class="order-header">
        <div class="order-number">
            <span>Order #{{ $order->order_number }}</span>
            <span class="order-status-badge">
                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <div>
                <i class="far fa-calendar me-2"></i>
                Placed: {{ $order->created_at->format('M j, Y g:i A') }}
            </div>
            @if($order->pickup_scheduled_at)
            <div>
                <i class="far fa-clock me-2"></i>
                Pickup: {{ $order->pickup_scheduled_at->format('M j, g:i A') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Main Details -->
        <div class="col-lg-8">
            <!-- Client Information Card -->
            <div class="detail-card mb-4">
                <div class="card-header-custom">
                    <i class="fas fa-user"></i>
                    Client Information
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $order->client->name }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value">
                                        <a href="tel:{{ $order->client->phone }}">{{ $order->client->phone ?? 'N/A' }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">
                                        <a href="mailto:{{ $order->client->email }}">{{ $order->client->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Total Orders</div>
                                    <div class="info-value">{{ $order->client->orders_count ?? 0 }} orders</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Details -->
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-card mb-4">
                        <div class="card-header-custom">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                            Pickup Location
                        </div>
                        <div class="card-body-custom">
                            <h6 class="fw-bold mb-2">{{ $order->pickupLocation->name }}</h6>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-location-dot me-1"></i>
                                {{ $order->pickupLocation->address }}
                            </p>
                            
                            @if($order->pickupLocation->instructions)
                            <div class="alert alert-info py-2 small mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ $order->pickupLocation->instructions }}
                            </div>
                            @endif

                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-phone-alt text-muted me-1"></i>
                                    <a href="tel:{{ $order->pickupLocation->contact_phone ?? $order->client->phone }}" class="text-decoration-none">
                                        {{ $order->pickupLocation->contact_phone ?? $order->client->phone }}
                                    </a>
                                </div>
                                @if($order->pickup_scheduled_at)
                                <div class="text-muted small">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $order->pickup_scheduled_at->format('g:i A') }}
                                </div>
                                @endif
                            </div>

                            <div class="map-placeholder" onclick="openMaps('pickup')">
                                <i class="fas fa-map-marked-alt"></i>
                                Open in Google Maps
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detailCard mb-4">
                        <div class="card-header-custom">
                            <i class="fas fa-flag-checkered" style="color: var(--success);"></i>
                            Delivery Location
                        </div>
                        <div class="card-body-custom">
                            <h6 class="fw-bold mb-2">{{ $order->deliveryLocation->name }}</h6>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-location-dot me-1"></i>
                                {{ $order->deliveryLocation->address }}
                            </p>
                            
                            @if($order->deliveryLocation->instructions)
                            <div class="alert alert-info py-2 small mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ $order->deliveryLocation->instructions }}
                            </div>
                            @endif

                            <div>
                                <i class="fas fa-phone-alt text-muted me-1"></i>
                                <a href="tel:{{ $order->deliveryLocation->contact_phone ?? $order->client->phone }}" class="text-decoration-none">
                                    {{ $order->deliveryLocation->contact_phone ?? $order->client->phone }}
                                </a>
                            </div>

                            <div class="map-placeholder" onclick="openMaps('delivery')">
                                <i class="fas fa-map-marked-alt"></i>
                                Open in Google Maps
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Special Instructions -->
            @if($order->special_instructions)
            <div class="detail-card mb-4">
                <div class="card-header-custom">
                    <i class="fas fa-clipboard-list"></i>
                    Special Instructions
                </div>
                <div class="card-body-custom">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ $order->special_instructions }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Special Items -->
            @if(!empty($order->special_items))
            <div class="detail-card mb-4">
                <div class="card-header-custom">
                    <i class="fas fa-star"></i>
                    Special Items
                </div>
                <div class="card-body-custom">
                    @foreach($order->special_items as $item)
                        <span class="special-item">
                            <i class="fas fa-tag me-1"></i>
                            {{ is_array($item) ? ($item['name'] ?? 'Special Item') : 'Special Item' }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Actions & Timeline -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="quick-stat">
                    <div class="quick-stat-value">{{ $order->basket->capacity_kg ?? 0 }}kg</div>
                    <div class="quick-stat-label">Basket Size</div>
                </div>
                <div class="quick-stat">
                    <div class="quick-stat-value">K{{ number_format($order->total, 2) }}</div>
                    <div class="quick-stat-label">Total Amount</div>
                </div>
                <div class="quick-stat">
                    <div class="quick-stat-value">K{{ number_format($order->total * 0.2, 2) }}</div>
                    <div class="quick-stat-label">Your Earnings</div>
                </div>
                <div class="quick-stat">
                    <div class="quick-stat-value">{{ count($order->special_items ?? []) }}</div>
                    <div class="quick-stat-label">Special Items</div>
                </div>
            </div>

            <!-- Action Card -->
            <div class="detail-card mb-4">
                <div class="card-header-custom">
                    <i class="fas fa-tasks"></i>
                    Actions
                </div>
                <div class="card-body-custom">
                    @if(!$order->driver_id)
                        <form action="{{ route('driver.orders.accept', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="action-btn btn-accept">
                                <i class="fas fa-check-circle"></i>
                                Accept This Order
                            </button>
                        </form>
                        <div class="text-center small text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            You'll earn K{{ number_format($order->total * 0.2, 2) }} for this delivery
                        </div>
                    @endif

                    @if($order->driver_id == auth()->id())
                        @if($order->status == 'driver_assigned' || $order->status == 'pickup_scheduled')
                            <form action="{{ route('driver.orders.collected', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-btn btn-collect">
                                    <i class="fas fa-box"></i>
                                    Mark as Collected
                                </button>
                            </form>
                        @endif

                        @if($order->status == 'collected' || $order->status == 'out_for_delivery')
                            <form action="{{ route('driver.orders.delivered', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-btn btn-deliver">
                                    <i class="fas fa-flag-checkered"></i>
                                    Mark as Delivered
                                </button>
                            </form>
                        @endif

                        @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected']))
                            <a href="tel:{{ $order->client->phone }}" class="action-btn btn-call text-decoration-none">
                                <i class="fas fa-phone"></i>
                                Call Client
                            </a>
                            
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->client->phone) }}" 
                               class="action-btn btn-call text-decoration-none" 
                               style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);" 
                               target="_blank">
                                <i class="fab fa-whatsapp"></i>
                                WhatsApp Client
                            </a>
                        @endif

                        @if($order->status == 'driver_assigned')
                            <div class="alert alert-warning small mt-3 mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Please proceed to pickup location
                            </div>
                        @elseif($order->status == 'collected')
                            <div class="alert alert-info small mt-3 mb-0">
                                <i class="fas fa-box me-2"></i>
                                Head to the laundry facility
                            </div>
                        @elseif($order->status == 'out_for_delivery')
                            <div class="alert alert-success small mt-3 mb-0">
                                <i class="fas fa-truck me-2"></i>
                                On your way to delivery
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="detailCard">
                <div class="card-header-custom">
                    <i class="fas fa-history"></i>
                    Order Timeline
                </div>
                <div class="card-body-custom">
                    <div class="timeline">
                        @php
                            $trackingHistory = [];
                            if (!empty($order->tracking_history)) {
                                if (is_string($order->tracking_history)) {
                                    $trackingHistory = json_decode($order->tracking_history, true) ?? [];
                                } elseif (is_array($order->tracking_history)) {
                                    $trackingHistory = $order->tracking_history;
                                }
                            }
                            
                            // Sort by date (oldest first)
                            usort($trackingHistory, function($a, $b) {
                                $dateA = $a['timestamp'] ?? $a['changed_at'] ?? '';
                                $dateB = $b['timestamp'] ?? $b['changed_at'] ?? '';
                                return strtotime($dateA) - strtotime($dateB);
                            });
                        @endphp

                        @forelse($trackingHistory as $history)
                            @php
                                $status = $history['status'] ?? 'Unknown';
                                $timestamp = $history['timestamp'] ?? $history['changed_at'] ?? null;
                                $note = $history['note'] ?? null;
                            @endphp
                            <div class="timeline-item {{ $loop->last ? 'current' : 'completed' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                                    @if($timestamp)
                                        <div class="timeline-time">
                                            <i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($timestamp)->format('M j, Y g:i A') }}
                                        </div>
                                    @endif
                                    @if($note)
                                        <div class="timeline-note">
                                            <i class="fas fa-comment"></i>
                                            {{ $note }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No tracking history available</p>
                            </div>
                        @endforelse

                        <div class="timeline-item active">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Current Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong>
                                <div class="timeline-time">
                                    <i class="far fa-clock"></i>
                                    {{ now()->format('M j, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share Progress -->
            @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected', 'out_for_delivery']))
            <div class="detailCard mt-4">
                <div class="card-body-custom text-center">
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="shareProgress()">
                        <i class="fas fa-share-alt me-2"></i>
                        Share Progress with Client
                    </button>
                    <small class="text-muted">
                        Client will receive SMS updates
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Open in Google Maps
function openMaps(type) {
    let address = type === 'pickup' 
        ? '{{ $order->pickupLocation->address }}'
        : '{{ $order->deliveryLocation->address }}';
    
    let encodedAddress = encodeURIComponent(address);
    
    // Detect if mobile device
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        window.open('geo:0,0?q=' + encodedAddress);
    } else {
        window.open('https://www.google.com/maps/search/?api=1&query=' + encodedAddress, '_blank');
    }
}

// Share progress (simulated)
function shareProgress() {
    const status = '{{ ucfirst(str_replace('_', ' ', $order->status)) }}';
    const orderNumber = '{{ $order->order_number }}';
    
    // Show toast notification
    showToast('Progress shared with client!');
    
    // In production, this would trigger an SMS/email to the client
    console.log(`Sharing progress for order ${orderNumber}: ${status}`);
}

// Toast notification
function showToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
        <div style="position: fixed; bottom: 20px; right: 20px; background: var(--success); color: white; padding: 12px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; animation: slideIn 0.3s ease;">
            <i class="fas fa-check-circle me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Add slide animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);

// Auto refresh page every 30 seconds for active orders
@if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected', 'out_for_delivery']))
    let refreshTimer = setTimeout(function() {
        location.reload();
    }, 30000);
    
    // Clear timer if user navigates away
    window.addEventListener('beforeunload', function() {
        clearTimeout(refreshTimer);
    });
@endif

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + C to call client
    if (e.altKey && e.key === 'c') {
        e.preventDefault();
        window.location.href = 'tel:{{ $order->client->phone }}';
    }
    
    // Alt + M to open maps
    if (e.altKey && e.key === 'm') {
        e.preventDefault();
        openMaps('pickup');
    }
});

// Pull to refresh on mobile
let touchstartY = 0;
document.addEventListener('touchstart', function(e) {
    touchstartY = e.changedTouches[0].screenY;
}, false);

document.addEventListener('touchend', function(e) {
    let touchendY = e.changedTouches[0].screenY;
    let diffY = touchendY - touchstartY;
    
    if (diffY > 100 && window.scrollY === 0) {
        location.reload();
    }
}, false);
</script>
@endpush
@endsection