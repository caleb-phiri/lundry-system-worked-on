@extends('layouts.client')

@section('title', 'Order #' . $order->order_number)

@section('client-content')
<style>
    :root {
        --primary: #0f3b2c;
        --primary-light: #e8f3ef;
        --primary-dark: #0a2c20;
        --secondary: #2c7a4b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --dark: #1e293b;
        --light: #f8fafc;
        --gray: #64748b;
        --border: #e2e8f0;
    }

    /* Page Background */
    .order-details-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 0;
    }

    /* Cards like dashboard */
    .info-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header-custom {
        background: var(--gray-50);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        font-weight: 600;
        font-size: 1rem;
        color: var(--dark);
    }

    .card-header-custom i {
        color: var(--primary);
        margin-right: 0.5rem;
    }

    /* Order Header */
    .order-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 1.5rem;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        color: white;
    }

    .order-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1.25rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.75rem;
        gap: 0.5rem;
    }

    .status-pickup_scheduled { background: #fef3c7; color: #92400e; }
    .status-driver_assigned { background: #dbeafe; color: #1e40af; }
    .status-collected { background: #e0e7ff; color: #3730a3; }
    .status-in_laundry { background: #fce7f3; color: #9d174d; }
    .status-out_for_delivery { background: #fed7aa; color: #9a3412; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    /* Modern Timeline */
    .modern-timeline {
        margin: 1.5rem 0;
    }
    
    .timeline-track {
        position: relative;
        display: flex;
        justify-content: space-between;
        margin: 2rem 0;
    }
    
    .timeline-track::before {
        content: '';
        position: absolute;
        top: 2rem;
        left: 2rem;
        right: 2rem;
        height: 2px;
        background: linear-gradient(90deg, #e2e8f0, #e2e8f0);
        z-index: 1;
    }
    
    .timeline-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 2;
        transition: transform 0.3s ease;
    }
    
    .timeline-step:hover {
        transform: translateY(-5px);
    }
    
    .timeline-dot {
        width: 3rem;
        height: 3rem;
        margin: 0 auto 0.75rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        background: white;
        border: 2px solid var(--border);
        position: relative;
    }
    
    .timeline-dot.completed {
        background: linear-gradient(135deg, var(--success), #059669);
        border-color: var(--success);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .timeline-dot.current {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-color: var(--primary);
        color: white;
        box-shadow: 0 0 0 3px rgba(15, 59, 44, 0.2);
        animation: pulse 2s infinite;
    }
    
    .timeline-dot.pending {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #94a3b8;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(15, 59, 44, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(15, 59, 44, 0); }
        100% { box-shadow: 0 0 0 0 rgba(15, 59, 44, 0); }
    }
    
    .timeline-label {
        font-weight: 600;
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
    }
    
    .timeline-label.completed { color: var(--success); }
    .timeline-label.current { color: var(--primary); }
    .timeline-label.pending { color: #94a3b8; }
    
    .timeline-date {
        font-size: 0.65rem;
        color: #94a3b8;
    }

    /* Progress Bar */
    .progress-container {
        background: #e2e8f0;
        border-radius: 10px;
        height: 6px;
        overflow: hidden;
    }
    
    .progress-fill {
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    /* Location Cards */
    .location-card {
        padding: 1rem;
        border-radius: 1rem;
        background: var(--gray-50);
        height: 100%;
    }
    
    .location-pickup {
        border-left: 3px solid var(--info);
    }
    
    .location-delivery {
        border-left: 3px solid var(--success);
    }

    /* Detail Rows */
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border);
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748b;
    }
    
    .detail-value {
        font-weight: 600;
        color: var(--dark);
    }

    /* Driver Card */
    .driver-card {
        text-align: center;
        padding: 1.5rem;
    }
    
    .driver-avatar {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }
    
    .rating-stars {
        color: #fbbf24;
        margin: 0.5rem 0;
        font-size: 0.8rem;
    }

    /* Buttons */
    .btn-custom {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .btn-custom-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
    }
    
    .btn-custom-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 59, 44, 0.3);
    }
    
    .btn-custom-outline {
        background: white;
        border: 1px solid var(--primary);
        color: var(--primary);
    }
    
    .btn-custom-outline:hover {
        background: var(--primary);
        color: white;
    }

    /* Tracking History */
    .history-timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .history-item {
        position: relative;
        padding-bottom: 1rem;
    }
    
    .history-item::before {
        content: '';
        position: absolute;
        left: -1.2rem;
        top: 0.25rem;
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 50%;
        background: var(--primary);
        border: 2px solid white;
        box-shadow: 0 0 0 2px var(--primary-light);
    }
    
    .history-item::after {
        content: '';
        position: absolute;
        left: -0.95rem;
        top: 1rem;
        width: 1px;
        height: calc(100% - 0.5rem);
        background: linear-gradient(to bottom, var(--primary), #e2e8f0);
    }
    
    .history-item:last-child::after {
        display: none;
    }
    
    .history-date {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }
    
    .history-title {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }
    
    .history-note {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .timeline-dot {
            width: 2.5rem;
            height: 2.5rem;
            font-size: 1rem;
        }
        
        .timeline-label {
            font-size: 0.65rem;
        }
        
        .order-number {
            font-size: 1.2rem;
        }
        
        .order-header {
            padding: 1rem 1.25rem;
        }
    }
</style>

<div class="order-details-container">
    <!-- Order Header -->
    <div class="order-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="order-number">Order #{{ $order->order_number }}</div>
                <p class="opacity-75 mb-0">
                    <i class="far fa-calendar-alt me-2"></i>
                    Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="status-badge status-{{ $order->status }} mb-2">
                    <i class="fas fa-{{ $order->status == 'delivered' ? 'check-circle' : ($order->status == 'cancelled' ? 'times-circle' : 'sync-alt') }}"></i>
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </div>
                @if(in_array($order->status, ['pickup_scheduled', 'driver_assigned']))
                    <form action="{{ route('client.orders.cancel', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <!-- Order Progress -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-chart-line"></i> Order Progress
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="progress-container flex-grow-1" style="max-width: 200px;">
                            <div class="progress-fill" style="width: {{ $progressPercent ?? 0 }}%"></div>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">{{ round($progressPercent ?? 0) }}% Complete</span>
                    </div>
                    
                    @php
                        $statuses = [
                            'pickup_scheduled' => ['label' => 'Pickup', 'icon' => 'fa-calendar-check'],
                            'driver_assigned' => ['label' => 'Driver', 'icon' => 'fa-user-check'],
                            'collected' => ['label' => 'Collected', 'icon' => 'fa-box'],
                            'in_laundry' => ['label' => 'Cleaning', 'icon' => 'fa-tshirt'],
                            'out_for_delivery' => ['label' => 'Delivery', 'icon' => 'fa-truck'],
                            'delivered' => ['label' => 'Delivered', 'icon' => 'fa-check-circle']
                        ];
                        $statusKeys = array_keys($statuses);
                        $currentIndex = array_search($order->status, $statusKeys);
                        $progressPercent = $currentIndex !== false ? (($currentIndex + 1) / count($statuses)) * 100 : 0;
                    @endphp
                    
                    <div class="modern-timeline">
                        <div class="timeline-track">
                            @foreach($statuses as $key => $status)
                                @php
                                    $statusIndex = array_search($key, $statusKeys);
                                    $isCompleted = $statusIndex <= $currentIndex;
                                    $isCurrent = $key === $order->status;
                                @endphp
                                <div class="timeline-step">
                                    <div class="timeline-dot {{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending') }}">
                                        @if($isCompleted && $key !== $order->status)
                                            <i class="fas fa-check"></i>
                                        @else
                                            <i class="fas {{ $status['icon'] }}"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-label {{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending') }}">
                                        {{ $status['label'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Location Details -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-map-marker-alt"></i> Location Details
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="location-card location-pickup">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-box-open text-primary me-2"></i>
                                    <h6 class="mb-0 fw-bold">Pickup Location</h6>
                                </div>
                                <p class="mb-1 fw-semibold small">{{ $order->pickupLocation->name ?? 'N/A' }}</p>
                                <p class="mb-2 text-muted small">{{ $order->pickupLocation->address ?? 'N/A' }}</p>
                                <div class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($order->pickup_scheduled_at)->format('M d, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="location-card location-delivery">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-flag-checkered text-success me-2"></i>
                                    <h6 class="mb-0 fw-bold">Delivery Location</h6>
                                </div>
                                <p class="mb-1 fw-semibold small">{{ $order->deliveryLocation->name ?? 'N/A' }}</p>
                                <p class="mb-2 text-muted small">{{ $order->deliveryLocation->address ?? 'N/A' }}</p>
                                @if($order->delivery_completed_at)
                                    <div class="text-success small">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Delivered {{ \Carbon\Carbon::parse($order->delivery_completed_at)->diffForHumans() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($order->special_instructions)
                        <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded-3 border-start border-warning border-3">
                            <div class="d-flex">
                                <i class="fas fa-info-circle text-warning me-2"></i>
                                <small><strong>Special Instructions:</strong> {{ $order->special_instructions }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Tracking History -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-history"></i> Tracking History
                </div>
                <div class="p-4">
                    @php
                        $history = is_string($order->tracking_history) ? json_decode($order->tracking_history, true) : ($order->tracking_history ?? []);
                    @endphp
                    @if($history && count($history) > 0)
                        <div class="history-timeline">
                            @foreach(array_reverse($history) as $event)
                                <div class="history-item">
                                    <div class="history-date">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($event['timestamp'] ?? $event['changed_at'] ?? now())->format('M d, Y g:i A') }}
                                    </div>
                                    <div class="history-title">
                                        {{ ucfirst(str_replace('_', ' ', $event['status'])) }}
                                    </div>
                                    <div class="history-note">
                                        {{ $event['note'] ?? 'Status updated' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-truck fa-2x text-muted mb-2 opacity-25"></i>
                            <p class="text-muted small mb-0">No tracking history available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <!-- Order Summary -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-receipt"></i> Order Summary
                </div>
                <div class="p-4">
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-2 mb-2">
                            <i class="fas fa-tshirt fa-2x" style="color: var(--primary);"></i>
                        </div>
                        <h6 class="fw-bold mb-0">{{ $order->basket->name ?? 'N/A' }}</h6>
                        <small class="text-muted">{{ $order->basket->capacity_kg ?? 'N/A' }} kg capacity</small>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Subtotal</span>
                        <span class="detail-value">K{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->surcharges > 0)
                        <div class="detail-row">
                            <span class="detail-label">Additional Services</span>
                            <span class="detail-value text-warning">+ K{{ number_format($order->surcharges, 2) }}</span>
                        </div>
                    @endif
                    <div class="detail-row border-bottom-0">
                        <span class="detail-label fw-bold">Total Amount</span>
                        <span class="detail-value fw-bold text-primary fs-6">K{{ number_format($order->total, 2) }}</span>
                    </div>
                    
                    <div class="mt-3 pt-2 border-top">
                        <div class="detail-row">
                            <span class="detail-label">Payment Method</span>
                            <span class="detail-value text-capitalize">{{ $order->payment_method }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Type</span>
                            <span class="detail-value text-capitalize">{{ $order->payment_type }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Status</span>
                            <span class="detail-value">
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'secondary') }} bg-opacity-10 text-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'secondary') }} rounded-pill">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Driver Information -->
            @if($order->driver)
                <div class="info-card">
                    <div class="card-header-custom">
                        <i class="fas fa-user-circle"></i> Delivery Partner
                    </div>
                    <div class="driver-card">
                        <div class="driver-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h6 class="fw-bold mb-0">{{ $order->driver->name }}</h6>
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone-alt me-1 text-primary small"></i>
                            <a href="tel:{{ $order->driver->phone }}" class="text-decoration-none small">{{ $order->driver->phone }}</a>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="tel:{{ $order->driver->phone }}" class="btn btn-custom-primary btn-custom flex-grow-1">
                                <i class="fas fa-phone-alt me-1"></i> Call
                            </a>
                            <button class="btn btn-custom-outline btn-custom flex-grow-1" onclick="showDriverLocation()">
                                <i class="fas fa-map-marker-alt me-1"></i> Track
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Need Help -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-headset"></i> Need Assistance?
                </div>
                <div class="p-4 text-center">
                    <i class="fas fa-life-ring fa-2x text-muted mb-2 opacity-50"></i>
                    <p class="text-muted small mb-3">Having issues? Our support team is here to help.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-custom-outline btn-custom flex-grow-1" onclick="openWhatsApp()">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                        <a href="{{ route('client.help') }}" class="btn btn-custom-primary btn-custom flex-grow-1">
                            <i class="fas fa-question-circle me-1"></i> Help
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDriverLocation() {
    Swal.fire({
        title: 'Driver Location',
        text: 'Your driver is currently on the way to your location!',
        icon: 'info',
        confirmButtonColor: '#0f3b2c',
        confirmButtonText: 'OK'
    });
}

function openWhatsApp() {
    window.open('https://wa.me/260970000000?text=Hello%2C%20I%20need%20help%20with%20my%20order%20%23{{ $order->order_number }}', '_blank');
}

// Auto-refresh order status every 30 seconds if not delivered/cancelled
let autoRefreshInterval = null;

function startAutoRefresh() {
    autoRefreshInterval = setInterval(function() {
        if (document.visibilityState === 'visible') {
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newStatus = doc.querySelector('.status-badge')?.innerHTML;
                const currentStatus = document.querySelector('.status-badge')?.innerHTML;
                
                if (newStatus && newStatus !== currentStatus) {
                    location.reload();
                }
            })
            .catch(error => console.error('Auto-refresh error:', error));
        }
    }, 30000);
}

const orderStatus = "{{ $order->status }}";
if (!['delivered', 'cancelled'].includes(orderStatus)) {
    startAutoRefresh();
}

document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'hidden' && autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
    } else if (document.visibilityState === 'visible' && !autoRefreshInterval && !['delivered', 'cancelled'].includes(orderStatus)) {
        startAutoRefresh();
    }
});
</script>

<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection