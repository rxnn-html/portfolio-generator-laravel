@php
    $links = [
        ['label' => 'Home',          'url' => url('/'),       'active' => request()->is('/')],
        ['label' => 'Create',        'url' => url('/create'), 'active' => request()->is('create')],
        ['label' => 'My Portfolios', 'url' => url('/manage'), 'active' => request()->is('manage')],
    ];
@endphp

<header class="border-b border-slate-200 bg-white/80 backdrop-blur dark:border-white/10 dark:bg-slate-950/70 print:hidden">
    <nav class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-4 py-3">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-bold text-slate-900 dark:text-white">
            <img src="{{ asset('favicon.svg') }}" alt="" class="h-8 w-8">
            Portfolio Generator
        </a>

        <div class="flex items-center gap-1 sm:gap-2">
            <ul class="flex items-center gap-1">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['url'] }}"
                           class="rounded-md px-3 py-2 text-sm font-medium transition {{ $link['active']
                               ? 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300'
                               : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white' }}">
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Dark / light toggle --}}
            <button type="button" aria-label="Toggle dark mode" title="Toggle dark mode"
                    onclick="(function(){var d=document.documentElement.classList.toggle('dark');try{localStorage.setItem('theme',d?'dark':'light')}catch(e){}})()"
                    class="flex h-9 w-9 items-center justify-center rounded-md text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white">
                <svg class="block dark:hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                <svg class="hidden dark:block" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>
        </div>
    </nav>
</header>