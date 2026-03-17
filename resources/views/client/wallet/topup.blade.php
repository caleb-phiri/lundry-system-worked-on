@extends('layouts.client')

@section('title', 'Top Up Wallet')

@push('styles')
<style>
    .payment-method-card {
        border: 2px solid var(--border);
        border-radius: 1rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        height: 100%;
    }

    .payment-method-card:hover {
        border-color: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.1);
    }

    .payment-method-card.selected {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .payment-method-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .amount-preset {
        border: 2px solid var(--border);
        border-radius: 1rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .amount-preset:hover {
        border-color: var(--primary);
    }

    .amount-preset.selected {
        border-color: var(--primary);
        background: var(--primary-light);
    }
</style>
@endpush

@section('client-content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Top Up Your Wallet</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.wallet.topup.process') }}" method="POST" id="topupForm">
                        @csrf

                        <!-- Amount -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Enter Amount (K)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">K</span>
                                <input type="number" 
                                       name="amount" 
                                       id="amount" 
                                       class="form-control" 
                                       placeholder="0.00" 
                                       min="10" 
                                       max="10000" 
                                       step="0.01"
                                       required>
                            </div>
                            <small class="text-muted">Minimum: K10 | Maximum: K10,000</small>
                        </div>

                        <!-- Preset Amounts -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Quick Select</label>
                            <div class="row g-2">
                                @foreach([50, 100, 200, 500, 1000] as $preset)
                                    <div class="col">
                                        <div class="amount-preset" onclick="setAmount({{ $preset }})">
                                            <span class="fw-bold">K{{ $preset }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Payment Method</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="payment-method-card">
                                        <input type="radio" name="payment_method" value="mtn" class="d-none" required>
                                        <div class="payment-method-icon">📱</div>
                                        <h6>MTN Money</h6>
                                        <small class="text-muted">Mobile Money</small>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="payment-method-card">
                                        <input type="radio" name="payment_method" value="airtel" class="d-none">
                                        <div class="payment-method-icon">📱</div>
                                        <h6>Airtel Money</h6>
                                        <small class="text-muted">Mobile Money</small>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="payment-method-card">
                                        <input type="radio" name="payment_method" value="zamtel" class="d-none">
                                        <div class="payment-method-icon">📱</div>
                                        <h6>Zamtel Money</h6>
                                        <small class="text-muted">Mobile Money</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-arrow-right me-2"></i>Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setAmount(amount) {
    document.getElementById('amount').value = amount;
    
    // Highlight selected preset
    document.querySelectorAll('.amount-preset').forEach(el => {
        el.classList.remove('selected');
    });
    event.currentTarget.classList.add('selected');
}

// Handle payment method selection
document.querySelectorAll('.payment-method-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.payment-method-card').forEach(c => {
            c.classList.remove('selected');
        });
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
    });
});
</script>
@endpush
@endsection