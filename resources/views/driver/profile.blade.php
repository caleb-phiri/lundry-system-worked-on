@extends('layouts.driver')

@section('title', 'My Profile')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<style>
/* Profile Page Specific Styles */
.profile-header {
    background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-600) 100%);
    color: white;
    padding: var(--space-6);
    border-radius: var(--radius-xl);
    margin-bottom: var(--space-6);
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transform: rotate(30deg);
}

.profile-avatar-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto var(--space-4);
}

.profile-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.avatar-edit-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--color-primary-500);
    color: white;
    border: 3px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-md);
}

.avatar-edit-btn:hover {
    background: var(--color-primary-600);
    transform: scale(1.1);
}

.profile-name {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    margin-bottom: var(--space-1);
}

.profile-role {
    font-size: var(--font-size-base);
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
}

.profile-meta {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: var(--space-4);
    margin-top: var(--space-4);
    padding-top: var(--space-4);
    border-top: 1px solid rgba(255,255,255,0.2);
}

.meta-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    font-size: var(--font-size-sm);
}

/* Section Cards */
.section-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    overflow: hidden;
    margin-bottom: var(--space-5);
}

.section-header {
    padding: var(--space-4) var(--space-5);
    background: linear-gradient(135deg, var(--color-gray-50) 0%, var(--color-white) 100%);
    border-bottom: 1px solid var(--card-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: var(--space-3);
}

.section-title {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.section-title i {
    color: var(--color-primary-500);
}

.section-actions {
    display: flex;
    gap: var(--space-2);
}

.section-body {
    padding: var(--space-5);
}

/* Form Styles */
.form-group {
    margin-bottom: var(--space-5);
}

.form-label {
    display: block;
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-700);
    margin-bottom: var(--space-2);
    font-size: var(--font-size-sm);
}

.form-label .required {
    color: var(--color-danger);
}

.form-control {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    font-size: var(--font-size-base);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-lg);
    background: var(--color-white);
    color: var(--color-gray-900);
    transition: all var(--transition-fast);
}

.form-control:focus {
    outline: none;
    border-color: var(--color-primary-500);
    box-shadow: 0 0 0 3px var(--color-primary-100);
}

.form-control:disabled {
    background: var(--color-gray-100);
    color: var(--color-gray-500);
    cursor: not-allowed;
}

.form-control.is-invalid {
    border-color: var(--color-danger);
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px var(--color-danger-light);
}

.form-text {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    margin-top: var(--space-1);
}

.invalid-feedback {
    display: none;
    font-size: var(--font-size-xs);
    color: var(--color-danger);
    margin-top: var(--space-1);
}

.form-control.is-invalid ~ .invalid-feedback {
    display: block;
}

/* Inline Edit */
.inline-edit-wrapper {
    position: relative;
}

.inline-edit-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-3) var(--space-4);
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-200);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.inline-edit-display:hover {
    background: var(--color-white);
    border-color: var(--color-primary-300);
    box-shadow: var(--shadow-sm);
}

.inline-edit-display.editing {
    background: var(--color-white);
    border-color: var(--color-primary-500);
    box-shadow: 0 0 0 3px var(--color-primary-100);
}

.inline-edit-value {
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-900);
}

.inline-edit-hint {
    font-size: var(--font-size-xs);
    color: var(--color-gray-400);
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.inline-edit-form {
    display: none;
    animation: fadeIn 0.2s ease-out;
}

.inline-edit-form.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Performance Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--space-4);
}

.metric-card {
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    text-align: center;
    transition: all var(--transition-base);
    border: 1px solid var(--color-gray-100);
}

.metric-card:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.metric-value {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-gray-900);
    line-height: 1;
    margin-bottom: var(--space-1);
}

.metric-card.primary .metric-value { color: var(--color-primary-600); }
.metric-card.success .metric-value { color: var(--color-success); }
.metric-card.warning .metric-value { color: var(--color-warning-dark); }
.metric-card.info .metric-value { color: var(--color-info); }
.metric-card.danger .metric-value { color: var(--color-danger); }

.metric-label {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: var(--font-weight-semibold);
}

.metric-trend {
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
    margin-top: var(--space-2);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-1);
}

.metric-trend.positive { color: var(--color-success); }
.metric-trend.negative { color: var(--color-danger); }

/* Rating Stars */
.rating-display {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.stars {
    display: flex;
    gap: var(--space-1);
}

.star {
    font-size: var(--font-size-xl);
    color: var(--color-gray-300);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.star.filled { color: var(--color-warning); }
.star.half { position: relative; }
.star.half::before {
    content: '\f005';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: var(--color-warning);
    position: absolute;
    left: 0;
    width: 50%;
    overflow: hidden;
}

.rating-value {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: var(--color-gray-900);
}

.rating-count {
    font-size: var(--font-size-sm);
    color: var(--color-gray-500);
}

/* Vehicle Card */
.vehicle-card {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    padding: var(--space-4);
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-100);
    transition: all var(--transition-base);
}

.vehicle-card:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-sm);
}

.vehicle-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-lg);
    background: linear-gradient(135deg, var(--color-primary-100), var(--color-primary-50));
    color: var(--color-primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-2xl);
    flex-shrink: 0;
}

.vehicle-info {
    flex: 1;
}

.vehicle-name {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    margin-bottom: var(--space-1);
}

.vehicle-details {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-3);
    font-size: var(--font-size-sm);
    color: var(--color-gray-500);
}

.vehicle-detail {
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

/* Badge */
.verification-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
}

.verification-badge.verified {
    background: var(--color-success-light);
    color: var(--color-success-dark);
}

.verification-badge.pending {
    background: var(--color-warning-light);
    color: var(--color-warning-dark);
}

.verification-badge.unverified {
    background: var(--color-gray-100);
    color: var(--color-gray-600);
}

/* Availability Toggle */
.availability-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-4);
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-100);
}

.availability-info {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.availability-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-xl);
}

.availability-icon.online {
    background: var(--color-success-light);
    color: var(--color-success);
}

.availability-icon.offline {
    background: var(--color-gray-100);
    color: var(--color-gray-400);
}

.availability-text {
    flex: 1;
}

.availability-status {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
}

.availability-subtitle {
    font-size: var(--font-size-sm);
    color: var(--color-gray-500);
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    width: 56px;
    height: 28px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--color-gray-300);
    transition: var(--transition-base);
    border-radius: 28px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: var(--transition-base);
    border-radius: 50%;
    box-shadow: var(--shadow-sm);
}

.toggle-switch input:checked + .toggle-slider {
    background-color: var(--color-success);
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(28px);
}

.toggle-switch input:focus-visible + .toggle-slider {
    box-shadow: 0 0 0 3px var(--color-success-light);
}

/* Document Upload */
.document-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-3) var(--space-4);
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-gray-100);
    margin-bottom: var(--space-3);
    transition: all var(--transition-fast);
}

.document-item:hover {
    background: var(--color-white);
    box-shadow: var(--shadow-sm);
}

.document-info {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.document-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-base);
    background: var(--color-primary-100);
    color: var(--color-primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
}

.document-name {
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-900);
}

.document-status {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
}

.document-actions {
    display: flex;
    gap: var(--space-2);
}

/* Notification Preferences */
.notification-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--color-gray-100);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-info {
    flex: 1;
}

.notification-label {
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-900);
    margin-bottom: var(--space-1);
}

.notification-description {
    font-size: var(--font-size-sm);
    color: var(--color-gray-500);
}

/* Danger Zone */
.danger-zone {
    border: 1px solid var(--color-danger-light) !important;
    background: var(--color-danger-light) !important;
}

.danger-zone .section-header {
    background: linear-gradient(135deg, var(--color-danger-light) 0%, var(--color-danger-50) 100%) !important;
    border-color: var(--color-danger-light) !important;
}

.danger-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-4);
    background: var(--color-white);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-3);
}

.danger-item:last-child {
    margin-bottom: 0;
}

.danger-info {
    flex: 1;
}

.danger-title {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
    margin-bottom: var(--space-1);
}

.danger-description {
    font-size: var(--font-size-sm);
    color: var(--color-gray-500);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .profile-header {
        padding: var(--space-4);
    }
    .profile-avatar-wrapper {
        width: 100px;
        height: 100px;
    }
    .profile-name {
        font-size: var(--font-size-xl);
    }
    .profile-meta {
        flex-direction: column;
        gap: var(--space-2);
    }
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .section-body {
        padding: var(--space-4);
    }
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .vehicle-card {
        flex-direction: column;
        text-align: center;
    }
    .vehicle-details {
        justify-content: center;
    }
    .availability-toggle {
        flex-direction: column;
        gap: var(--space-3);
        text-align: center;
    }
    .notification-item {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-3);
    }
    .danger-item {
        flex-direction: column;
        gap: var(--space-3);
    }
}

@media (max-width: 480px) {
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    .form-control {
        padding: var(--space-3);
    }
    .btn {
        padding: var(--space-2) var(--space-4);
        font-size: var(--font-size-sm);
    }
}

/* Print Styles */
@media print {
    .avatar-edit-btn, .section-actions, .inline-edit-form, .toggle-switch, .document-actions, .danger-zone, .sidebar, .navbar-top {
        display: none !important;
    }
    .profile-header {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .section-card {
        box-shadow: none;
        border: 1px solid #ddd;
        break-inside: avoid;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Profile Header -->
    <div class="profile-header text-center" data-aos="fade-down">
        <div class="profile-avatar-wrapper">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="profile-avatar" id="profileAvatar">
            @else
                <div class="profile-avatar d-flex align-items-center justify-content-center bg-primary-100 text-primary-600" style="font-size: 3rem;" id="profileAvatar">
                    {{ strtoupper(auth()->user()->name[0]) }}
                </div>
            @endif
            <label class="avatar-edit-btn" for="avatarUpload" title="Change photo">
                <i class="fas fa-camera"></i>
            </label>
            <input type="file" id="avatarUpload" accept="image/*" class="d-none" onchange="previewAvatar(this)">
        </div>
        
        <h1 class="profile-name">{{ auth()->user()->name }}</h1>
        <div class="profile-role">
            <i class="fas fa-truck"></i>
            <span>Laundry Driver</span>
            <span class="verification-badge {{ auth()->user()->is_verified ? 'verified' : 'pending' }}">
                <i class="fas fa-{{ auth()->user()->is_verified ? 'check-circle' : 'clock' }}"></i>
                {{ auth()->user()->is_verified ? 'Verified' : 'Pending Verification' }}
            </span>
        </div>
        
        <div class="profile-meta">
            <div class="meta-item">
                <i class="fas fa-id-badge"></i>
                <span>ID: {{ auth()->user()->driver_id ?? 'DRV-' . str_pad(auth()->id(), 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>Joined {{ auth()->user()->created_at->format('M Y') }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-star"></i>
                <span>{{ $avgRating ?? 0 }} Rating</span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <x-driver.card class="section-card" data-aos="fade-up">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </div>
                    <div class="section-actions">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="enableEdit('personal')">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                    </div>
                </x-slot>
                <div class="section-body">
                    <form id="personalForm" action="{{ route('driver.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group inline-edit-wrapper" data-field="name">
                                    <label class="form-label">Full Name <span class="required">*</span></label>
                                    <div class="inline-edit-display" id="display_name">
                                        <span class="inline-edit-value">{{ auth()->user()->name }}</span>
                                        <span class="inline-edit-hint"><i class="fas fa-pencil-alt"></i>Click to edit</span>
                                    </div>
                                    <div class="inline-edit-form" id="form_name">
                                        <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
                                        <div class="invalid-feedback">Name is required</div>
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="saveInline('name')"><i class="fas fa-check me-1"></i>Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelInline('name')"><i class="fas fa-times me-1"></i>Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group inline-edit-wrapper" data-field="email">
                                    <label class="form-label">Email Address <span class="required">*</span></label>
                                    <div class="inline-edit-display" id="display_email">
                                        <span class="inline-edit-value">{{ auth()->user()->email }}</span>
                                        <span class="inline-edit-hint"><i class="fas fa-pencil-alt"></i>Click to edit</span>
                                    </div>
                                    <div class="inline-edit-form" id="form_email">
                                        <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required>
                                        <div class="invalid-feedback">Valid email is required</div>
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="saveInline('email')"><i class="fas fa-check me-1"></i>Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelInline('email')"><i class="fas fa-times me-1"></i>Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group inline-edit-wrapper" data-field="phone">
                                    <label class="form-label">Phone Number <span class="required">*</span></label>
                                    <div class="inline-edit-display" id="display_phone">
                                        <span class="inline-edit-value">{{ auth()->user()->phone }}</span>
                                        <span class="inline-edit-hint"><i class="fas fa-pencil-alt"></i>Click to edit</span>
                                    </div>
                                    <div class="inline-edit-form" id="form_phone">
                                        <input type="tel" class="form-control" name="phone" value="{{ auth()->user()->phone }}" required>
                                        <div class="invalid-feedback">Phone number is required</div>
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="saveInline('phone')"><i class="fas fa-check me-1"></i>Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelInline('phone')"><i class="fas fa-times me-1"></i>Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group inline-edit-wrapper" data-field="emergency_contact">
                                    <label class="form-label">Emergency Contact</label>
                                    <div class="inline-edit-display" id="display_emergency_contact">
                                        <span class="inline-edit-value">{{ auth()->user()->emergency_contact ?? 'Not set' }}</span>
                                        <span class="inline-edit-hint"><i class="fas fa-pencil-alt"></i>Click to edit</span>
                                    </div>
                                    <div class="inline-edit-form" id="form_emergency_contact">
                                        <input type="tel" class="form-control" name="emergency_contact" value="{{ auth()->user()->emergency_contact ?? '' }}" placeholder="Emergency contact number">
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="saveInline('emergency_contact')"><i class="fas fa-check me-1"></i>Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="cancelInline('emergency_contact')"><i class="fas fa-times me-1"></i>Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group inline-edit-wrapper" data-field="address">
                            <label class="form-label">Home Address</label>
                            <div class="inline-edit-display" id="display_address">
                                <span class="inline-edit-value">{{ auth()->user()->address ?? 'Not set' }}</span>
                                <span class="inline-edit-hint"><i class="fas fa-pencil-alt"></i>Click to edit</span>
                            </div>
                            <div class="inline-edit-form" id="form_address">
                                <textarea class="form-control" name="address" rows="2" placeholder="Your home address">{{ auth()->user()->address ?? '' }}</textarea>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="saveInline('address')"><i class="fas fa-check me-1"></i>Save</button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="cancelInline('address')"><i class="fas fa-times me-1"></i>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </x-driver.card>

            <!-- Vehicle Information -->
            <x-driver.card class="section-card" data-aos="fade-up" data-aos-delay="100">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-motorcycle"></i>
                        Vehicle Information
                    </div>
                    <div class="section-actions">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="enableEdit('vehicle')">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                    </div>
                </x-slot>
                <div class="section-body">
                    @if(auth()->user()->vehicle)
                        <div class="vehicle-card" id="vehicleDisplay">
                            <div class="vehicle-icon">
                                <i class="fas fa-{{ auth()->user()->vehicle->type === 'bicycle' ? 'bicycle' : (auth()->user()->vehicle->type === 'motorbike' ? 'motorcycle' : 'truck') }}"></i>
                            </div>
                            <div class="vehicle-info">
                                <div class="vehicle-name">{{ ucfirst(auth()->user()->vehicle->type) }} - {{ auth()->user()->vehicle->registration_number }}</div>
                                <div class="vehicle-details">
                                    <span class="vehicle-detail"><i class="fas fa-tag"></i>{{ auth()->user()->vehicle->make }} {{ auth()->user()->vehicle->model }}</span>
                                    <span class="vehicle-detail"><i class="fas fa-palette"></i>{{ auth()->user()->vehicle->color }}</span>
                                    <span class="vehicle-detail"><i class="fas fa-calendar"></i>{{ auth()->user()->vehicle->year }}</span>
                                </div>
                            </div>
                            <span class="verification-badge {{ auth()->user()->vehicle->is_verified ? 'verified' : 'pending' }}">
                                <i class="fas fa-{{ auth()->user()->vehicle->is_verified ? 'check-circle' : 'clock' }}"></i>
                                {{ auth()->user()->vehicle->is_verified ? 'Verified' : 'Pending' }}
                            </span>
                        </div>
                        
                        <form id="vehicleForm" class="d-none" action="{{ route('driver.vehicle.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Vehicle Type <span class="required">*</span></label>
                                        <select class="form-control" name="type" required>
                                            @foreach(['motorbike' => 'Motorbike', 'bicycle' => 'Bicycle', 'van' => 'Van', 'truck' => 'Truck'] as $value => $label)
                                                <option value="{{ $value }}" {{ auth()->user()->vehicle->type === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Registration Number <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="registration_number" value="{{ auth()->user()->vehicle->registration_number }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Make</label>
                                        <input type="text" class="form-control" name="make" value="{{ auth()->user()->vehicle->make }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Model</label>
                                        <input type="text" class="form-control" name="model" value="{{ auth()->user()->vehicle->model }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Year</label>
                                        <input type="number" class="form-control" name="year" value="{{ auth()->user()->vehicle->year }}" min="1990" max="{{ date('Y') + 1 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Color</label>
                                <input type="text" class="form-control" name="color" value="{{ auth()->user()->vehicle->color }}">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Vehicle</button>
                                <button type="button" class="btn btn-secondary" onclick="cancelVehicleEdit()"><i class="fas fa-times me-1"></i>Cancel</button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-motorcycle fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Vehicle Registered</h5>
                            <p class="text-muted mb-4">Add your vehicle details to start accepting orders</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                <i class="fas fa-plus me-2"></i>Add Vehicle
                            </button>
                        </div>
                    @endif
                </div>
            </x-driver.card>

            <!-- Documents -->
            <x-driver.card class="section-card" data-aos="fade-up" data-aos-delay="200">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Documents & Verification
                    </div>
                </x-slot>
                <div class="section-body">
                    @foreach($documents ?? [] as $doc)
                        <div class="document-item">
                            <div class="document-info">
                                <div class="document-icon">
                                    <i class="fas fa-{{ $doc['icon'] ?? 'file-alt' }}"></i>
                                </div>
                                <div>
                                    <div class="document-name">{{ $doc['name'] }}</div>
                                    <div class="document-status">
                                        @if($doc['status'] === 'verified')
                                            <span class="verification-badge verified"><i class="fas fa-check-circle"></i>Verified</span>
                                        @elseif($doc['status'] === 'pending')
                                            <span class="verification-badge pending"><i class="fas fa-clock"></i>Pending Review</span>
                                        @elseif($doc['status'] === 'rejected')
                                            <span class="verification-badge" style="background: var(--color-danger-light); color: var(--color-danger-dark);"><i class="fas fa-times-circle"></i>Rejected</span>
                                        @else
                                            <span class="verification-badge unverified"><i class="fas fa-upload"></i>Not Uploaded</span>
                                        @endif
                                        @if($doc['expires_at'])
                                            • Expires {{ \Carbon\Carbon::parse($doc['expires_at'])->format('M j, Y') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="document-actions">
                                @if($doc['status'] !== 'verified')
                                    <button class="btn btn-primary btn-sm" onclick="uploadDocument('{{ $doc['key'] }}')">
                                        <i class="fas fa-upload me-1"></i>Upload
                                    </button>
                                @endif
                                @if($doc['file_path'])
                                    <a href="{{ asset('storage/' . $doc['file_path']) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-driver.card>

            <!-- Performance Metrics -->
            <x-driver.card class="section-card" data-aos="fade-up" data-aos-delay="300">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Performance Metrics
                    </div>
                    <div class="section-actions">
                        <div class="btn-group btn-group-sm" role="group">
                            @foreach(['week' => 'This Week', 'month' => 'This Month', 'quarter' => 'This Quarter', 'year' => 'This Year'] as $period => $label)
                                <button type="button" class="btn btn-outline-secondary {{ $performancePeriod === $period ? 'active' : '' }}" onclick="changePerformancePeriod('{{ $period }}')">{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>
                </x-slot>
                <div class="section-body">
                    <div class="metrics-grid">
                        <div class="metric-card primary">
                            <div class="metric-value">{{ $metrics['completed_orders'] ?? 0 }}</div>
                            <div class="metric-label">Completed Orders</div>
                            <div class="metric-trend {{ ($metrics['orders_trend'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($metrics['orders_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                <span>{{ abs($metrics['orders_trend'] ?? 0) }}%</span>
                            </div>
                        </div>
                        <div class="metric-card success">
                            <div class="metric-value">K{{ number_format($metrics['total_earnings'] ?? 0, 0) }}</div>
                            <div class="metric-label">Total Earnings</div>
                            <div class="metric-trend {{ ($metrics['earnings_trend'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($metrics['earnings_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                <span>{{ abs($metrics['earnings_trend'] ?? 0) }}%</span>
                            </div>
                        </div>
                        <div class="metric-card warning">
                            <div class="metric-value">{{ $metrics['avg_rating'] ?? 0 }}</div>
                            <div class="metric-label">Average Rating</div>
                            <div class="metric-trend {{ ($metrics['rating_trend'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($metrics['rating_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                <span>{{ abs($metrics['rating_trend'] ?? 0) }}%</span>
                            </div>
                        </div>
                        <div class="metric-card info">
                            <div class="metric-value">{{ $metrics['on_time_rate'] ?? 0 }}%</div>
                            <div class="metric-label">On-Time Rate</div>
                            <div class="metric-trend {{ ($metrics['ontime_trend'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($metrics['ontime_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                <span>{{ abs($metrics['ontime_trend'] ?? 0) }}%</span>
                            </div>
                        </div>
                        <div class="metric-card danger">
                            <div class="metric-value">{{ $metrics['cancellation_rate'] ?? 0 }}%</div>
                            <div class="metric-label">Cancellation Rate</div>
                            <div class="metric-trend {{ ($metrics['cancel_trend'] ?? 0) <= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($metrics['cancel_trend'] ?? 0) <= 0 ? 'down' : 'up' }}"></i>
                                <span>{{ abs($metrics['cancel_trend'] ?? 0) }}%</span>
                            </div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value">{{ $metrics['active_hours'] ?? 0 }}h</div>
                            <div class="metric-label">Active Hours</div>
                            <div class="metric-trend {{ ($metrics['hours_trend'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($metrics['hours_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                <span>{{ abs($metrics['hours_trend'] ?? 0) }}%</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rating Breakdown -->
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="fw-semibold mb-3">Rating Breakdown</h6>
                        <div class="rating-display">
                            <div class="stars">
                                @for($i = 5; $i >= 1; $i--)
                                    <i class="fas fa-star star {{ ($avgRating ?? 0) >= $i ? 'filled' : '' }} {{ ($avgRating ?? 0) >= $i - 0.5 && ($avgRating ?? 0) < $i ? 'half' : '' }}"></i>
                                @endfor
                            </div>
                            <div class="rating-value">{{ number_format($avgRating ?? 0, 1) }}</div>
                            <div class="rating-count">({{ $totalReviews ?? 0 }} reviews)</div>
                        </div>
                        <div class="row mt-3">
                            @for($i = 5; $i >= 1; $i--)
                                @php $count = $ratingBreakdown[$i] ?? 0; $percent = $totalReviews ? ($count / $totalReviews * 100) : 0; @endphp
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-warning fw-bold" style="width: 24px;">{{ $i }}</span>
                                        <div class="flex-grow-1" style="height: 6px; background: var(--color-gray-200); border-radius: 3px; overflow: hidden;">
                                            <div style="width: {{ $percent }}%; height: 100%; background: var(--color-warning); border-radius: 3px; transition: width 0.5s;"></div>
                                        </div>
                                        <span class="text-muted small">{{ $count }}</span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </x-driver.card>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Availability Status -->
            <x-driver.card class="section-card mb-5" data-aos="fade-up" data-aos-delay="400">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-toggle-on"></i>
                        Availability
                    </div>
                </x-slot>
                <div class="section-body p-0">
                    <div class="availability-toggle" style="border-radius: 0; border: none;">
                        <div class="availability-info">
                            <div class="availability-icon {{ auth()->user()->is_available ? 'online' : 'offline' }}" id="availabilityIcon">
                                <i class="fas fa-{{ auth()->user()->is_available ? 'circle' : 'circle' }}"></i>
                            </div>
                            <div class="availability-text">
                                <div class="availability-status" id="availabilityStatus">{{ auth()->user()->is_available ? 'Available for Orders' : 'Offline' }}</div>
                                <div class="availability-subtitle" id="availabilitySubtitle">{{ auth()->user()->is_available ? 'You\'ll receive new order notifications' : 'Toggle to go online' }}</div>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="availabilityToggle" {{ auth()->user()->is_available ? 'checked' : '' }} onchange="toggleAvailability(this)">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </x-driver.card>

            <!-- Notification Preferences -->
            <x-driver.card class="section-card mb-5" data-aos="fade-up" data-aos-delay="500">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-bell"></i>
                        Notifications
                    </div>
                </x-slot>
                <div class="section-body p-0">
                    @foreach($notificationTypes ?? ['new_orders' => ['New Orders', 'Receive notifications for new order requests'], 'order_updates' => ['Order Updates', 'Status changes for your active orders'], 'earnings' => ['Earnings', 'Daily/weekly earnings summaries'], 'promotions' => ['Promotions', 'Special offers and bonuses'], 'system' => ['System Updates', 'App updates and maintenance notices']] as $key => $notification)
                        <div class="notification-item px-4">
                            <div class="notification-info">
                                <div class="notification-label">{{ $notification[0] }}</div>
                                <div class="notification-description">{{ $notification[1] }}</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" {{ ($userNotifications[$key] ?? true) ? 'checked' : '' }} onchange="toggleNotification('{{ $key }}', this)">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </x-driver.card>

            <!-- Preferences -->
            <x-driver.card class="section-card mb-5" data-aos="fade-up" data-aos-delay="600">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-sliders-h"></i>
                        Preferences
                    </div>
                </x-slot>
                <div class="section-body">
                    <form id="preferencesForm">
                        <div class="form-group">
                            <label class="form-label">Preferred Language</label>
                            <select class="form-control" name="language" onchange="savePreference('language', this.value)">
                                <option value="en" {{ (auth()->user()->preferences['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ny" {{ (auth()->user()->preferences['language'] ?? 'en') === 'ny' ? 'selected' : '' }}>Chichewa</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Distance Unit</label>
                            <select class="form-control" name="distance_unit" onchange="savePreference('distance_unit', this.value)">
                                <option value="km" {{ (auth()->user()->preferences['distance_unit'] ?? 'km') === 'km' ? 'selected' : '' }}>Kilometers</option>
                                <option value="mi" {{ (auth()->user()->preferences['distance_unit'] ?? 'km') === 'mi' ? 'selected' : '' }}>Miles</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Auto-Accept Orders</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="autoAccept" {{ (auth()->user()->preferences['auto_accept'] ?? false) ? 'checked' : '' }} onchange="savePreference('auto_accept', this.checked)">
                                <label class="form-check-label" for="autoAccept">Automatically accept nearby orders</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Sound Notifications</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="soundNotifications" {{ (auth()->user()->preferences['sound'] ?? true) ? 'checked' : '' }} onchange="savePreference('sound', this.checked)">
                                <label class="form-check-label" for="soundNotifications">Play sound for new orders</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Vibration</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="vibration" {{ (auth()->user()->preferences['vibration'] ?? true) ? 'checked' : '' }} onchange="savePreference('vibration', this.checked)">
                                <label class="form-check-label" for="vibration">Vibrate on new orders</label>
                            </div>
                        </div>
                    </form>
                </div>
            </x-driver.card>

            <!-- Danger Zone -->
            <x-driver.card class="section-card danger-zone" data-aos="fade-up" data-aos-delay="700">
                <x-slot name="header">
                    <div class="section-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Danger Zone
                    </div>
                </x-slot>
                <div class="section-body p-0">
                    <div class="danger-item">
                        <div class="danger-info">
                            <div class="danger-title">Change Password</div>
                            <div class="danger-description">Update your account password</div>
                        </div>
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-1"></i>Change
                        </button>
                    </div>
                    <div class="danger-item">
                        <div class="danger-info">
                            <div class="danger-title">Delete Account</div>
                            <div class="danger-description">Permanently delete your driver account and all data</div>
                        </div>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </x-driver.card>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('driver.vehicle.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vehicle Type <span class="required">*</span></label>
                            <select class="form-control" name="type" required>
                                <option value="">Select type</option>
                                <option value="motorbike">Motorbike</option>
                                <option value="bicycle">Bicycle</option>
                                <option value="van">Van</option>
                                <option value="truck">Truck</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registration Number <span class="required">*</span></label>
                            <input type="text" class="form-control" name="registration_number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Make</label>
                            <input type="text" class="form-control" name="make">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" class="form-control" name="model">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="year" min="1990" max="{{ date('Y') + 1 }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="text" class="form-control" name="color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Add Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="changePasswordForm" action="{{ route('driver.password.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-key me-2"></i>Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Password <span class="required">*</span></label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="required">*</span></label>
                        <input type="password" class="form-control" name="password" required minlength="8">
                        <div class="form-text">Minimum 8 characters</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password <span class="required">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>This action is irreversible!</strong> All your data including earnings history, ratings, and documents will be permanently deleted.
                </div>
                <p>Please type "DELETE" to confirm:</p>
                <input type="text" class="form-control mb-3" id="deleteConfirmation" placeholder="Type DELETE to confirm">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="deleteUnderstand">
                    <label class="form-check-label" for="deleteUnderstand">I understand this cannot be undone</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled onclick="deleteAccount()">
                    <i class="fas fa-trash me-1"></i>Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Avatar preview
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatar = document.getElementById('profileAvatar');
            if (avatar.tagName === 'IMG') {
                avatar.src = e.target.result;
            } else {
                avatar.innerHTML = '';
                avatar.style.backgroundImage = `url(${e.target.result})`;
                avatar.style.backgroundSize = 'cover';
                avatar.style.backgroundPosition = 'center';
            }
        };
        reader.readAsDataURL(input.files[0]);
        
        // Auto-upload
        const formData = new FormData();
        formData.append('avatar', input.files[0]);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route('driver.profile.avatar') }}', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.toastManager.success('Profile photo updated!');
            } else {
                window.toastManager.error(data.message || 'Failed to update photo');
            }
        })
        .catch(() => window.toastManager.error('Failed to update photo'));
    }
}

// Inline editing
function enableEdit(field) {
    const display = document.getElementById('display_' + field);
    const form = document.getElementById('form_' + field);
    if (display && form) {
        display.classList.add('editing');
        display.style.display = 'none';
        form.classList.add('active');
        const input = form.querySelector('input, textarea, select');
        if (input) input.focus();
    }
}

function cancelInline(field) {
    const display = document.getElementById('display_' + field);
    const form = document.getElementById('form_' + field);
    if (display && form) {
        display.style.display = 'flex';
        display.classList.remove('editing');
        form.classList.remove('active');
    }
}

function saveInline(field) {
    const form = document.getElementById('form_' + field);
    const display = document.getElementById('display_' + field);
    const input = form.querySelector('input, textarea, select');
    
    if (!input.checkValidity()) {
        input.reportValidity();
        return;
    }
    
    const formData = new FormData();
    formData.append(field, input.value);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');
    
    const originalText = input.value;
    input.disabled = true;
    
    fetch('{{ route('driver.profile.update') }}', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update display value
            const displayValue = display.querySelector('.inline-edit-value');
            if (displayValue) displayValue.textContent = originalText || 'Not set';
            
            cancelInline(field);
            window.toastManager.success('Saved successfully!');
        } else {
            window.toastManager.error(data.message || 'Failed to save');
        }
    })
    .catch(() => window.toastManager.error('Failed to save'))
    .finally(() => input.disabled = false);
}

// Click to edit
document.querySelectorAll('.inline-edit-display').forEach(display => {
    display.addEventListener('click', function() {
        const field = this.dataset.field;
        if (field && !this.classList.contains('editing')) {
            enableEdit(field);
        }
    });
});

// Vehicle edit
function enableEditVehicle() {
    document.getElementById('vehicleDisplay').classList.add('d-none');
    document.getElementById('vehicleForm').classList.remove('d-none');
}

function cancelVehicleEdit() {
    document.getElementById('vehicleForm').classList.add('d-none');
    document.getElementById('vehicleDisplay').classList.remove('d-none');
}

// Availability toggle
function toggleAvailability(toggle) {
    const isAvailable = toggle.checked;
    
    fetch('{{ route('driver.availability.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ is_available: isAvailable })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('availabilityIcon').className = 'availability-icon ' + (isAvailable ? 'online' : 'offline');
            document.getElementById('availabilityIcon').innerHTML = '<i class="fas fa-circle"></i>';
            document.getElementById('availabilityStatus').textContent = isAvailable ? 'Available for Orders' : 'Offline';
            document.getElementById('availabilitySubtitle').textContent = isAvailable ? "You'll receive new order notifications" : 'Toggle to go online';
            
            window.toastManager.success(isAvailable ? 'You are now online!' : 'You are now offline');
        } else {
            toggle.checked = !isAvailable;
            window.toastManager.error(data.message || 'Failed to update availability');
        }
    })
    .catch(() => {
        toggle.checked = !isAvailable;
        window.toastManager.error('Failed to update availability');
    });
}

// Notification toggles
function toggleNotification(key, toggle) {
    fetch('{{ route('driver.notifications.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key: key, enabled: toggle.checked })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            toggle.checked = !toggle.checked;
            window.toastManager.error(data.message || 'Failed to update');
        }
    })
    .catch(() => {
        toggle.checked = !toggle.checked;
        window.toastManager.error('Failed to update');
    });
}

// Save preferences
function savePreference(key, value) {
    fetch('{{ route('driver.preferences.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key: key, value: value })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.toastManager.success('Preference saved');
        } else {
            window.toastManager.error(data.message || 'Failed to save');
        }
    })
    .catch(() => window.toastManager.error('Failed to save'));
}

// Document upload
function uploadDocument(key) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*,.pdf';
    input.onchange = function(e) {
        if (e.target.files[0]) {
            const formData = new FormData();
            formData.append('document', e.target.files[0]);
            formData.append('type', key);
            formData.append('_token', '{{ csrf_token() }}');
            
            window.toastManager.info('Uploading...', { title: 'Upload' });
            
            fetch('{{ route('driver.documents.upload') }}', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.toastManager.success('Document uploaded for review!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    window.toastManager.error(data.message || 'Upload failed');
                }
            })
            .catch(() => window.toastManager.error('Upload failed'));
        }
    };
    input.click();
}

// Performance period
function changePerformancePeriod(period) {
    const url = new URL(window.location);
    url.searchParams.set('performance_period', period);
    window.location.href = url.toString();
}

// Delete account confirmation
document.getElementById('deleteConfirmation')?.addEventListener('input', function() {
    const btn = document.getElementById('confirmDeleteBtn');
    const checkbox = document.getElementById('deleteUnderstand');
    btn.disabled = this.value !== 'DELETE' || !checkbox.checked;
});

document.getElementById('deleteUnderstand')?.addEventListener('change', function() {
    const btn = document.getElementById('confirmDeleteBtn');
    const input = document.getElementById('deleteConfirmation');
    btn.disabled = input.value !== 'DELETE' || !this.checked;
});

function deleteAccount() {
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';
    
    fetch('{{ route('driver.account.delete') }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.toastManager.success('Account deleted');
            window.location.href = '{{ route('login') }}';
        } else {
            window.toastManager.error(data.message || 'Failed to delete');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash me-1"></i>Delete My Account';
        }
    })
    .catch(() => {
        window.toastManager.error('Failed to delete');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-trash me-1"></i>Delete My Account';
    });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + P to toggle availability
    if (e.altKey && e.key === 'p') {
        e.preventDefault();
        const toggle = document.getElementById('availabilityToggle');
        if (toggle) toggle.click();
    }
    
    // Escape to cancel inline edits
    if (e.key === 'Escape') {
        document.querySelectorAll('.inline-edit-form.active').forEach(form => {
            const field = form.id.replace('form_', '');
            cancelInline(field);
        });
        document.getElementById('vehicleForm')?.classList.add('d-none');
        document.getElementById('vehicleDisplay')?.classList.remove('d-none');
    }
});

// Enter to save inline edits
document.querySelectorAll('.inline-edit-form input, .inline-edit-form textarea').forEach(input => {
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const field = this.closest('.inline-edit-form').id.replace('form_', '');
            saveInline(field);
        }
    });
});

// Auto-save form data to localStorage for offline support
const forms = document.querySelectorAll('form[id$="Form"]');
forms.forEach(form => {
    const key = 'form_' + form.id;
    const saved = localStorage.getItem(key);
    if (saved) {
        try {
            const data = JSON.parse(saved);
            Object.keys(data).forEach(name => {
                const input = form.querySelector('[name="' + name + '"]');
                if (input && input.type !== 'file') input.value = data[name];
            });
        } catch(e) {}
    }
    
    form.addEventListener('input', function() {
        const data = {};
        form.querySelectorAll('input, textarea, select').forEach(input => {
            if (input.type !== 'file' && input.name) data[input.name] = input.value;
        });
        localStorage.setItem(key, JSON.stringify(data));
    });
    
    form.addEventListener('submit', function() {
        localStorage.removeItem(key);
    });
});

// PWA install prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    // Could show custom install button here
});

// Service worker registration
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('{{ asset('sw.js') }}')
        .then(reg => console.log('SW registered'))
        .catch(err => console.log('SW registration failed:', err));
}
</script>
@endpush
@endsection