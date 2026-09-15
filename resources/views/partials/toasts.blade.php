<div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0"></div>

<script>
(function() {
    const container = document.getElementById('toast-container');

    window.showToast = function({ type = 'success', title = '', message = '', duration = 4000 }) {
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto flex items-start gap-3 p-3.5 bg-white border border-zinc-200/90 rounded-md shadow-lg shadow-zinc-900/5 text-xs transition-all duration-300 transform translate-y-3 opacity-0 ${
            type === 'error' ? 'border-l-[3px] border-l-rose-500' : 'border-l-[3px] border-l-emerald-500'
        }`;

        const iconSvg = type === 'error'
            ? `<svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
               </svg>`
            : `<svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>`;

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        const safeTitle = escapeHtml(title);
        const safeMessage = escapeHtml(message);

        toast.innerHTML = `
            ${iconSvg}
            <div class="flex-1 min-w-0 pr-1">
                <div class="font-medium text-zinc-900 tracking-tight">${safeTitle}</div>
                ${safeMessage ? `<div class="text-[11px] text-zinc-500 mt-0.5 leading-relaxed">${safeMessage}</div>` : ''}
            </div>
            <button type="button" class="toast-close text-zinc-400 hover:text-zinc-600 p-0.5 rounded transition-colors text-sm leading-none select-none">
                &times;
            </button>
        `;

        container.appendChild(toast);

        // Animate in on next frame
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-3', 'opacity-0');
        });

        // Auto dismiss logic
        let timer;
        const dismiss = () => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        };

        if (duration > 0) {
            timer = setTimeout(dismiss, duration);
        }

        toast.querySelector('.toast-close').addEventListener('click', () => {
            if (timer) clearTimeout(timer);
            dismiss();
        });
    };

    // Auto-trigger on page load from Blade session/error values
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('status'))
            window.showToast({
                type: 'success',
                title: @json(session('status')),
            });
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                window.showToast({
                    type: 'error',
                    title: 'Validation Error',
                    message: @json($error),
                });
            @endforeach
        @endif
    });
})();
</script>
