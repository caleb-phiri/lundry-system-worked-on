<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080; max-width: 380px;" aria-live="polite" aria-atomic="true"></div>

<script>
class ToastManager {
    constructor() {
        this.container = document.getElementById('toastContainer');
        this.toasts = new Map();
    }
    
    show(message, options = {}) {
        const id = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const type = options.type || 'info';
        const title = options.title;
        const duration = options.duration || 5000;
        const persistent = options.persistent || false;
        const action = options.action;
        
        const typeConfig = {
            success: { icon: 'check-circle', bg: 'bg-success', text: 'text-white' },
            error: { icon: 'times-circle', bg: 'bg-danger', text: 'text-white' },
            warning: { icon: 'exclamation-triangle', bg: 'bg-warning', text: 'text-dark' },
            info: { icon: 'info-circle', bg: 'bg-primary', text: 'text-white' }
        };
        
        const config = typeConfig[type] || typeConfig.info;
        
        const toastHtml = `
            <div id="${id}" class="toast ${config.bg} ${config.text} driver-toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="${duration}">
                <div class="toast-header ${config.bg} ${config.text} border-0">
                    ${title ? `<strong class="me-auto">${title}</strong>` : ''}
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-${config.icon} me-2"></i>
                    <span class="flex-grow-1">${message}</span>
                    ${action ? `<button type="button" class="btn btn-sm btn-light ms-2" onclick="${action.callback.toString().replace(/[\r\n]/g, ' ')}">${action.label}</button>` : ''}
                </div>
            </div>
        `;
        
        this.container.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastEl = document.getElementById(id);
        const toast = new bootstrap.Toast(toastEl, { 
            autohide: !persistent, 
            delay: duration 
        });
        
        this.toasts.set(id, { toast, element: toastEl });
        
        toast.show();
        
        toastEl.addEventListener('hidden.bs.toast', () => {
            this.toasts.delete(id);
            toastEl.remove();
        });
        
        return id;
    }
    
    success(message, options = {}) {
        return this.show(message, { ...options, type: 'success' });
    }
    
    error(message, options = {}) {
        return this.show(message, { ...options, type: 'error' });
    }
    
    warning(message, options = {}) {
        return this.show(message, { ...options, type: 'warning' });
    }
    
    info(message, options = {}) {
        return this.show(message, { ...options, type: 'info' });
    }
    
    dismiss(id) {
        if (this.toasts.has(id)) {
            this.toasts.get(id).toast.hide();
        }
    }
    
    dismissAll() {
        this.toasts.forEach(({ toast }) => toast.hide());
    }
}

// Global instance
window.toastManager = new ToastManager();

// Handle Laravel session flashes
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        window.toastManager.success('{{ session('success') }}', { title: 'Success' });
    @endif
    
    @if(session('error'))
        window.toastManager.error('{{ session('error') }}', { title: 'Error' });
    @endif
    
    @if(session('warning'))
        window.toastManager.warning('{{ session('warning') }}', { title: 'Warning' });
    @endif
    
    @if(session('info'))
        window.toastManager.info('{{ session('info') }}', { title: 'Info' });
    @endif
});
</script>