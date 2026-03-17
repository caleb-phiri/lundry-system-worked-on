@extends('layouts.client')

@section('title', 'Transaction History')

@section('client-content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Transaction History</h2>
        <div>
            <a href="{{ route('client.wallet.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left"></i> Back to Wallet
            </a>
            <a href="{{ route('client.wallet.topup') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Top Up
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Description</th>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M j, Y g:i A') }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td><code>{{ $transaction->reference }}</code></td>
                                <td>
                                    <span class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }} fw-bold">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }}K{{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('client.wallet.transaction.show', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection