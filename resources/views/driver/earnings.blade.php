@extends('layouts.driver')

@section('title', 'My Earnings')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<style>
/* Earnings Page Specific Styles */
.earnings-header {
    background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-600) 100%);
    color: white;
    padding: var(--space-6);
    border-radius: var(--radius-xl);
    margin-bottom: var(--space-6);
    position: relative;
    overflow: hidden;
}

.earnings-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transform: rotate(30deg);
}

.earnings-total {
    font-size: var(--font-size-4xl);
    font-weight: var(--font-weight-bold);
    line-height: 1;
    margin-bottom: var(--space-2);
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.earnings-subtitle {
    font-size: var(--font-size-base);
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: var(--space-2);
    flex-wrap: wrap;
}

.period-badge {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-full);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-semibold);
    border: 1px solid rgba(255,255,255,0.3);
}

/* Stat Cards */
.earnings-stat-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    transition: all var(--transition-base);
    position: relative;
    overflow: hidden;
}

.earnings-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.earnings-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.earnings-stat-card.primary::before {
    background: linear-gradient(90deg, var(--color-primary-500), var(--color-primary-300));
}

.earnings-stat-card.success::before {
    background: linear-gradient(90deg, var(--color-success), var(--color-success-light));
}

.earnings-stat-card.warning::before {
    background: linear-gradient(90deg, var(--color-warning), var(--color-warning-light));
}

.earnings-stat-card.info::before {
    background: linear-gradient(90deg, var(--color-info), var(--color-info-light));
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-xl);
    margin-bottom: var(--space-3);
}

.stat-icon.primary { background: var(--color-primary-100); color: var(--color-primary-600); }
.stat-icon.success { background: var(--color-success-light); color: var(--color-success); }
.stat-icon.warning { background: var(--color-warning-light); color: var(--color-warning-dark); }
.stat-icon.info { background: var(--color-info-light); color: var(--color-info); }

.stat-value {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-gray-900);
    line-height: 1;
    margin-bottom: var(--space-1);
}

.stat-label {
    font-size: var(--font-size-sm);
    color: var(--color-gray-500);
    font-weight: var(--font-weight-medium);
}

.stat-trend {
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
    margin-top: var(--space-2);
    display: flex;
    align-items: center;
    gap: var(--space-1);
}

.stat-trend.positive { color: var(--color-success); }
.stat-trend.negative { color: var(--color-danger); }

/* Chart Container */
.chart-container {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    height: 350px;
    position: relative;
}

.chart-container canvas {
    max-height: 300px;
}

/* Date Range Picker */
.date-range-picker {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    flex-wrap: wrap;
}

.date-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.date-input-group i {
    position: absolute;
    left: var(--space-3);
    color: var(--color-gray-400);
    z-index: 2;
}

.date-input-group input {
    padding-left: var(--space-10);
    border: 1px solid var(--color-gray-200);
    border-radius: var(--radius-lg);
    background: var(--color-white);
    font-size: var(--font-size-sm);
    min-width: 180px;
    transition: all var(--transition-fast);
}

.date-input-group input:focus {
    outline: none;
    border-color: var(--color-primary-500);
    box-shadow: 0 0 0 3px var(--color-primary-100);
}

.date-separator {
    color: var(--color-gray-400);
    font-weight: var(--font-weight-medium);
}

/* Quick Period Buttons */
.quick-periods {
    display: flex;
    gap: var(--space-2);
    flex-wrap: wrap;
}

.quick-period-btn {
    padding: var(--space-2) var(--space-4);
    border: 1px solid var(--color-gray-200);
    background: var(--color-white);
    border-radius: var(--radius-lg);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-600);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.quick-period-btn:hover {
    border-color: var(--color-primary-300);
    color: var(--color-primary-600);
    background: var(--color-primary-50);
}

.quick-period-btn.active {
    background: var(--color-primary-500);
    border-color: var(--color-primary-500);
    color: var(--color-white);
}

/* Withdraw CTA */
.withdraw-cta {
    background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success-dark) 100%);
    border: none;
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    color: var(--color-white);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
    flex-wrap: wrap;
    transition: all var(--transition-base);
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
}

.withdraw-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
}

.withdraw-info {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.withdraw-label {
    font-size: var(--font-size-sm);
    opacity: 0.9;
}

.withdraw-amount {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-bold);
}

.withdraw-arrow {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-xl);
    transition: all var(--transition-base);
}

.withdraw-cta:hover .withdraw-arrow {
    background: rgba(255,255,255,0.3);
    transform: translateX(4px);
}

/* Earnings Table */
.earnings-table-container {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.earnings-table-header {
    background: linear-gradient(135deg, var(--color-gray-50) 0%, var(--color-white) 100%);
    border-bottom: 1px solid var(--card-border);
    padding: var(--space-4) var(--space-5);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: var(--space-3);
}

.table-title {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
}

.table-actions {
    display: flex;
    gap: var(--space-2);
}

.earnings-table {
    width: 100%;
    border-collapse: collapse;
}

.earnings-table th {
    background: var(--color-gray-50);
    padding: var(--space-3) var(--space-4);
    text-align: left;
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--card-border);
    white-space: nowrap;
}

.earnings-table td {
    padding: var(--space-4);
    border-bottom: 1px solid var(--card-border);
    vertical-align: middle;
}

.earnings-table tr:last-child td {
    border-bottom: none;
}

.earnings-table tbody tr {
    transition: all var(--transition-fast);
}

.earnings-table tbody tr:hover {
    background: var(--color-gray-50);
}

/* Daily Grouping */
.daily-group {
    background: var(--color-gray-50);
}

.daily-group td {
    padding: var(--space-3) var(--space-4);
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-700);
    border-top: 2px solid var(--color-gray-200);
}

.daily-group:first-child td {
    border-top: none;
}

.daily-summary {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    margin-left: var(--space-4);
    padding-top: var(--space-2);
}

.daily-summary-item {
    display: flex;
    align-items: center;
    gap: var(--space-1);
    font-size: var(--font-size-sm);
    color: var(--color-gray-600);
}

.daily-summary-value {
    font-weight: var(--font-weight-semibold);
    color: var(--color-gray-900);
}

.daily-summary-value.earnings { color: var(--color-success); }
.daily-summary-value.orders { color: var(--color-primary-600); }

/* Transaction Row */
.transaction-date {
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-900);
}

.transaction-time {
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
}

.transaction-order {
    font-size: var(--font-size-sm);
    color: var(--color-primary-600);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
}

.transaction-order:hover {
    text-decoration: underline;
}

.transaction-type {
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
}

.transaction-type.delivery { background: var(--color-primary-100); color: var(--color-primary-700); }
.transaction-type.bonus { background: var(--color-warning-light); color: var(--color-warning-dark); }
.transaction-type.tip { background: var(--color-success-light); color: var(--color-success-dark); }
.transaction-type.adjustment { background: var(--color-info-light); color: var(--color-info-dark); }

.transaction-amount {
    font-weight: var(--font-weight-bold);
    font-size: var(--font-size-base);
}

.transaction-amount.positive { color: var(--color-success); }
.transaction-amount.negative { color: var(--color-danger); }

.transaction-status {
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-full);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-semibold);
}

.transaction-status.paid { background: var(--color-success-light); color: var(--color-success-dark); }
.transaction-status.pending { background: var(--color-warning-light); color: var(--color-warning-dark); }
.transaction-status.failed { background: var(--color-danger-light); color: var(--color-danger-dark); }

/* Summary Cards at Bottom */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-4);
    margin-top: var(--space-6);
}

.summary-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    transition: all var(--transition-base);
}

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.summary-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-xl);
    flex-shrink: 0;
}

.summary-icon.primary { background: var(--color-primary-100); color: var(--color-primary-600); }
.summary-icon.success { background: var(--color-success-light); color: var(--color-success); }
.summary-icon.warning { background: var(--color-warning-light); color: var(--color-warning-dark); }
.summary-icon.info { background: var(--color-info-light); color: var(--color-info); }

.summary-content {
    flex: 1;
}

.summary-value {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-gray-900);
    line-height: 1;
}

.summary-label {
    font-size: var(--font-size-sm);
    color: var(--color-gray-500);
    margin-top: var(--space-1);
}

/* Empty State */
.earnings-empty {
    text-align: center;
    padding: var(--space-12) var(--space-6);
}

.earnings-empty i {
    font-size: 4rem;
    color: var(--color-gray-300);
    margin-bottom: var(--space-4);
}

.earnings-empty h3 {
    color: var(--color-gray-600);
    margin-bottom: var(--space-2);
}

.earnings-empty p {
    color: var(--color-gray-500);
}

/* Loading Skeleton */
.skeleton-row td {
    padding: var(--space-4);
}

.skeleton-cell {
    height: 20px;
    border-radius: var(--radius-base);
    background: linear-gradient(90deg, var(--color-gray-200) 25%, var(--color-gray-100) 50%, var(--color-gray-200) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

/* Mobile Responsive */
@media (max-width: 992px) {
    .earnings-total {
        font-size: var(--font-size-3xl);
    }
    .chart-container {
        height: 300px;
    }
}

@media (max-width: 768px) {
    .earnings-header {
        padding: var(--space-4);
    }
    .earnings-total {
        font-size: var(--font-size-2xl);
    }
    .earnings-stat-card {
        padding: var(--space-4);
    }
    .stat-value {
        font-size: var(--font-size-xl);
    }
    .date-input-group input {
        min-width: 140px;
        padding: var(--space-2) var(--space-3) var(--space-2) var(--space-9);
    }
    .chart-container {
        padding: var(--space-3);
        height: 280px;
    }
    .earnings-table-container {
        border-radius: var(--radius-lg);
    }
    .earnings-table th,
    .earnings-table td {
        padding: var(--space-2) var(--space-3);
        font-size: var(--font-size-sm);
    }
    .withdraw-cta {
        flex-direction: column;
        text-align: center;
        padding: var(--space-4);
    }
    .withdraw-arrow {
        transform: rotate(90deg);
    }
    .withdraw-cta:hover .withdraw-arrow {
        transform: rotate(90deg) translateY(-4px);
    }
    .daily-summary {
        flex-wrap: wrap;
        margin-left: 0;
        padding-top: var(--space-2);
    }
    .summary-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .earnings-stat-card {
        padding: var(--space-3);
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: var(--font-size-lg);
    }
    .quick-period-btn {
        padding: var(--space-2) var(--space-3);
        font-size: var(--font-size-xs);
    }
    .date-input-group input {
        min-width: 120px;
        font-size: var(--font-size-xs);
    }
    .earnings-table th:nth-child(4),
    .earnings-table td:nth-child(4),
    .earnings-table th:nth-child(5),
    .earnings-table td:nth-child(5) {
        display: none;
    }
}

/* Print Styles */
@media print {
    .withdraw-cta, .quick-periods, .date-range-picker, .table-actions, .sidebar, .navbar-top {
        display: none !important;
    }
    .earnings-header {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .earnings-table-container {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    .earnings-table th {
        background: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

/* Animation for chart */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.chart-container {
    animation: fadeInUp 0.6s ease-out;
}

.earnings-stat-card:nth-child(1) { animation: fadeInUp 0.4s ease-out 0.1s both; }
.earnings-stat-card:nth-child(2) { animation: fadeInUp 0.4s ease-out 0.2s both; }
.earnings-stat-card:nth-child(3) { animation: fadeInUp 0.4s ease-out 0.3s both; }
.earnings-stat-card:nth-child(4) { animation: fadeInUp 0.4s ease-out 0.4s both; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header with Total Earnings -->
    <div class="earnings-header" data-aos="fade-down">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
            <div>
                <div class="earnings-total">K{{ number_format($totalEarnings, 2) }}</div>
                <div class="earnings-subtitle">
                    <span>Total Earnings</span>
                    <span class="period-badge">{{ $periodLabel }}</span>
                </div>
            </div>
            <div class="text-md-end">
                <div class="d-flex align-items-center justify-content-md-end gap-2 mb-2 flex-wrap">
                    <x-driver.dropdown
                        :options="[
                            ['label' => 'Today', 'value' => 'today', 'icon' => 'calendar-day'],
                            ['label' => 'This Week', 'value' => 'week', 'icon' => 'calendar-week'],
                            ['label' => 'This Month', 'value' => 'month', 'icon' => 'calendar'],
                            ['label' => 'This Year', 'value' => 'year', 'icon' => 'calendar-alt'],
                            ['label' => 'Custom Range', 'value' => 'custom', 'icon' => 'calendar-alt'],
                        ]"
                        :selected="$currentPeriod"
                        onChange="changePeriod"
                        className="ms-auto"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Period Buttons & Date Range Picker -->
    <div class="card mb-4" data-aos="fade-up">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="quick-periods" role="group" aria-label="Quick period selection">
                    @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $value => $label)
                        <button type="button" 
                                class="quick-period-btn {{ $currentPeriod === $value ? 'active' : '' }}"
                                onclick="changePeriod('{{ $value }}')"
                                aria-pressed="{{ $currentPeriod === $value ? 'true' : 'false' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                <div class="date-range-picker" {{ $currentPeriod !== 'custom' ? 'style="display: none;"' : '' }} id="customDateRange">
                    <div class="date-input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="text" id="startDate" placeholder="Start Date" value="{{ $startDate ?? '' }}" readonly>
                    </div>
                    <span class="date-separator">to</span>
                    <div class="date-input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="text" id="endDate" placeholder="End Date" value="{{ $endDate ?? '' }}" readonly>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" onclick="applyCustomRange()">
                        <i class="fas fa-check me-1"></i>Apply
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-6" data-aos="fade-up">
        <div class="col-md-6 col-lg-3">
            <div class="earnings-stat-card primary">
                <div class="stat-icon primary"><i class="fas fa-truck"></i></div>
                <div class="stat-value">{{ $stats['completed_deliveries'] ?? 0 }}</div>
                <div class="stat-label">Completed Deliveries</div>
                @if(isset($stats['deliveries_trend']))
                    <div class="stat-trend {{ $stats['deliveries_trend'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-arrow-{{ $stats['deliveries_trend'] >= 0 ? 'up' : 'down' }}"></i>
                        <span>{{ abs($stats['deliveries_trend']) }}% vs last period</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="earnings-stat-card success">
                <div class="stat-icon success"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-value">K{{ number_format($stats['total_earnings'] ?? 0, 2) }}</div>
                <div class="stat-label">Total Earnings</div>
                @if(isset($stats['earnings_trend']))
                    <div class="stat-trend {{ $stats['earnings_trend'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-arrow-{{ $stats['earnings_trend'] >= 0 ? 'up' : 'down' }}"></i>
                        <span>{{ abs($stats['earnings_trend']) }}% vs last period</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="earnings-stat-card warning">
                <div class="stat-icon warning"><i class="fas fa-chart-line"></i></div>
                <div class="stat-value">K{{ number_format($stats['avg_per_delivery'] ?? 0, 2) }}</div>
                <div class="stat-label">Avg. per Delivery</div>
                @if(isset($stats['avg_trend']))
                    <div class="stat-trend {{ $stats['avg_trend'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-arrow-{{ $stats['avg_trend'] >= 0 ? 'up' : 'down' }}"></i>
                        <span>{{ abs($stats['avg_trend']) }}% vs last period</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="earnings-stat-card info">
                <div class="stat-icon info"><i class="fas fa-clock"></i></div>
                <div class="stat-value">{{ $stats['active_hours'] ?? 0 }}h</div>
                <div class="stat-label">Active Hours</div>
                @if(isset($stats['hours_trend']))
                    <div class="stat-trend {{ $stats['hours_trend'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-arrow-{{ $stats['hours_trend'] >= 0 ? 'up' : 'down' }}"></i>
                        <span>{{ abs($stats['hours_trend']) }}% vs last period</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row g-4 mb-6" data-aos="fade-up">
        <div class="col-lg-8">
            <x-driver.card class="chart-container">
                <x-slot name="header">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <span><i class="fas fa-chart-bar me-2"></i>Earnings Trend</span>
                        <div class="btn-group btn-group-sm" role="group">
                            @foreach(['day' => 'Daily', 'week' => 'Weekly', 'month' => 'Monthly'] as $granularity => $label)
                                <button type="button" 
                                        class="btn btn-outline-secondary {{ $chartGranularity === $granularity ? 'active' : '' }}"
                                        onclick="changeGranularity('{{ $granularity }}')"
                                        aria-pressed="{{ $chartGranularity === $granularity ? 'true' : 'false' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </x-slot>
                <div class="card-body p-0">
                    <canvas id="earningsChart" aria-label="Earnings chart over time"></canvas>
                </div>
            </x-driver.card>
        </div>
        <div class="col-lg-4">
            <!-- Withdraw CTA -->
            <div class="withdraw-cta" onclick="openWithdrawModal()" data-aos="fade-up" data-aos-delay="200" role="button" tabindex="0" aria-label="Withdraw earnings">
                <div class="withdraw-info">
                    <span class="withdraw-label">Available Balance</span>
                    <span class="withdraw-amount">K{{ number_format($availableBalance ?? $totalEarnings, 2) }}</span>
                </div>
                <div class="withdraw-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>

            <!-- Quick Stats -->
            <x-driver.card class="mt-4" data-aos="fade-up" data-aos-delay="300">
                <x-slot name="header">
                    <i class="fas fa-percent"></i>
                    Breakdown
                </x-slot>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted small">Base Earnings</span>
                        <span class="fw-semibold">K{{ number_format($stats['base_earnings'] ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted small">Tips Received</span>
                        <span class="fw-semibold text-success">+K{{ number_format($stats['tips'] ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted small">Bonuses</span>
                        <span class="fw-semibold text-warning">+K{{ number_format($stats['bonuses'] ?? 0, 2) }}</span>
                    </div>
                    @if(($stats['adjustments'] ?? 0) != 0)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted small">Adjustments</span>
                        <span class="fw-semibold {{ ($stats['adjustments'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">{{ ($stats['adjustments'] ?? 0) >= 0 ? '+' : '' }}K{{ number_format($stats['adjustments'] ?? 0, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <span class="fw-semibold">Net Earnings</span>
                        <span class="fw-bold text-primary fs-5">K{{ number_format($totalEarnings, 2) }}</span>
                    </div>
                </div>
            </x-driver.card>
        </div>
    </div>

    <!-- Transactions Table -->
    <x-driver.card class="earnings-table-container mb-6" data-aos="fade-up">
        <x-slot name="header">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <span class="table-title">
                    <i class="fas fa-list me-2"></i>Transaction History
                    <span class="badge bg-primary ms-2">{{ $transactions->count() ?? 0 }} transactions</span>
                </span>
                <div class="table-actions">
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportEarnings()">
                        <i class="fas fa-download me-1"></i>Export CSV
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printEarnings()">
                        <i class="fas fa-print me-1"></i>Print
                    </button>
                </div>
            </div>
        </x-slot>
        
        @if($transactions->isNotEmpty())
            <div class="table-responsive">
                <table class="earnings-table" id="earningsTable">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 150px;">Date</th>
                            <th scope="col" style="width: 200px;">Order</th>
                            <th scope="col" style="width: 120px;">Type</th>
                            <th scope="col" style="width: 100px;">Amount</th>
                            <th scope="col" style="width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentDate = null;
                            $dailyTotal = 0;
                            $dailyOrders = 0;
                        @endphp
                        @foreach($transactions as $transaction)
                            @php
                                $transDate = \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d');
                                $transDateDisplay = \Carbon\Carbon::parse($transaction->created_at)->format('M j, Y');
                                
                                // Check if new day
                                if ($currentDate !== $transDate) {
                                    // Close previous day group
                                    if ($currentDate !== null) {
                                        echo '<tr class="daily-group"><td colspan="5"><div class="daily-summary">';
                                        echo '<span class="daily-summary-item"><i class="fas fa-receipt"></i><span class="daily-summary-value orders">' . $dailyOrders . ' orders</span></span>';
                                        echo '<span class="daily-summary-item"><i class="fas fa-money-bill-wave"></i><span class="daily-summary-value earnings">K' . number_format($dailyTotal, 2) . '</span></span>';
                                        echo '</div></td></tr>';
                                    }
                                    // Start new day group
                                    echo '<tr class="daily-group"><td colspan="5"><strong>' . \Carbon\Carbon::parse($transaction->created_at)->format('l, M j, Y') . '</strong></td></tr>';
                                    $currentDate = $transDate;
                                    $dailyTotal = 0;
                                    $dailyOrders = 0;
                                }
                                $dailyTotal += $transaction->amount;
                                $dailyOrders++;
                            @endphp
                            <tr data-order-id="{{ $transaction->order_id ?? '' }}">
                                <td>
                                    <div class="transaction-date">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M j') }}</div>
                                    <div class="transaction-time">{{ \Carbon\Carbon::parse($transaction->created_at)->format('g:i A') }}</div>
                                </td>
                                <td>
                                    @if($transaction->order_id)
                                        <a href="{{ route('driver.orders.show', $transaction->order_id) }}" class="transaction-order">
                                            #{{ $transaction->order_number ?? $transaction->order_id }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ $transaction->description ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $typeClass = 'delivery';
                                        $typeIcon = 'truck';
                                        $typeLabel = 'Delivery';
                                        if (str_contains(strtolower($transaction->type ?? ''), 'tip')) {
                                            $typeClass = 'tip';
                                            $typeIcon = 'hand-holding-heart';
                                            $typeLabel = 'Tip';
                                        } elseif (str_contains(strtolower($transaction->type ?? ''), 'bonus')) {
                                            $typeClass = 'bonus';
                                            $typeIcon = 'star';
                                            $typeLabel = 'Bonus';
                                        } elseif (str_contains(strtolower($transaction->type ?? ''), 'adjust')) {
                                            $typeClass = 'adjustment';
                                            $typeIcon = 'edit';
                                            $typeLabel = 'Adjustment';
                                        }
                                    @endphp
                                    <span class="transaction-type {{ $typeClass }}">
                                        <i class="fas fa-{{ $typeIcon }}"></i>
                                        {{ $typeLabel }}
                                    </span>
                                </td>
                                <td>
                                    <span class="transaction-amount {{ $transaction->amount >= 0 ? 'positive' : 'negative' }}">
                                        {{ $transaction->amount >= 0 ? '+' : '' }}K{{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'pending';
                                        $statusLabel = 'Pending';
                                        $statusIcon = 'clock';
                                        if (($transaction->status ?? 'pending') === 'paid' || ($transaction->is_paid ?? false)) {
                                            $statusClass = 'paid';
                                            $statusLabel = 'Paid';
                                            $statusIcon = 'check-circle';
                                        } elseif (($transaction->status ?? 'pending') === 'failed') {
                                            $statusClass = 'failed';
                                            $statusLabel = 'Failed';
                                            $statusIcon = 'times-circle';
                                        }
                                    @endphp
                                    <span class="transaction-status {{ $statusClass }}">
                                        <i class="fas fa-{{ $statusIcon }}"></i>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        @php
                            // Close last day group
                            if ($currentDate !== null) {
                                echo '<tr class="daily-group"><td colspan="5"><div class="daily-summary">';
                                echo '<span class="daily-summary-item"><i class="fas fa-receipt"></i><span class="daily-summary-value orders">' . $dailyOrders . ' orders</span></span>';
                                echo '<span class="daily-summary-item"><i class="fas fa-money-bill-wave"></i><span class="daily-summary-value earnings">K' . number_format($dailyTotal, 2) . '</span></span>';
                                echo '</div></td></tr>';
                            }
                        @endphp
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            {{ $transactions->links('vendor.pagination.bootstrap-5') }}
        @else
            <div class="earnings-empty">
                <i class="fas fa-receipt"></i>
                <h3>No Transactions Yet</h3>
                <p>Complete deliveries to see your earnings history here.</p>
                <a href="{{ route('driver.dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-home me-2"></i>Go to Dashboard
                </a>
            </div>
        @endif
    </x-driver.card>

    <!-- Summary Cards -->
    <div class="summary-grid" data-aos="fade-up">
        <div class="summary-card">
            <div class="summary-icon primary"><i class="fas fa-calendar-check"></i></div>
            <div class="summary-content">
                <div class="summary-value">{{ $stats['working_days'] ?? 0 }}</div>
                <div class="summary-label">Working Days</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon success"><i class="fas fa-money-bill-wave"></i></div>
            <div class="summary-content">
                <div class="summary-value">K{{ number_format($stats['avg_daily_earnings'] ?? 0, 2) }}</div>
                <div class="summary-label">Avg. Daily Earnings</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon warning"><i class="fas fa-trophy"></i></div>
            <div class="summary-content">
                <div class="summary-value">{{ $stats['best_day_earnings'] ?? 0 }}</div>
                <div class="summary-label">Best Day (K{{ number_format($stats['best_day_amount'] ?? 0, 2) }})</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon info"><i class="fas fa-percent"></i></div>
            <div class="summary-content">
                <div class="summary-value">{{ $stats['completion_rate'] ?? 0 }}%</div>
                <div class="summary-label">Completion Rate</div>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel"><i class="fas fa-money-bill-transfer me-2"></i>Withdraw Earnings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('driver.earnings.withdraw') }}" method="POST" id="withdrawForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Available balance: <strong>K{{ number_format($availableBalance ?? $totalEarnings, 2) }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <label for="withdrawAmount" class="form-label fw-semibold">Amount to Withdraw</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">K</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="withdrawAmount" 
                                   name="amount" 
                                   min="1" 
                                   max="{{ $availableBalance ?? $totalEarnings }}"
                                   step="0.01"
                                   required
                                   placeholder="Enter amount">
                        </div>
                        <div class="form-text">Minimum withdrawal: K1.00</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Withdrawal Method</label>
                        <div class="row g-2">
                            @foreach(['mobile_money' => ['Mobile Money', 'mobile-alt', 'Airtel Money / MTN MoMo'], 'bank_transfer' => ['Bank Transfer', 'university', 'Direct to bank account'], 'cash_pickup' => ['Cash Pickup', 'hand-holding-usd', 'Collect from office']] as $key => $method)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" id="method_{{ $key }}" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} required>
                                        <label class="form-check-label w-100 p-3 border rounded-lg text-center h-100 d-flex flex-column align-items-center justify-content-center cursor-pointer" for="method_{{ $key }}">
                                            <i class="fas fa-{{ $method[1] }} fa-2x text-primary mb-2"></i>
                                            <span class="fw-medium">{{ $method[0] }}</span>
                                            <small class="text-muted">{{ $method[2] }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="withdrawPhone" class="form-label fw-semibold">Phone Number (for Mobile Money)</label>
                        <input type="tel" class="form-control" id="withdrawPhone" name="phone" placeholder="Enter your mobile money number" value="{{ auth()->user()->phone ?? '' }}">
                        <div class="form-text">Used for Mobile Money withdrawals only</div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="withdrawTerms" required>
                        <label class="form-check-label" for="withdrawTerms">
                            I confirm this withdrawal request and understand processing takes 1-2 business days.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Request Withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
<script>
// Earnings Chart
let earningsChart = null;
const chartData = @json($chartData ?? []);
const chartGranularity = '{{ $chartGranularity ?? "day" }}';

function initEarningsChart(data, granularity) {
    const ctx = document.getElementById('earningsChart').getContext('2d');
    
    if (earningsChart) {
        earningsChart.destroy();
    }
    
    const labels = data.map(d => d.label);
    const values = data.map(d => d.value);
    const orders = data.map(d => d.orders || 0);
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
    
    const gradientOrders = ctx.createLinearGradient(0, 0, 0, 300);
    gradientOrders.addColorStop(0, 'rgba(67, 97, 238, 0.4)');
    gradientOrders.addColorStop(1, 'rgba(67, 97, 238, 0)');
    
    earningsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Earnings (K)',
                    data: values,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    yAxisID: 'y',
                    order: 2
                },
                {
                    label: 'Orders',
                    data: orders,
                    type: 'line',
                    fill: true,
                    backgroundColor: gradientOrders,
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(67, 97, 238, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    yAxisID: 'y1',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, family: 'Inter' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 13, family: 'Inter', weight: '600' },
                    bodyFont: { size: 12, family: 'Inter' },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label === 'Earnings (K)') {
                                return 'Earnings: K' + context.raw.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                            return 'Orders: ' + context.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, family: 'Inter' } }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { display: true, text: 'Earnings (K)', font: { size: 11, family: 'Inter', weight: '600' } },
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: { font: { size: 11, family: 'Inter' }, callback: v => 'K' + v }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Orders', font: { size: 11, family: 'Inter', weight: '600' } },
                    grid: { drawOnChartArea: false },
                    ticks: { font: { size: 11, family: 'Inter' }, stepSize: 1 },
                    min: 0
                }
            },
            animation: {
                duration: 800,
                easing: 'easeOutQuart'
            }
        }
    });
}

// Initialize chart on load
document.addEventListener('DOMContentLoaded', function() {
    if (chartData.length > 0) {
        initEarningsChart(chartData, chartGranularity);
    }
});

// Flatpickr for date range
let startPicker = flatpickr('#startDate', {
    dateFormat: 'Y-m-d',
    maxDate: 'today',
    onChange: function(selectedDates) {
        if (selectedDates.length > 0) {
            endPicker.set('minDate', selectedDates[0]);
        }
    }
});

let endPicker = flatpickr('#endDate', {
    dateFormat: 'Y-m-d',
    maxDate: 'today',
    onChange: function(selectedDates) {
        if (selectedDates.length > 0) {
            startPicker.set('maxDate', selectedDates[0]);
        }
    }
});

// Period change
function changePeriod(period) {
    const url = new URL(window.location);
    url.searchParams.set('period', period);
    url.searchParams.delete('start_date');
    url.searchParams.delete('end_date');
    window.location.href = url.toString();
}

// Custom range
function applyCustomRange() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    
    if (!start || !end) {
        window.toastManager.warning('Please select both start and end dates');
        return;
    }
    
    const url = new URL(window.location);
    url.searchParams.set('period', 'custom');
    url.searchParams.set('start_date', start);
    url.searchParams.set('end_date', end);
    window.location.href = url.toString();
}

// Granularity change
function changeGranularity(granularity) {
    const url = new URL(window.location);
    url.searchParams.set('granularity', granularity);
    window.location.href = url.toString();
}

// Withdraw modal
function openWithdrawModal() {
    const modal = new bootstrap.Modal(document.getElementById('withdrawModal'));
    modal.show();
    
    // Focus amount input when modal opens
    document.getElementById('withdrawModal').addEventListener('shown.bs.modal', function() {
        document.getElementById('withdrawAmount').focus();
    }, { once: true });
}

// Quick amount buttons
document.getElementById('withdrawAmount').addEventListener('input', function() {
    const max = parseFloat(this.max);
    const val = parseFloat(this.value);
    if (val > max) this.value = max;
});

// Export earnings
function exportEarnings() {
    const table = document.getElementById('earningsTable');
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('th, td');
        const rowData = [];
        cols.forEach(col => {
            // Skip daily group rows
            if (!row.classList.contains('daily-group')) {
                rowData.push('"' + col.innerText.trim().replace(/"/g, '""') + '"');
            }
        });
        if (rowData.length > 0) csv.push(rowData.join(','));
    });
    
    const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'earnings-' + new Date().toISOString().split('T')[0] + '.csv';
    link.click();
    
    window.toastManager.success('Earnings exported successfully!');
}

// Print earnings
function printEarnings() {
    window.print();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + W to open withdraw
    if (e.altKey && e.key === 'w') {
        e.preventDefault();
        openWithdrawModal();
    }
    
    // Alt + E to export
    if (e.altKey && e.key === 'e') {
        e.preventDefault();
        exportEarnings();
    }
});

// Mobile touch handlers for chart
let touchStartX = 0;
document.getElementById('earningsChart')?.addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
}, { passive: true });

document.getElementById('earningsChart')?.addEventListener('touchend', function(e) {
    const touchEndX = e.changedTouches[0].screenX;
    const diff = touchEndX - touchStartX;
    if (Math.abs(diff) > 50) {
        // Swipe detected - could navigate periods
    }
}, { passive: true });

// Auto-refresh earnings every 5 minutes for active periods
@if(in_array($currentPeriod, ['today', 'week']))
    setInterval(function() {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newChartData = doc.querySelector('#earningsChart')?.dataset.chartData;
                if (newChartData) {
                    const data = JSON.parse(newChartData);
                    initEarningsChart(data, chartGranularity);
                }
            })
            .catch(console.error);
    }, 300000); // 5 minutes
@endif
</script>
@endpush
@endsection