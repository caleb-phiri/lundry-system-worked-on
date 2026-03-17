@extends('layouts.client')

@section('title', 'My Wallet')

@push('styles')
<style>
    :root {
        --primary: #4361ee;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    .wallet-header {
        background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
        color: white;
        padding: 2rem;
        border-radius: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
    }

    .balance-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .balance-amount {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .stat-item {
        background: var(--light);
        border-radius: 1rem;
        padding: 1.25rem;
        text-align: center;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
    }

    .transaction-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        transition: all 0.3s;
    }

    .transaction-item:hover {
        background: var(--light);
    }

    .transaction-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-credit {
        background: #d1fae5;
        color: var(--success);
    }

    .icon-debit {
        background: #fee2e2;
        color: var(--danger);
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('client-content')
<div class="container py-4">
    <!-- Wallet Header -->
    <div class="wallet-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-3">My Wallet</h2>
                <div class="balance-card">
                    <small class="text-white-50">Available Balance</small>
                    <div class="balance-amount">K{{ number_format($wallet->balance, 2) }}</div>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('client.wallet.topup') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i>Top Up
                        </a>
                        <button class="btn btn-outline-light" onclick="exportStatement()">
                            <i class="fas fa-download me-2"></i>Statement
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">K{{ number_format($stats['total_credited'], 2) }}</div>
                        <div class="stat-label">Total Credited</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">K{{ number_format($stats['total_debited'], 2) }}</div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Transactions</h5>
                    <a href="{{ route('client.wallet.transactions') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                            <h6>No transactions yet</h6>
                            <p class="text-muted">Your transactions will appear here</p>
                            <a href="{{ route('client.wallet.topup') }}" class="btn btn-primary btn-sm">
                                Top Up Now
                            </a>
                        </div>
                    @else
                        @foreach($recentTransactions as $transaction)
                            <div class="transaction-item">
                                <div class="transaction-icon icon-{{ $transaction->type }}">
                                    <i class="fas fa-{{ $transaction->type === 'credit' ? 'arrow-down' : 'arrow-up' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $transaction->description }}</h6>
                                            <small class="text-muted">
                                                {{ $transaction->created_at->format('M j, Y g:i A') }}
                                                @if($transaction->reference)
                                                    • Ref: {{ $transaction->reference }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->type === 'credit' ? '+' : '-' }}K{{ number_format($transaction->amount, 2) }}
                                            </div>
                                            <span class="badge-{{ $transaction->status }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.wallet.topup') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Top Up Wallet
                        </a>
                        <a href="{{ route('client.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Create Order
                        </a>
                        <button class="btn btn-outline-secondary" onclick="printStatement()">
                            <i class="fas fa-print me-2"></i>Print Statement
                        </button>
                    </div>
                </div>
            </div>

            <!-- Wallet Info -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Wallet Info</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Lifetime Earnings</span>
                            <span class="fw-bold">K{{ number_format($wallet->lifetime_earnings, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Lifetime Spent</span>
                            <span class="fw-bold">K{{ number_format($wallet->lifetime_spent, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Pending Credits</span>
                            <span class="fw-bold text-warning">K{{ number_format($stats['pending_credits'], 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportStatement() {
    window.location.href = "{{ route('client.wallet.statement') }}";
}

function printStatement() {
    window.print();
}
</script>
@endpush
@endsection