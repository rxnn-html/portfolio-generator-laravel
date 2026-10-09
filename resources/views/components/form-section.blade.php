@props(['number', 'title', 'description' => null])

<section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
    <header class="flex items-start gap-4">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-500/10 font-mono text-sm font-bold text-indigo-600 dark:text-indigo-300">
            {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
        </span>
        <div class="min-w-0">
            <h2 class="text-lg font-bold text-gray-900">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
    </header>
    <div class="mt-6 space-y-5">
        {{ $slot }}
    </div>
</section>