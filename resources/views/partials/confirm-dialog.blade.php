<div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-200" aria-hidden="true">
    {{-- Overlay --}}
    <div id="confirm-overlay" class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs transition-opacity"></div>

    {{-- Modal Card --}}
    <div class="relative w-full max-w-sm bg-white border border-zinc-200/90 rounded-xl shadow-xl shadow-zinc-900/10 p-5 transform scale-95 transition-all duration-200 z-10">
        <div class="flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-lg bg-zinc-100 border border-zinc-200/80 flex items-center justify-center shrink-0 text-zinc-600">
                <svg class="w-4 h-4 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 id="confirm-title" class="text-xs font-semibold text-zinc-900 tracking-tight">Delete this item?</h3>
                <p id="confirm-message" class="text-xs text-zinc-500 mt-1 leading-relaxed">This action cannot be undone.</p>
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end gap-2 pt-3 border-t border-zinc-100">
            <button type="button" id="confirm-cancel-btn" class="px-3 py-1.5 text-xs font-medium text-zinc-600 hover:text-zinc-900 rounded-md hover:bg-zinc-100 transition-colors select-none">
                Cancel
            </button>
            <button type="button" id="confirm-submit-btn" class="px-3 py-1.5 text-xs font-medium text-white bg-zinc-900 hover:bg-zinc-800 rounded-md transition-colors shadow-2xs select-none">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    const modal = document.getElementById('confirm-modal');
    const overlay = document.getElementById('confirm-overlay');
    const titleEl = document.getElementById('confirm-title');
    const messageEl = document.getElementById('confirm-message');
    const cancelBtn = document.getElementById('confirm-cancel-btn');
    const submitBtn = document.getElementById('confirm-submit-btn');

    let activeForm = null;

    window.showConfirmDialog = function({ title, message, form, confirmText = 'Delete' }) {
        activeForm = form;
        titleEl.textContent = title || 'Delete this item?';
        messageEl.textContent = message || 'This action cannot be undone.';
        submitBtn.textContent = confirmText;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        modal.setAttribute('aria-hidden', 'false');

        const card = modal.querySelector('.relative');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    };

    function hideConfirmDialog() {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.setAttribute('aria-hidden', 'true');

        const card = modal.querySelector('.relative');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');

        activeForm = null;
    }

    if (overlay) overlay.addEventListener('click', hideConfirmDialog);
    if (cancelBtn) cancelBtn.addEventListener('click', hideConfirmDialog);

    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            if (activeForm) {
                const formToSubmit = activeForm;
                hideConfirmDialog();
                formToSubmit.submit();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
            hideConfirmDialog();
        }
    });

    // Intercept delete forms with data-confirm-title or data-confirm-message
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.hasAttribute('data-confirm-title') || form.hasAttribute('data-confirm-message')) {
            e.preventDefault();
            window.showConfirmDialog({
                title: form.getAttribute('data-confirm-title') || 'Delete item?',
                message: form.getAttribute('data-confirm-message') || 'This action cannot be undone.',
                form: form,
                confirmText: form.getAttribute('data-confirm-btn') || 'Delete',
            });
        }
    });
})();
</script>
