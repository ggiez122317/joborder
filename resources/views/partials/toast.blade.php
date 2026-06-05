@php
    $toastMessage = session('status');
    $toastType = session('toast_type', 'success');

    if (!$toastMessage && $errors->any()) {
        $toastMessage = $errors->first();
        $toastType = 'error';
    }

    $toastClasses = match ($toastType) {
        'error' => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        default => 'border-emerald-200 bg-emerald-50 text-emerald-800',
    };
@endphp

@if ($toastMessage)
    <div class="toast-stack pointer-events-none fixed right-4 top-4 z-[120] w-full max-w-sm" data-toast-stack>
        <div class="pointer-events-auto rounded-[14px] border px-4 py-3 shadow-[0_18px_40px_rgba(15,23,42,0.14)] {{ $toastClasses }}" data-toast>
            <div class="flex items-start gap-3">
                <div class="mt-0.5 h-2.5 w-2.5 shrink-0 rounded-full bg-current opacity-70"></div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-semibold">{{ $toastMessage }}</div>
                </div>
                <button type="button" class="toast-close rounded-[8px] px-2 py-1 text-xs font-semibold opacity-70 transition hover:opacity-100" data-toast-close>
                    Close
                </button>
            </div>
        </div>
    </div>
@endif
