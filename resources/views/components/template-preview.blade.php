@props(['type'])

{{-- Tiny fake "screenshots" of the three templates. "theme-fixed" = dark mode leaves it alone. --}}
@if ($type === 'simple')
    <div class="theme-fixed flex h-full flex-col items-center gap-2 bg-white p-4">
        <div class="h-8 w-8 rounded-full bg-slate-300"></div>
        <div class="h-2 w-24 rounded bg-slate-800"></div>
        <div class="my-1 h-px w-full bg-slate-200"></div>
        <div class="h-1.5 w-full rounded bg-slate-200"></div>
        <div class="h-1.5 w-5/6 rounded bg-slate-200"></div>
        <div class="my-1 h-px w-full bg-slate-200"></div>
        <div class="h-1.5 w-full rounded bg-slate-200"></div>
        <div class="h-1.5 w-2/3 rounded bg-slate-200"></div>
    </div>
@elseif ($type === 'modern')
    <div class="theme-fixed h-full space-y-2 bg-slate-100 p-3">
        <div class="flex items-center gap-2 rounded-xl bg-indigo-600 p-3">
            <div class="h-8 w-8 rounded-full bg-white/70"></div>
            <div class="space-y-1">
                <div class="h-2 w-20 rounded bg-white"></div>
                <div class="h-1.5 w-12 rounded bg-white/60"></div>
            </div>
        </div>
        <div class="flex gap-1">
            <div class="h-3 w-10 rounded-full bg-indigo-200"></div>
            <div class="h-3 w-8 rounded-full bg-indigo-200"></div>
            <div class="h-3 w-12 rounded-full bg-indigo-200"></div>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="h-12 rounded-lg bg-white shadow"></div>
            <div class="h-12 rounded-lg bg-white shadow"></div>
        </div>
    </div>
@else
    <div class="theme-fixed flex h-full gap-3 bg-slate-900 p-3">
        <div class="w-1/3 space-y-2">
            <div class="h-10 w-10 rotate-6 rounded-lg bg-amber-400"></div>
            <div class="h-2.5 w-full rounded bg-fuchsia-400"></div>
            <div class="h-1.5 w-2/3 rounded bg-slate-600"></div>
        </div>
        <div class="relative flex-1 border-l-2 border-fuchsia-500/50 pl-3">
            <div class="absolute -left-[5px] top-1 h-2 w-2 rounded-full bg-amber-400"></div>
            <div class="absolute -left-[5px] top-8 h-2 w-2 rounded-full bg-amber-400"></div>
            <div class="absolute -left-[5px] top-[60px] h-2 w-2 rounded-full bg-amber-400"></div>
            <div class="h-2 w-3/4 rounded bg-slate-300"></div>
            <div class="mt-1 h-1.5 w-full rounded bg-slate-600"></div>
            <div class="mt-5 h-2 w-2/3 rounded bg-slate-300"></div>
            <div class="mt-1 h-1.5 w-full rounded bg-slate-600"></div>
            <div class="mt-5 h-2 w-1/2 rounded bg-slate-300"></div>
        </div>
    </div>
@endif