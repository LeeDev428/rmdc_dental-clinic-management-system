<!-- Toast Notification Component -->
<div id="toast-notification" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-4 flex items-center gap-3 max-w-md border border-gray-200">
        <div id="toast-notification-icon" class="flex-shrink-0"></div>
        <div class="flex-1">
            <p id="toast-notification-message" class="text-sm font-medium text-gray-900"></p>
        </div>
        <button onclick="window.closeToast()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<style>
    @keyframes toast-slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes toast-slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .toast-animate-in {
        animation: toast-slide-in 0.3s ease-out forwards;
    }
    
    .toast-animate-out {
        animation: toast-slide-out 0.3s ease-out forwards;
    }
</style>

<script>
    // Global Toast Notification Functions
    window.showToast = function(message, type = 'success', duration = 8000) {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-notification-message');
        const toastIcon = document.getElementById('toast-notification-icon');
        
        if (!toast || !toastMessage || !toastIcon) {
            console.error('Toast notification elements not found');
            return;
        }
        
        toastMessage.textContent = message;
        
        // Set icon based on type
        if (type === 'success') {
            toastIcon.innerHTML = '<svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else if (type === 'error') {
            toastIcon.innerHTML = '<svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else if (type === 'warning') {
            toastIcon.innerHTML = '<svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
        } else if (type === 'info') {
            toastIcon.innerHTML = '<svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        }
        
        // Show toast
        toast.classList.remove('hidden', 'toast-animate-out');
        toast.classList.add('toast-animate-in');
        
        // Auto close after duration
        if (window.toastTimeout) {
            clearTimeout(window.toastTimeout);
        }
        
        window.toastTimeout = setTimeout(() => {
            window.closeToast();
        }, duration);
    };
    
    window.closeToast = function() {
        const toast = document.getElementById('toast-notification');
        
        if (!toast) return;
        
        toast.classList.remove('toast-animate-in');
        toast.classList.add('toast-animate-out');
        
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('toast-animate-out');
        }, 300);
        
        if (window.toastTimeout) {
            clearTimeout(window.toastTimeout);
        }
    };
    
    // Expose legacy function names for backward compatibility
    window.showToastNotification = window.showToast;
</script>
