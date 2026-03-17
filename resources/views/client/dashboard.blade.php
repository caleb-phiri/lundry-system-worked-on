@extends('layouts.client')

@section('title', 'Client Dashboard')

@push('styles')
<style>
    :root {
        --primary: #4361ee;
        --primary-dark: #3730a3;
        --primary-light: #e0e7ff;
        --secondary: #0ea5e9;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: #f1f5f9;
        color: var(--dark);
    }

    /* Dashboard Header */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        color: white;
        border-radius: 0 0 2rem 2rem;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
    }

    .dashboard-header h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .dashboard-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .wallet-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.75rem 1.5rem;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-weight: 600;
        font-size: 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Referral Banner */
    .referral-banner {
        background: linear-gradient(135deg, #fcd34d 0%, #fbbf24 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        color: #92400e;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .referral-code-wrapper {
        background: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        border: 2px dashed #92400e;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .referral-code {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 1px;
    }

    .copy-btn {
        background: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        color: #92400e;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .copy-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        overflow: hidden;
        border: 1px solid var(--border);
        margin-bottom: 2rem;
    }

    .form-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }

    .form-header h4 {
        margin: 0;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-body {
        padding: 2rem;
    }

    /* Step Indicator */
    .step-indicator {
        display: flex;
        margin-bottom: 2.5rem;
        position: relative;
        padding: 0 1rem;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--border);
        z-index: 1;
    }

    .step-item {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .step-number {
        width: 48px;
        height: 48px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: 600;
        color: var(--gray);
        transition: all 0.3s;
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray);
    }

    .step-item.active .step-number {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    .step-item.active .step-label {
        color: var(--primary);
        font-weight: 600;
    }

    .step-item.completed .step-number {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .step-item.completed .step-label {
        color: var(--success);
    }

    /* Maps */
    .map-wrapper {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid var(--border);
        margin-bottom: 1rem;
    }

    .map-container {
        height: 250px;
        width: 100%;
    }

    .map-overlay {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .map-overlay i {
        color: var(--primary);
    }

    /* Search Box */
    .search-box {
        position: relative;
        margin-bottom: 1rem;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        z-index: 10;
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
        border: 1px solid var(--border);
        border-radius: 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: var(--light);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    /* Location Cards */
    .location-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid var(--border);
        border-radius: 1rem;
        background: white;
    }

    .location-item {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .location-item:last-child {
        border-bottom: none;
    }

    .location-item:hover {
        background: var(--light);
    }

    .location-item.selected {
        background: var(--primary-light);
        border-left: 4px solid var(--primary);
    }

    .location-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .location-info h6 {
        margin: 0 0 0.25rem;
        font-weight: 600;
        color: var(--dark);
    }

    .location-info p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--gray);
    }

    /* Basket Cards */
    .basket-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .basket-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .basket-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary);
        box-shadow: 0 12px 20px -8px rgba(67, 97, 238, 0.2);
    }

    .basket-card.selected {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .basket-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
    }

    .basket-name {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .basket-capacity {
        font-size: 0.875rem;
        color: var(--gray);
        margin-bottom: 1rem;
    }

    .basket-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .basket-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: var(--light);
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gray);
    }

    /* Special Items */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .item-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .item-card:hover {
        border-color: var(--primary);
        background: var(--light);
    }

    .item-checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.2s;
    }

    .item-card.selected .item-checkbox {
        background: var(--primary);
        border-color: var(--primary);
    }

    .item-info {
        flex: 1;
    }

    .item-info h6 {
        margin: 0 0 0.25rem;
        font-weight: 600;
    }

    .item-price {
        font-size: 0.875rem;
        color: var(--success);
        font-weight: 600;
    }

    /* Price Summary */
    .price-summary {
        background: linear-gradient(135deg, var(--dark) 0%, #0f172a 100%);
        border-radius: 1rem;
        padding: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .price-total {
        display: flex;
        justify-content: space-between;
        padding-top: 1rem;
        font-size: 1.25rem;
        font-weight: 700;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid var(--border);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .stat-icon.primary {
        background: var(--primary-light);
        color: var(--primary);
    }

    .stat-icon.success {
        background: #d1fae5;
        color: var(--success);
    }

    .stat-icon.info {
        background: #e0f2fe;
        color: var(--secondary);
    }

    .stat-icon.warning {
        background: #fed7aa;
        color: var(--warning);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
    }

    /* Buttons */
    .btn-gradient {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 100px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 6px -1px rgba(67, 97, 238, 0.2);
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);
    }

    .btn-outline {
        background: white;
        border: 1px solid var(--border);
        color: var(--gray);
        padding: 0.75rem 2rem;
        border-radius: 100px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--light);
    }

    /* Recent Orders */
    .orders-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .orders-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }

    .orders-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .order-item:hover {
        background: var(--light);
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-info h6 {
        margin: 0 0 0.25rem;
        font-weight: 600;
    }

    .order-meta {
        font-size: 0.875rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-status {
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .order-status.pending { background: #fff3cd; color: #856404; }
    .order-status.processing { background: #cfe2ff; color: #084298; }
    .order-status.completed { background: #d1e7dd; color: #0f5132; }
    .order-status.cancelled { background: #f8d7da; color: #842029; }

    .order-amount {
        font-weight: 700;
        color: var(--primary);
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
        color: var(--dark);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    .payment-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .payment-option {
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .payment-option:hover {
        border-color: var(--primary);
    }

    .payment-option.selected {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    /* Toast Notifications */
    .toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-left: 4px solid var(--success);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-header h1 {
            font-size: 1.75rem;
        }
        
        .step-label {
            font-size: 0.75rem;
        }
        
        .step-number {
            width: 36px;
            height: 36px;
            font-size: 0.875rem;
        }
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
@endpush

@section('client-content')
<!-- Dashboard Header -->
<div class="dashboard-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p>Ready to freshen up your laundry? Let's get started!</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="wallet-badge">
                    <i class="fas fa-wallet"></i>
                    <span>K{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Referral Banner -->
    <div class="referral-banner">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="d-flex align-items-center gap-3">
                    <div style="font-size: 2rem;">🎁</div>
                    <div>
                        <h5 class="mb-1">Refer & Earn K20!</h5>
                        <p class="mb-0">Share your code with friends and earn when they order</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="referral-code-wrapper">
                    <span class="referral-code">{{ auth()->user()->referral_code }}</span>
                    <button class="copy-btn" onclick="copyReferralCode()">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Order Form -->
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h4>
                        <i class="fas fa-plus-circle" style="color: var(--primary);"></i>
                        Create New Order
                    </h4>
                </div>
                <div class="form-body">
                    <form action="{{ route('client.orders.store') }}" method="POST" id="orderForm">
                        @csrf
                        
                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step-item active" id="step1-indicator">
                                <div class="step-number">1</div>
                                <div class="step-label">Location</div>
                            </div>
                            <div class="step-item" id="step2-indicator">
                                <div class="step-number">2</div>
                                <div class="step-label">Basket</div>
                            </div>
                            <div class="step-item" id="step3-indicator">
                                <div class="step-number">3</div>
                                <div class="step-label">Extras</div>
                            </div>
                            <div class="step-item" id="step4-indicator">
                                <div class="step-number">4</div>
                                <div class="step-label">Payment</div>
                            </div>
                        </div>
                        
                        <!-- Step 1: Location Selection -->
                        <div id="step1" class="form-step">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">
                                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                                        Pickup Location
                                    </h6>
                                    
                                    <div class="map-wrapper">
                                        <div id="pickup-map" class="map-container"></div>
                                        <div class="map-overlay">
                                            <i class="fas fa-hand-pointer"></i>
                                            Click map to set location
                                        </div>
                                    </div>
                                    
                                    <div class="search-box">
                                        <i class="fas fa-search"></i>
                                        <input type="text" 
                                               id="pickup-search" 
                                               placeholder="Search for pickup location...">
                                        <div id="pickup-suggestions" class="suggestions-dropdown"></div>
                                    </div>
                                    
                                    <div class="location-list" id="pickup-locations">
                                        @forelse(auth()->user()->locations as $location)
                                            <div class="location-item" 
                                                 onclick="selectPickupLocation({{ $location->id }})"
                                                 data-pickup-id="{{ $location->id }}"
                                                 data-pickup-name="{{ $location->name }}"
                                                 data-pickup-address="{{ $location->address }}"
                                                 data-pickup-lat="{{ $location->latitude ?? -15.3875259 }}"
                                                 data-pickup-lng="{{ $location->longitude ?? 28.3228165 }}">
                                                <div class="location-icon">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                                <div class="location-info">
                                                    <h6>{{ $location->name }}</h6>
                                                    <p>{{ $location->address }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <p class="text-muted mb-3">No saved locations</p>
                                                <a href="{{ route('client.locations.index') }}" class="btn btn-outline">
                                                    <i class="fas fa-plus"></i> Add Location
                                                </a>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    <input type="hidden" name="pickup_location_id" id="pickup_location_id" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="mb-3">
                                        <i class="fas fa-flag-checkered" style="color: var(--success);"></i>
                                        Delivery Location
                                    </h6>
                                    
                                    <div class="map-wrapper">
                                        <div id="delivery-map" class="map-container"></div>
                                        <div class="map-overlay">
                                            <i class="fas fa-hand-pointer"></i>
                                            Click map to set location
                                        </div>
                                    </div>
                                    
                                    <div class="search-box">
                                        <i class="fas fa-search"></i>
                                        <input type="text" 
                                               id="delivery-search" 
                                               placeholder="Search for delivery location...">
                                        <div id="delivery-suggestions" class="suggestions-dropdown"></div>
                                    </div>
                                    
                                    <div class="location-list" id="delivery-locations">
                                        @forelse(auth()->user()->locations as $location)
                                            <div class="location-item" 
                                                 onclick="selectDeliveryLocation({{ $location->id }})"
                                                 data-delivery-id="{{ $location->id }}"
                                                 data-delivery-name="{{ $location->name }}"
                                                 data-delivery-address="{{ $location->address }}"
                                                 data-delivery-lat="{{ $location->latitude ?? -15.3875259 }}"
                                                 data-delivery-lng="{{ $location->longitude ?? 28.3228165 }}">
                                                <div class="location-icon">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </div>
                                                <div class="location-info">
                                                    <h6>{{ $location->name }}</h6>
                                                    <p>{{ $location->address }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <p class="text-muted mb-3">No saved locations</p>
                                                <a href="{{ route('client.locations.index') }}" class="btn btn-outline">
                                                    <i class="fas fa-plus"></i> Add Location
                                                </a>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    <input type="hidden" name="delivery_location_id" id="delivery_location_id" required>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn-gradient" onclick="nextStep(2)">
                                    Continue <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 2: Basket Selection -->
                        <div id="step2" class="form-step" style="display: none;">
                            <h6 class="mb-3">
                                <i class="fas fa-shopping-basket" style="color: var(--primary);"></i>
                                Select Basket Size
                            </h6>
                            
                            <div class="basket-grid">
                                @foreach($baskets as $basket)
                                    <div class="basket-card" 
                                         onclick="selectBasket({{ $basket->id }})"
                                         data-basket-id="{{ $basket->id }}"
                                         data-basket-price="{{ $basket->base_price }}">
                                        <div class="basket-icon">
                                            <i class="fas fa-{{ $basket->size === 'small' ? 'box' : ($basket->size === 'medium' ? 'box-open' : 'boxes') }}"></i>
                                        </div>
                                        <div class="basket-name">{{ $basket->name }}</div>
                                        <div class="basket-capacity">{{ $basket->capacity_kg }} kg capacity</div>
                                        <div class="basket-price">K{{ number_format($basket->base_price, 2) }}</div>
                                        <span class="basket-badge">{{ ucfirst($basket->size) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="basket_id" id="basket_id" required>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn-outline" onclick="prevStep(1)">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn-gradient" onclick="nextStep(3)">
                                    Continue <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 3: Special Items -->
                        <div id="step3" class="form-step" style="display: none;">
                            <h6 class="mb-3">
                                <i class="fas fa-star" style="color: var(--warning);"></i>
                                Special Items (Optional)
                            </h6>
                            
                            @foreach($specialItems->groupBy('category') as $category => $items)
                                <div class="mb-3">
                                    <h6 class="text-capitalize mb-2">{{ $category }}</h6>
                                    <div class="items-grid">
                                        @foreach($items as $item)
                                            <div class="item-card" onclick="toggleSpecialItem({{ $item->id }})">
                                                <div class="item-checkbox" id="checkbox-{{ $item->id }}">
                                                    <i class="fas fa-check" style="font-size: 12px;"></i>
                                                </div>
                                                <div class="item-info">
                                                    <h6>{{ $item->name }}</h6>
                                                    <div class="item-price">+K{{ number_format($item->surcharge, 2) }}</div>
                                                </div>
                                                <input type="checkbox" 
                                                       name="special_items[]" 
                                                       value="{{ $item->id }}"
                                                       id="item-{{ $item->id }}"
                                                       class="d-none special-item"
                                                       data-surcharge="{{ $item->surcharge }}"
                                                       onchange="calculatePrice()">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn-outline" onclick="prevStep(2)">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn-gradient" onclick="nextStep(4)">
                                    Continue <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 4: Schedule & Payment -->
                        <div id="step4" class="form-step" style="display: none;">
                            <h6 class="mb-3">
                                <i class="fas fa-credit-card" style="color: var(--success);"></i>
                                Schedule & Payment
                            </h6>
                            
                            <!-- Price Summary -->
                            <div class="price-summary" id="priceSummary" style="display: none;">
                                <div class="price-row">
                                    <span>Subtotal</span>
                                    <span>K<span id="subtotal">0.00</span></span>
                                </div>
                                <div class="price-row">
                                    <span>Surcharges</span>
                                    <span>+ K<span id="surcharges">0.00</span></span>
                                </div>
                                <div class="price-total">
                                    <span>Total</span>
                                    <span>K<span id="total">0.00</span></span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Pickup Date & Time</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="far fa-calendar"></i>
                                            </span>
                                            <input type="datetime-local" 
                                                   name="pickup_scheduled_at" 
                                                   class="form-control" 
                                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Payment Method</label>
                                        <select name="payment_method" class="form-control" required>
                                            <option value="">Select payment method</option>
                                            <option value="mtn">📱 MTN Mobile Money</option>
                                            <option value="airtel">📱 Airtel Money</option>
                                            <option value="zamtel">📱 Zamtel Money</option>
                                            <option value="wallet">💰 Wallet (K{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }})</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Payment Type</label>
                                <div class="payment-options">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_type" value="prepaid" checked>
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="font-size: 1.5rem;">💰</div>
                                            <div>
                                                <strong>Pay Before Collection</strong>
                                                <div class="small text-success">Get 5% discount!</div>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <label class="payment-option">
                                        <input type="radio" name="payment_type" value="postpaid">
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="font-size: 1.5rem;">⏰</div>
                                            <div>
                                                <strong>Pay After Collection</strong>
                                                <div class="small text-muted">Pay on delivery</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Special Instructions</label>
                                <textarea name="special_instructions" 
                                          class="form-control" 
                                          rows="3" 
                                          placeholder="Gate code, landmark, or any special instructions..."></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn-outline" onclick="prevStep(3)">
                                    <i class="fas fa-arrow-left"></i> Back
                                </button>
                                <button type="submit" class="btn-gradient">
                                    <i class="fas fa-check"></i> Place Order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Stats & Recent Orders -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-value">{{ $stats['total_orders'] }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $stats['completed_orders'] }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-value">K{{ number_format($stats['total_spent'], 2) }}</div>
                    <div class="stat-label">Total Spent</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="stat-value">K{{ number_format($stats['referral_earnings'], 2) }}</div>
                    <div class="stat-label">Referral Earnings</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="orders-card">
                <div class="orders-header">
                    <h5>
                        <i class="fas fa-history" style="color: var(--primary);"></i>
                        Recent Orders
                    </h5>
                </div>
                <div class="orders-list">
                    @if($recentOrders->isEmpty())
                        <div class="text-center py-5">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
                            <p class="text-muted mb-3">No orders yet</p>
                            <button class="btn-gradient btn-sm" onclick="showStep(1)">
                                Create First Order
                            </button>
                        </div>
                    @else
                        @foreach($recentOrders as $order)
                            <a href="{{ route('client.orders.show', $order) }}" class="order-item">
                                <div class="order-info">
                                    <h6>Order #{{ $order->order_number }}</h6>
                                    <div class="order-meta">
                                        <span>{{ $order->created_at->diffForHumans() }}</span>
                                        <span class="order-status {{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
                                    </div>
                                </div>
                                <div class="order-amount">K{{ number_format($order->total, 2) }}</div>
                            </a>
                        @endforeach
                        <div class="p-3 text-center">
                            <a href="{{ route('client.orders.index') }}" class="btn-outline btn-sm">
                                View All Orders <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast" style="display: none;">
    <div class="d-flex align-items-center gap-3">
        <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
        <span id="toast-message">Referral code copied!</span>
    </div>
</div>

@push('scripts')
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>

<script>
// Maps and Location
let pickupMap, deliveryMap;
let pickupMarker = null, deliveryMarker = null;
let selectedPickupId = null, selectedDeliveryId = null;
let selectedBasket = null;
let basketPrice = 0;
let currentStep = 1;
let selectedItems = new Set();

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initializeMaps();
    initializeSearch();
});

function initializeMaps() {
    const defaultCenter = [-15.3875259, 28.3228165];
    
    // Pickup Map
    pickupMap = L.map('pickup-map').setView(defaultCenter, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(pickupMap);
    
    // Delivery Map
    deliveryMap = L.map('delivery-map').setView(defaultCenter, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(deliveryMap);
    
    // Click handlers
    pickupMap.on('click', (e) => reverseGeocode(e.latlng, 'pickup'));
    deliveryMap.on('click', (e) => reverseGeocode(e.latlng, 'delivery'));
}

function initializeSearch() {
    const pickupSearch = document.getElementById('pickup-search');
    const deliverySearch = document.getElementById('delivery-search');
    const pickupSuggestions = document.getElementById('pickup-suggestions');
    const deliverySuggestions = document.getElementById('delivery-suggestions');
    
    let pickupTimeout, deliveryTimeout;
    
    pickupSearch.addEventListener('input', function() {
        clearTimeout(pickupTimeout);
        if (this.value.length < 3) return;
        pickupTimeout = setTimeout(() => searchLocations(this.value, 'pickup'), 300);
    });
    
    deliverySearch.addEventListener('input', function() {
        clearTimeout(deliveryTimeout);
        if (this.value.length < 3) return;
        deliveryTimeout = setTimeout(() => searchLocations(this.value, 'delivery'), 300);
    });
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!pickupSearch.contains(e.target) && !pickupSuggestions.contains(e.target)) {
            pickupSuggestions.style.display = 'none';
        }
        if (!deliverySearch.contains(e.target) && !deliverySuggestions.contains(e.target)) {
            deliverySuggestions.style.display = 'none';
        }
    });
}

function searchLocations(query, type) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`;
    const suggestionsDiv = document.getElementById(`${type}-suggestions`);
    
    fetch(url, { 
        headers: { 
            'Accept-Language': 'en', 
            'User-Agent': 'LaundryApp/1.0' 
        } 
    })
    .then(response => response.json())
    .then(data => {
        suggestionsDiv.innerHTML = '';
        
        if (data.length === 0) {
            suggestionsDiv.innerHTML = '<div class="suggestion-item text-muted">No locations found</div>';
        } else {
            data.forEach(place => {
                const div = document.createElement('div');
                div.className = 'suggestion-item';
                div.innerHTML = `
                    <div class="suggestion-main">${place.display_name.split(',')[0]}</div>
                    <div class="suggestion-secondary">${place.display_name}</div>
                `;
                div.onclick = function() {
                    selectLocation(type, place.lat, place.lon, place.display_name);
                };
                suggestionsDiv.appendChild(div);
            });
        }
        suggestionsDiv.style.display = 'block';
    })
    .catch(error => {
        console.error('Search error:', error);
    });
}

function selectLocation(type, lat, lng, address) {
    const latlng = [parseFloat(lat), parseFloat(lng)];
    document.getElementById(`${type}-search`).value = address;
    document.getElementById(`${type}-suggestions`).style.display = 'none';
    
    const map = type === 'pickup' ? pickupMap : deliveryMap;
    
    map.setView(latlng, 15);
    
    const marker = type === 'pickup' ? pickupMarker : deliveryMarker;
    if (marker) marker.remove();
    
    const newMarker = L.marker(latlng, {
        icon: L.divIcon({
            className: `custom-marker ${type}`,
            html: `<i class="fas fa-${type === 'pickup' ? 'map-marker-alt' : 'flag-checkered'}"></i>`,
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        })
    }).addTo(map).bindPopup(address).openPopup();
    
    if (type === 'pickup') pickupMarker = newMarker;
    else deliveryMarker = newMarker;
}

function reverseGeocode(latlng, type) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`;
    
    fetch(url, { 
        headers: { 
            'Accept-Language': 'en', 
            'User-Agent': 'LaundryApp/1.0' 
        } 
    })
    .then(response => response.json())
    .then(data => {
        selectLocation(type, latlng.lat, latlng.lng, data.display_name);
    })
    .catch(error => {
        console.error('Reverse geocoding error:', error);
    });
}

// Location Selection
function selectPickupLocation(id) {
    selectedPickupId = id;
    document.getElementById('pickup_location_id').value = id;
    
    document.querySelectorAll('#pickup-locations .location-item').forEach(card => {
        card.classList.remove('selected');
        if (card.dataset.pickupId == id) {
            card.classList.add('selected');
            selectLocation('pickup', card.dataset.pickupLat, card.dataset.pickupLng, card.dataset.pickupName);
        }
    });
}

function selectDeliveryLocation(id) {
    selectedDeliveryId = id;
    document.getElementById('delivery_location_id').value = id;
    
    document.querySelectorAll('#delivery-locations .location-item').forEach(card => {
        card.classList.remove('selected');
        if (card.dataset.deliveryId == id) {
            card.classList.add('selected');
            selectLocation('delivery', card.dataset.deliveryLat, card.dataset.deliveryLng, card.dataset.deliveryName);
        }
    });
}

// Basket Selection
function selectBasket(basketId) {
    selectedBasket = basketId;
    document.getElementById('basket_id').value = basketId;
    
    document.querySelectorAll('.basket-card').forEach(card => {
        card.classList.remove('selected');
        if (card.dataset.basketId == basketId) {
            card.classList.add('selected');
            basketPrice = parseFloat(card.dataset.basketPrice);
        }
    });
}

// Special Items
function toggleSpecialItem(itemId) {
    const checkbox = document.getElementById(`item-${itemId}`);
    const card = checkbox.closest('.item-card');
    
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        card.classList.add('selected');
        selectedItems.add(itemId);
    } else {
        card.classList.remove('selected');
        selectedItems.delete(itemId);
    }
    
    calculatePrice();
}

// Price Calculation
function calculatePrice() {
    if (!selectedBasket) return;
    
    let subtotal = basketPrice;
    let surcharges = 0;
    
    document.querySelectorAll('.special-item:checked').forEach(item => {
        surcharges += parseFloat(item.dataset.surcharge);
    });
    
    let total = subtotal + surcharges;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('surcharges').textContent = surcharges.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
    document.getElementById('priceSummary').style.display = 'block';
}

// Step Navigation
function showStep(step) {
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`step${i}`).style.display = 'none';
        document.getElementById(`step${i}-indicator`).classList.remove('active', 'completed');
    }
    
    document.getElementById(`step${step}`).style.display = 'block';
    
    for (let i = 1; i < step; i++) {
        document.getElementById(`step${i}-indicator`).classList.add('completed');
    }
    document.getElementById(`step${step}-indicator`).classList.add('active');
    
    currentStep = step;
    
    // Refresh maps
    if (step === 1) {
        setTimeout(() => {
            if (pickupMap) pickupMap.invalidateSize();
            if (deliveryMap) deliveryMap.invalidateSize();
        }, 100);
    }
}

function nextStep(step) {
    if (currentStep === 1 && (!selectedPickupId || !selectedDeliveryId)) {
        showToast('Please select both pickup and delivery locations');
        return;
    }
    if (step === 3 && !selectedBasket) {
        showToast('Please select a basket size');
        return;
    }
    
    showStep(step);
    if (step === 4) calculatePrice();
}

function prevStep(step) {
    showStep(step);
}

// Copy Referral Code
function copyReferralCode() {
    const code = '{{ auth()->user()->referral_code }}';
    navigator.clipboard.writeText(code).then(() => {
        showToast('Referral code copied to clipboard!');
    }).catch(() => {
        showToast('Failed to copy code');
    });
}

// Toast Notification
function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toast-message').textContent = message;
    toast.style.display = 'block';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

// Form Validation
document.getElementById('orderForm').addEventListener('submit', function(e) {
    if (!selectedPickupId || !selectedDeliveryId) {
        e.preventDefault();
        showToast('Please select pickup and delivery locations');
        showStep(1);
        return;
    }
    
    if (!selectedBasket) {
        e.preventDefault();
        showToast('Please select a basket size');
        showStep(2);
        return;
    }
});
</script>
@endpush
@endsection