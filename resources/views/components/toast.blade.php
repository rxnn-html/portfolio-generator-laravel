@props(['notice' => null])

@php
    $messages = [
        'saved'    => 'Changes saved.',
        'ready'    => 'Your portfolio is ready!',
        'template' => 'Template updated.',
        'deleted'  => 'Portfolio deleted.',
    ];
    $message = $messages[$notice] ?? null; // unknown or missing = no toast
@endphp

@if ($message)
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 4000); history.replaceState(null, '', location.pathname)"
         x-show="show" x-transition role="status" aria-live="polite"
         class="fixed inset-x-4 bottom-24 z-50 mx-auto flex max-w-sm items-center gap-3 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-xl print:hidden">
        <span aria-hidden="true">&#10003;</span> {{ $message }}
    </div>
@endif