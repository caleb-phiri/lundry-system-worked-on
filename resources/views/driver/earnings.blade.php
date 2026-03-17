@extends('layouts.driver')

@section('title', 'My Earnings')
@section('page-title', 'Earnings')

@section('content')
<div class="container-fluid">
    <!-- Earnings Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Wallet Balance</h6>
                            <h2 class="text-white mb-0">K{{ number_format($wallet->balance ?? 0, 2) }}</h2>
                        </div>
                        <i class="fas fa-wallet fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Earnings</h6>
                            <h2 class="text-white mb-0">K{{ number_format($today ?? 0, 2) }}</h2>
                        </div>
                        <i class="fas fa-sun fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">This Week</h6>
                            <h2 class="text-white mb-0">K{{ number_format($week ?? 0, 2) }}</h2>
                        </div>
                        <i class="fas fa-calendar-week fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">This Month</h6>
                            <h2 class="text-white mb-0">K{{ number_format($month ?? 0, 2) }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Lifetime Earnings</h6>
                            <h2 class="text-white mb-0">K{{ number_format($wallet->lifetime_earnings ?? 0, 2) }}</h2>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Lifetime Spent</h6>
                            <h2 class="text-white mb-0">K{{ number_format($wallet->lifetime_spent ?? 0, 2) }}</h2>
                        </div>
                        <i class="fas fa-credit-card fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Transaction History</h5>
            @if(isset($transactions) && $transactions->count() > 0)
            <div>
                <button class="btn btn-sm btn-primary" onclick="exportTransactions()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
            @endif
        </div>
        <div class="card-body">
            @if(isset($transactions) && $transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Reference</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M j, Y g:i A') }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td><code>{{ $transaction->reference }}</code></td>
                                <td>
                                    <span class="badge bg-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="text-end {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}K{{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="text-end">K{{ number_format($transaction->balance_after ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-4x text-muted mb-3"></i>
                    <h6>No Transactions Yet</h6>
                    <p class="text-muted small">Your earnings will appear here once you complete deliveries</p>
                    <a href="{{ route('driver.orders.index') }}" class="btn btn-primary btn-sm mt-2">
                        Browse Available Orders
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
@if(isset($transactions) && $transactions->count() > 0)
<script>
function exportTransactions() {
    window.location.href = "{{ route('driver.earnings.export') }}";
}
</script>
@endif
@endpush
@endsection