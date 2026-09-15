@php
    $type = $type ?? 'info';
    $title = $title ?? null;
    $message = $message ?? '';

    $borderColor = match($type) {
        'error', 'danger' => 'border-l-rose-500',
        'warning' => 'border-l-amber-500',
        'success' => 'border-l-emerald-500',
        default => 'border-l-zinc-400',
    };

    $iconColor = match($type) {
        'error', 'danger' => 'text-rose-500',
        'warning' => 'text-amber-500',
        'success' => 'text-emerald-500',
        default => 'text-zinc-500',
    };
@endphp

<div class="flex items-start gap-3 p-3 bg-white border border-zinc-200/90 rounded-md border-l-[3px] {{ $borderColor }} text-xs shadow-2xs my-2">
    @if ($type === 'warning')
        <svg class="w-4 h-4 {{ $iconColor }} shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
    @elseif ($type === 'error' || $type === 'danger')
        <svg class="w-4 h-4 {{ $iconColor }} shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>
    @elseif ($type === 'success')
        <svg class="w-4 h-4 {{ $iconColor }} shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    @else
        <svg class="w-4 h-4 {{ $iconColor }} shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
    @endif

    <div class="flex-1 min-w-0">
        @if (!empty($title))
            <div class="font-medium text-zinc-900 tracking-tight mb-0.5">{{ $title }}</div>
        @endif
        @if (!empty($message))
            <div class="text-[11px] text-zinc-600 leading-relaxed">{{ $message }}</div>
        @endif
    </div>
</div>
