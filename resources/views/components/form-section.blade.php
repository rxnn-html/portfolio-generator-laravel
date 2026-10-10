@props(['number', 'title', 'description' => null, 'section'])

<section id="{{ $section }}" data-section class="relative scroll-mt-32 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
    {{-- green edge appears when the section is complete --}}
    <span aria-hidden="true" class="absolute inset-y-6 left-0 w-1 rounded-r bg-emerald-500 transition-opacity duration-500"
          :class="done.{{ $section }} ? 'opacity-100' : 'opacity-0'"></span>

    <header class="flex items-start gap-4">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl font-mono text-sm font-bold transition-colors duration-500"
              :class="done.{{ $section }} ? 'bg-emerald-500 text-white' : 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-300'">
            <span x-show="!done.{{ $section }}">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
            <span x-show="done.{{ $section }}">&#10003;</span>
        </span>
        <div class="min-w-0 flex-1">
            <h2 class="text-lg font-bold text-gray-900">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
        <span x-show="done.{{ $section }}" class="hidden rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-600 dark:text-emerald-300 sm:inline">Complete</span>
    </header>

    <div class="mt-6 space-y-5">
        {{ $slot }}
    </div>
</section>