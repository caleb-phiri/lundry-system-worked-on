@extends('layouts.driver')

@section('title', 'Order #' . $order->order_number)

@push('styles')
<style>
/* Order Detail Specific Styles */
.order-header {
    background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-600) 100%);
    color: white;
    padding: var(--space-6);
    border-radius: var(--radius-xl);
    margin-bottom: var(--space-6);
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
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    margin-bottom: var(--space-3);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    flex-wrap: wrap;
}

.order-status-badge {
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-full);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-semibold);
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
}

.detail-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: all var(--transition-base);
    height: 100%;
}

.detail-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.card-header-custom {
    padding: var(--space-4) var(--space-6);
    background: linear-gradient(135deg, var(--color-gray-50) 0%, var(--color-white) 100%);
    border-bottom: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    font-weight: var(--font-weight-semibold);
}

.card-header-custom i {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-primary-100);
    color: var(--color-primary-600);
    border-radius: var(--radius-lg);
    font-size: var(--font-size-base);
}

.card-body-custom {
    padding: var(--space-6);
}

.info-item {
    display: flex;
    margin-bottom: var(--space-4);
    align-items: flex-start;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: var(--color-gray-100);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-primary-600);
    margin-right: var(--space-4);
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: var(--space-1);
}

.info-value {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    line-height: var(--line-height-normal);
}

.info-value a {
    color: var(--color-primary-600);
    text-decoration: none;
}

.info-value a:hover {
    text-decoration: underline;
}

/* Action Buttons */
.action-btn {
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    font-weight: var(--font-weight-semibold);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    border: none;
    width: 100%;
    margin-bottom: var(--space-3);
    transition: all var(--transition-base);
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
    font-size: var(--font-size-lg);
}

.action-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.action-btn:disabled::before {
    display: none;
}

.btn-accept {
    background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success-dark) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-accept:hover:not(:disabled) {
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    transform: translateY(-2px);
}

.btn-collect {
    background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-600) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-collect:hover:not(:disabled) {
    box-shadow: 0 6px 16px rgba(67, 97, 238, 0.4);
    transform: translateY(-2px);
}

.btn-deliver {
    background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success-dark) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-deliver:hover:not(:disabled) {
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    transform: translateY(-2px);
}

.btn-call {
    background: linear-gradient(135deg, var(--color-info) 0%, var(--color-info-dark) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-call:hover:not(:disabled) {
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    transform: translateY(-2px);
}

.btn-whatsapp {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
}

.btn-whatsapp:hover:not(:disabled) {
    box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4);
    transform: translateY(-2px);
}

.btn-navigate {
    background: linear-gradient(135deg, var(--color-warning) 0%, var(--color-warning-dark) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-navigate:hover:not(:disabled) {
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    transform: translateY(-2px);
}

/* Stepper */
.stepper {
    display: flex;
    align-items: center;
    position: relative;
    margin-bottom: var(--space-6);
}

.stepper::before {
    content: '';
    position: absolute;
    top: 28px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--color-gray-200);
    z-index: 1;
    border-radius: 2px;
}

.stepper-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    padding: var(--space-4) 0;
}

.stepper-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: var(--color-gray-400);
    background: var(--color-white);
    border: 3px solid var(--color-gray-200);
    transition: all var(--transition-base);
    margin-bottom: var(--space-3);
    box-shadow: var(--shadow-sm);
}

.stepper-item.completed .stepper-circle {
    background: var(--color-success);
    border-color: var(--color-success);
    color: var(--color-white);
    box-shadow: 0 0 0 6px var(--color-success-light);
}

.stepper-item.active .stepper-circle {
    background: var(--color-primary-500);
    border-color: var(--color-primary-500);
    color: var(--color-white);
    box-shadow: 0 0 0 6px var(--color-primary-100);
    animation: pulse 2s infinite;
}

.stepper-item.current .stepper-circle {
    background: var(--color-warning);
    border-color: var(--color-warning);
    color: var(--color-white);
    box-shadow: 0 0 0 6px var(--color-warning-light);
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 var(--color-primary-100); }
    70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.stepper-label {
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-500);
    text-align: center;
    white-space: nowrap;
    max-width: 100px;
}

.stepper-item.completed .stepper-label,
.stepper-item.active .stepper-label,
.stepper-item.current .stepper-label {
    color: var(--color-gray-900);
}

.stepper-time {
    font-size: var(--font-size-xs);
    color: var(--color-gray-400);
    margin-top: var(--space-1);
    text-align: center;
}

/* Timeline */
.timeline {
    position: relative;
    padding: var(--space-2) 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 24px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--color-primary-100), var(--color-gray-200));
    z-index: 1;
}

.timeline-item {
    position: relative;
    padding-left: 4rem;
    margin-bottom: var(--space-5);
    z-index: 2;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 14px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--color-white);
    border: 3px solid var(--color-primary-500);
    z-index: 3;
    transition: all var(--transition-base);
}

.timeline-item.completed .timeline-marker {
    background: var(--color-success);
    border-color: var(--color-success);
    box-shadow: 0 0 0 4px var(--color-success-light);
}

.timeline-item.active .timeline-marker {
    background: var(--color-primary-500);
    border-color: var(--color-primary-500);
    box-shadow: 0 0 0 4px var(--color-primary-100);
    animation: pulse 2s infinite;
}

.timeline-item.current .timeline-marker {
    background: var(--color-warning);
    border-color: var(--color-warning);
    box-shadow: 0 0 0 4px var(--color-warning-light);
}

.timeline-content {
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--space-3) var(--space-4);
    transition: all var(--transition-base);
}

.timeline-item:hover .timeline-content {
    background: var(--color-white);
    box-shadow: var(--shadow-sm);
}

.timeline-time {
    display: flex;
    align-items: center;
    gap: var(--space-1);
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    margin-top: var(--space-2);
}

.timeline-note {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    font-style: italic;
    margin-top: var(--space-2);
}

/* Quick Stats */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-3);
    margin-bottom: var(--space-4);
}

.quick-stat {
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    text-align: center;
    transition: all var(--transition-base);
}

.quick-stat:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-sm);
}

.quick-stat-value {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-primary-600);
    line-height: 1;
    margin-bottom: var(--space-1);
}

.quick-stat-label {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Map Placeholder */
.map-placeholder {
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-gray-200) 100%);
    height: 150px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-gray-500);
    font-size: var(--font-size-sm);
    gap: var(--space-2);
    cursor: pointer;
    transition: all var(--transition-base);
    margin-top: var(--space-4);
    border: 2px dashed var(--color-gray-300);
}

.map-placeholder:hover {
    background: linear-gradient(135deg, var(--color-primary-50) 0%, var(--color-primary-100) 100%);
    color: var(--color-primary-600);
    border-color: var(--color-primary-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.map-placeholder:focus {
    outline: 2px solid var(--color-primary-500);
    outline-offset: 2px;
}

/* Special Items */
.special-item {
    background: linear-gradient(135deg, var(--color-warning-light), #fff3cd);
    color: var(--color-warning-dark);
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
    margin-right: var(--space-2);
    margin-bottom: var(--space-2);
}

/* Action Card */
.action-card {
    background: var(--card-bg);
    border: none;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.action-card .card-header-custom {
    background: var(--color-gray-50);
}

.action-earnings {
    background: linear-gradient(135deg, var(--color-primary-50) 0%, var(--color-primary-100) 100%);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    text-align: center;
    margin-bottom: var(--space-4);
}

.action-earnings-label {
    font-size: var(--font-size-xs);
    color: var(--color-primary-600);
    font-weight: var(--font-weight-semibold);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: var(--space-1);
}

.action-earnings-value {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-primary-700);
}

/* Status Alerts */
.status-alert {
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    font-size: var(--font-size-sm);
    display: flex;
    align-items: center;
    gap: var(--space-2);
    margin-top: var(--space-3);
}

.status-alert-info {
    background: var(--color-info-light);
    color: var(--color-info-dark);
    border-left: 4px solid var(--color-info);
}

.status-alert-warning {
    background: var(--color-warning-light);
    color: var(--color-warning-dark);
    border-left: 4px solid var(--color-warning);
}

.status-alert-success {
    background: var(--color-success-light);
    color: var(--color-success-dark);
    border-left: 4px solid var(--color-success);
}

/* Share Button */
.btn-share {
    background: var(--color-gray-100);
    color: var(--color-gray-700);
    border: 1px solid var(--color-gray-200);
}

.btn-share:hover:not(:disabled) {
    background: var(--color-gray-200);
    color: var(--color-primary-600);
    border-color: var(--color-primary-300);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .order-header {
        padding: var(--space-4);
    }
    .order-number {
        font-size: var(--font-size-xl);
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-2);
    }
    .card-header-custom {
        padding: var(--space-3) var(--space-4);
    }
    .card-body-custom {
        padding: var(--space-4);
    }
    .info-item {
        margin-bottom: var(--space-3);
    }
    .info-icon {
        width: 36px;
        height: 36px;
        font-size: var(--font-size-base);
    }
    .info-value {
        font-size: var(--font-size-sm);
    }
    .action-btn {
        padding: var(--space-3);
        font-size: var(--font-size-sm);
    }
    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    .stepper-circle {
        width: 44px;
        height: 44px;
        font-size: var(--font-size-base);
    }
    .stepper-label {
        font-size: 10px;
        max-width: 80px;
    }
    .timeline-item {
        padding-left: 3.5rem;
    }
    .timeline-marker {
        left: 12px;
        width: 16px;
        height: 16px;
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
        font-size: var(--font-size-xs);
    }
    .stepper::before {
        top: 22px;
    }
    .stepper-circle {
        width: 40px;
        height: 40px;
    }
    .stepper-label {
        font-size: 9px;
        max-width: 70px;
    }
}

/* Print Styles */
@media print {
    .action-btn, .map-placeholder, .btn-share, .sidebar, .navbar-top {
        display: none !important;
    }
    .detail-card {
        box-shadow: none;
        border: 1px solid #ddd;
        break-inside: avoid;
    }
    .order-header {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .main-content {
        margin: 0;
        padding: 0;
    }
}

/* Skeleton Loading */
.skeleton {
    background: linear-gradient(90deg, var(--color-gray-200) 25%, var(--color-gray-100) 50%, var(--color-gray-200) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: var(--radius-base);
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Ripple Effect for Buttons */
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.btn-ripple {
    position: relative;
    overflow: hidden;
}

.btn-ripple::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: rgba(255,255,255,0.5);
    border-radius: 50%;
    transform: scale(0);
    animation: ripple 0.6s ease-out;
    pointer-events: none;
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Order Header -->
    <div class="order-header" data-aos="fade-down">
        <div class="order-number">
            <span>Order #{{ $order->order_number }}</span>
            <span class="order-status-badge">
                <i class="fas fa-circle" style="font-size: 6px;"></i>
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
        <div class="d-flex flex-wrap gap-4 text-primary-100">
            <div class="d-flex align-items-center gap-2">
                <i class="far fa-calendar"></i>
                <span>Placed: {{ $order->created_at->format('M j, Y g:i A') }}</span>
            </div>
            @if($order->pickup_scheduled_at)
            <div class="d-flex align-items-center gap-2">
                <i class="far fa-clock"></i>
                <span>Pickup: {{ $order->pickup_scheduled_at->format('M j, g:i A') }}</span>
            </div>
            @endif
            @if($order->distance_km)
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-route"></i>
                <span>{{ $order->distance_km }} km</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Workflow Stepper -->
    @if($order->driver_id == auth()->id() || !$order->driver_id)
    <div class="card mb-6" data-aos="fade-up">
        <div class="card-body p-4">
            <div class="stepper">
                <div class="stepper-item @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected', 'out_for_delivery', 'delivered'])) completed @elseif(!$order->driver_id) @else active @endif">
                    <div class="stepper-circle">
                        @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected', 'out_for_delivery', 'delivered']))
                            <i class="fas fa-check"></i>
                        @else
                            1
                        @endif
                    </div>
                    <div class="stepper-label">Accept</div>
                    @if($order->accepted_at)
                        <div class="stepper-time">{{ \Carbon\Carbon::parse($order->accepted_at)->format('M j, H:i') }}</div>
                    @endif
                </div>
                
                <div class="stepper-item @if(in_array($order->status, ['pickup_scheduled', 'collected', 'out_for_delivery', 'delivered'])) completed @elseif(in_array($order->status, ['driver_assigned'])) active @else @endif">
                    <div class="stepper-circle">
                        @if(in_array($order->status, ['pickup_scheduled', 'collected', 'out_for_delivery', 'delivered']))
                            <i class="fas fa-check"></i>
                        @else
                            2
                        @endif
                    </div>
                    <div class="stepper-label">Pickup</div>
                    @if($order->collected_at)
                        <div class="stepper-time">{{ \Carbon\Carbon::parse($order->collected_at)->format('M j, H:i') }}</div>
                    @endif
                </div>
                
                <div class="stepper-item @if(in_array($order->status, ['out_for_delivery', 'delivered'])) completed @elseif($order->status === 'collected') active @else @endif">
                    <div class="stepper-circle">
                        @if(in_array($order->status, ['out_for_delivery', 'delivered']))
                            <i class="fas fa-check"></i>
                        @else
                            3
                        @endif
                    </div>
                    <div class="stepper-label">En Route</div>
                    @if($order->out_for_delivery_at)
                        <div class="stepper-time">{{ \Carbon\Carbon::parse($order->out_for_delivery_at)->format('M j, H:i') }}</div>
                    @endif
                </div>
                
                <div class="stepper-item @if($order->status === 'delivered') completed @elseif($order->status === 'out_for_delivery') active @else @endif">
                    <div class="stepper-circle">
                        @if($order->status === 'delivered')
                            <i class="fas fa-check"></i>
                        @else
                            4
                        @endif
                    </div>
                    <div class="stepper-label">Delivered</div>
                    @if($order->delivery_completed_at)
                        <div class="stepper-time">{{ \Carbon\Carbon::parse($order->delivery_completed_at)->format('M j, H:i') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Left Column - Main Details -->
        <div class="col-lg-8">
            <!-- Client Information Card -->
            <x-driver.card class="detail-card mb-4" data-aos="fade-up" data-aos-delay="100">
                <x-slot name="header">
                    <i class="fas fa-user"></i>
                    Client Information
                </x-slot>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-user"></i></div>
                                <div class="info-content">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $order->client->name }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-phone"></i></div>
                                <div class="info-content">
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value">
                                        <a href="tel:{{ $order->client->phone }}"><i class="fas fa-phone-alt me-1"></i>{{ $order->client->phone ?? 'N/A' }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                <div class="info-content">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">
                                        <a href="mailto:{{ $order->client->email }}"><i class="fas fa-envelope me-1"></i>{{ $order->client->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-box"></i></div>
                                <div class="info-content">
                                    <div class="info-label">Total Orders</div>
                                    <div class="info-value">{{ $order->client->orders_count ?? 0 }} orders</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-driver.card>

            <!-- Location Details -->
            <div class="row">
                <div class="col-md-6">
                    <x-driver.card class="detail-card mb-4" data-aos="fade-up" data-aos-delay="200">
                        <x-slot name="header">
                            <i class="fas fa-map-marker-alt" style="color: var(--color-primary);"></i>
                            Pickup Location
                        </x-slot>
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

                            <x-driver.map-placeholder 
                                type="pickup" 
                                :address="$order->pickupLocation->address"
                                label="Open Pickup in Maps" 
                            />
                        </div>
                    </x-driver.card>
                </div>

                <div class="col-md-6">
                    <x-driver.card class="detail-card mb-4" data-aos="fade-up" data-aos-delay="300">
                        <x-slot name="header">
                            <i class="fas fa-flag-checkered" style="color: var(--color-success);"></i>
                            Delivery Location
                        </x-slot>
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

                            <x-driver.map-placeholder 
                                type="delivery" 
                                :address="$order->deliveryLocation->address"
                                label="Open Delivery in Maps" 
                            />
                        </div>
                    </x-driver.card>
                </div>
            </div>

            <!-- Special Instructions -->
            @if($order->special_instructions)
            <x-driver.card class="detail-card mb-4" data-aos="fade-up" data-aos-delay="400">
                <x-slot name="header">
                    <i class="fas fa-clipboard-list"></i>
                    Special Instructions
                </x-slot>
                <div class="card-body-custom">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ $order->special_instructions }}
                    </div>
                </div>
            </x-driver.card>
            @endif

            <!-- Special Items -->
            @if(!empty($order->special_items))
            <x-driver.card class="detail-card mb-4" data-aos="fade-up" data-aos-delay="500">
                <x-slot name="header">
                    <i class="fas fa-star"></i>
                    Special Items
                </x-slot>
                <div class="card-body-custom">
                    @foreach($order->special_items as $item)
                        <span class="special-item">
                            <i class="fas fa-tag"></i>
                            {{ is_array($item) ? ($item['name'] ?? 'Special Item') : 'Special Item' }}
                        </span>
                    @endforeach
                </div>
            </x-driver.card>
            @endif
        </div>

        <!-- Right Column - Actions & Timeline -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="quick-stats" data-aos="fade-up" data-aos-delay="100">
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
            <x-driver.card class="action-card mb-4" data-aos="fade-up" data-aos-delay="200">
                <x-slot name="header">
                    <i class="fas fa-tasks"></i>
                    Actions
                </x-slot>
                <div class="card-body-custom">
                    @if(!$order->driver_id)
                        <form action="{{ route('driver.orders.accept', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="action-btn btn-accept btn-ripple">
                                <i class="fas fa-check-circle"></i>
                                Accept This Order
                            </button>
                        </form>
                        <div class="text-center small text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            You'll earn <strong>K{{ number_format($order->total * 0.2, 2) }}</strong> for this delivery
                        </div>
                    @endif

                    @if($order->driver_id == auth()->id())
                        @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled']))
                            <div class="d-grid gap-2">
                                <a href="#" class="action-btn btn-navigate" onclick="openMaps('pickup'); return false;">
                                    <i class="fas fa-navigation"></i>
                                    Navigate to Pickup
                                </a>
                                <form action="{{ route('driver.orders.collected', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="action-btn btn-collect btn-ripple">
                                        <i class="fas fa-box"></i>
                                        Mark as Collected
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($order->status === 'collected')
                            <div class="d-grid gap-2">
                                <a href="#" class="action-btn btn-navigate" onclick="openMaps('delivery'); return false;">
                                    <i class="fas fa-navigation"></i>
                                    Navigate to Delivery
                                </a>
                                <form action="{{ route('driver.orders.out_for_delivery', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="action-btn btn-navigate btn-ripple">
                                        <i class="fas fa-truck-fast"></i>
                                        Start Delivery
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($order->status === 'out_for_delivery')
                            <div class="d-grid gap-2">
                                <a href="#" class="action-btn btn-navigate" onclick="openMaps('delivery'); return false;">
                                    <i class="fas fa-navigation"></i>
                                    Navigate to Delivery
                                </a>
                                <form action="{{ route('driver.orders.delivered', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="action-btn btn-deliver btn-ripple">
                                        <i class="fas fa-flag-checkered"></i>
                                        Mark as Delivered
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected', 'out_for_delivery']))
                            <div class="d-grid gap-2 mt-3">
                                <a href="tel:{{ $order->client->phone }}" class="action-btn btn-call text-decoration-none btn-ripple">
                                    <i class="fas fa-phone"></i>
                                    Call Client
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->client->phone) }}" 
                                   class="action-btn btn-whatsapp text-decoration-none btn-ripple" 
                                   target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                    WhatsApp Client
                                </a>
                            </div>
                        @endif

                        @if($order->status === 'driver_assigned')
                            <div class="status-alert status-alert-warning">
                                <i class="fas fa-clock"></i>
                                Please proceed to pickup location
                            </div>
                        @elseif($order->status === 'pickup_scheduled')
                            <div class="status-alert status-alert-info">
                                <i class="fas fa-calendar-clock"></i>
                                Pickup scheduled for {{ $order->pickup_scheduled_at->format('g:i A') }}
                            </div>
                        @elseif($order->status === 'collected')
                            <div class="status-alert status-alert-info">
                                <i class="fas fa-box"></i>
                                Items collected. Head to laundry facility.
                            </div>
                        @elseif($order->status === 'out_for_delivery')
                            <div class="status-alert status-alert-success">
                                <i class="fas fa-truck-fast"></i>
                                On your way to delivery
                            </div>
                        @endif
                    @endif
                </div>
            </x-driver.card>

            <!-- Status Timeline -->
            <x-driver.card class="detail-card mb-4" data-aos="fade-up" data-aos-delay="300">
                <x-slot name="header">
                    <i class="fas fa-history"></i>
                    Order Timeline
                </x-slot>
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
                            <div class="timeline-item completed">
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
            </x-driver.card>

            <!-- Share Progress -->
            @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected', 'out_for_delivery']))
            <x-driver.card class="detail-card" data-aos="fade-up" data-aos-delay="400">
                <div class="card-body-custom text-center">
                    <button class="btn btn-share w-100 mb-2" onclick="shareProgress()">
                        <i class="fas fa-share-alt me-2"></i>
                        Share Progress with Client
                    </button>
                    <small class="text-muted">
                        Client will receive SMS updates
                    </small>
                </div>
            </x-driver.card>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Open in Maps
function openMaps(type) {
    let address = type === 'pickup' 
        ? '{{ $order->pickupLocation->address }}'
        : '{{ $order->deliveryLocation->address }}';
    
    let encodedAddress = encodeURIComponent(address);
    
    // Detect if mobile device
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
            window.open('maps://?q=' + encodedAddress);
            setTimeout(() => {
                window.open('https://www.google.com/maps/search/?api=1&query=' + encodedAddress, '_blank');
            }, 500);
        } else {
            window.open('geo:0,0?q=' + encodedAddress);
        }
    } else {
        window.open('https://www.google.com/maps/search/?api=1&query=' + encodedAddress, '_blank');
    }
    
    // Track navigation event
    if (window.gtag) {
        gtag('event', 'navigate', { destination: type, order: '{{ $order->order_number }}' });
    }
}

// Share progress
function shareProgress() {
    const status = '{{ ucfirst(str_replace('_', ' ', $order->status)) }}';
    const orderNumber = '{{ $order->order_number }}';
    
    window.toastManager.success('Progress shared with client!', { title: 'Shared' });
    
    // In production, this would trigger an SMS/email to the client
    console.log(`Sharing progress for order ${orderNumber}: ${status}`);
    
    // Simulate API call
    fetch('{{ route('driver.orders.share', $order) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).catch(console.error);
}

// Auto-refresh for active orders
@if(in_array($order->status, ['driver_assigned', 'pickup_scheduled', 'collected', 'out_for_delivery']))
    let refreshTimer = setTimeout(function() {
        location.reload();
    }, 30000);
    
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
    
    // Alt + M to open maps (pickup)
    if (e.altKey && e.key === 'm') {
        e.preventDefault();
        openMaps('pickup');
    }
    
    // Alt + D to open maps (delivery)
    if (e.altKey && e.key === 'd') {
        e.preventDefault();
        openMaps('delivery');
    }
    
    // Alt + A to accept/collect/deliver
    if (e.altKey && e.key === 'a') {
        e.preventDefault();
        const primaryAction = document.querySelector('.action-btn:not(:disabled)');
        if (primaryAction) primaryAction.click();
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

// Add ripple effect to action buttons
document.querySelectorAll('.btn-ripple').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const ripple = document.createElement('span');
        ripple.style.cssText = `
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(255,255,255,0.5);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            left: ${x}px;
            top: ${y}px;
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;
        
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
@endpush
@endsection