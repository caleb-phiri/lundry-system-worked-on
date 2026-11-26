{{-- Driver Action Card Component --}}
@props([
    'title' => 'Actions',
    'icon' => 'fas fa-tasks',
    'actions' => [],
    'status' => null,
    'statusLabel' => null,
    'earnings' => null,
])

<div class="card action-card" {{ $attributes }}>
    <div class="card-header-custom">
        <i class="{{ $icon }}"></i>
        {{ $title }}
    </div>
    <div class="card-body-custom">
        @if($earnings)
            <div class="action-earnings mb-4 p-3 bg-primary-50 rounded-lg text-center">
                <div class="text-xs text-primary-600 font-medium mb-1">YOUR EARNINGS</div>
                <div class="text-2xl font-bold text-primary-700">K{{ number_format($earnings, 2) }}</div>
            </div>
        @endif
        
        @foreach($actions as $action)
            @if(!isset($action['visible']) || $action['visible']($status))
                @if(isset($action['form']))
                    <form action="{{ $action['form'] }}" method="POST" class="mb-3">
                        @csrf
                        @if(isset($action['method'])) @method($action['method']) @endif
                        <button type="submit" 
                                class="action-btn {{ $action['class'] ?? 'btn-primary' }} w-100"
                                {{ isset($action['confirm']) ? 'onclick="return confirm(\''.$action['confirm'].'\')"' : '' }}
                                {{ isset($action['disabled']) && $action['disabled'] ? 'disabled' : '' }}>
                            @if(isset($action['icon'])) <i class="{{ $action['icon'] }}"></i> @endif
                            {{ $action['label'] }}
                        </button>
                    </form>
                @elseif(isset($action['url']))
                    <a href="{{ $action['url'] }}" 
                       class="action-btn {{ $action['class'] ?? 'btn-primary' }} w-100 d-flex"
                       {{ isset($action['target']) ? 'target="'.$action['target'].'"' : '' }}>
                        @if(isset($action['icon'])) <i class="{{ $action['icon'] }}"></i> @endif
                        {{ $action['label'] }}
                    </a>
                @endif
            @endif
        @endforeach
        
        @if($statusLabel)
            <div class="alert alert-{{ $statusLabel['type'] ?? 'info' }} small mt-3 mb-0">
                <i class="{{ $statusLabel['icon'] ?? 'fas fa-info-circle' }} me-2"></i>
                {{ $statusLabel['message'] }}
            </div>
        @endif
    </div>
</div>

<style>
.action-card {
    border: none;
    box-shadow: var(--shadow-sm);
}
.action-card .card-header-custom {
    background: var(--color-gray-50);
    border-bottom: 1px solid var(--color-gray-200);
}
.action-btn {
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    font-weight: var(--font-weight-semibold);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    border: none;
    width: 100%;
    margin-bottom: var(--space-2);
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
    font-size: 1.1rem;
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
.btn-collect {
    background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-600) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}
.btn-deliver {
    background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success-dark) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.btn-call {
    background: linear-gradient(135deg, var(--color-info) 0%, var(--color-info-dark) 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
.btn-whatsapp {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    color: var(--color-white);
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
}
.action-earnings {
    animation: fadeIn 0.5s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>