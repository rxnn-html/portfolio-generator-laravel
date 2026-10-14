@php
    $links = [
        ['label' => 'Home',          'url' => url('/'),       'active' => request()->is('/'),                   'icon' => 'home'],
        ['label' => 'Create',        'url' => url('/create'), 'active' => request()->is('create', 'templates'), 'icon' => 'plus'],
        ['label' => 'My Portfolios', 'url' => url('/manage'), 'active' => request()->is('manage', 'portfolio/*'), 'icon' => 'folder'],
    ];
    // Icon drawings (fixed strings written here, never user input)
    $icons = [
        'home'   => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v10h5v-6h4v6h5V10"/>',
        'plus'   => '<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/>',
        'folder' => '<path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>',
    ];
    $on  = 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300';
    $off = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white';
    $toggle = "(function(){var d=document.documentElement.classList.toggle('dark');try{localStorage.setItem('theme',d?'dark':'light')}catch(e){}})()";
@endphp

{{-- TOP BAR: logo and dark-mode toggle (text links only on tablet and desktop) --}}
<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/75 backdrop-blur-lg dark:border-white/10 dark:bg-slate-950/70 print:hidden">
    <nav class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3" aria-label="Main">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-bold text-slate-900 dark:text-white">
            <img src="{{ asset('favicon.svg') }}?v=2" alt="" class="h-8 w-8">
            <span class="hidden min-[380px]:inline">Portfolio Generator</span>
        </a>

        <div class="flex items-center gap-1">
            <ul class="hidden items-center gap-1 sm:flex">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['url'] }}" @if ($link['active']) aria-current="page" @endif
                           class="rounded-md px-3 py-2 text-sm font-medium transition {{ $link['active'] ? $on : $off }}">{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>

            <button type="button" aria-label="Toggle dark mode" title="Toggle dark mode" onclick="{{ $toggle }}"
                    class="flex h-10 w-10 items-center justify-center rounded-md text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white">
                <svg class="block dark:hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                <svg class="hidden dark:block" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>
        </div>
    </nav>
</header>

{{-- BOTTOM BAR: three icons, phones only --}}
<nav aria-label="Bottom navigation"
     class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/90 backdrop-blur-lg dark:border-white/10 dark:bg-slate-950/90 sm:hidden print:hidden"
     style="padding-bottom: env(safe-area-inset-bottom)">
    <ul class="mx-auto grid max-w-md grid-cols-3">
        @foreach ($links as $link)
            <li>
                <a href="{{ $link['url'] }}" aria-label="{{ $link['label'] }}" title="{{ $link['label'] }}"
                   @if ($link['active']) aria-current="page" @endif
                   class="flex h-16 items-center justify-center">
                    <span class="flex h-11 w-16 items-center justify-center rounded-full transition
                                 {{ $link['active'] ? 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-300' : 'text-slate-500 dark:text-slate-400' }}">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icons[$link['icon']] !!}</svg>
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>