$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) {
    $full = Join-Path (Get-Location) $p
    $dir = Split-Path $full -Parent
    if (-not (Test-Path $dir)) { New-Item -ItemType Directory -Force $dir | Out-Null }
    [System.IO.File]::WriteAllText($full, $t.Replace("`r`n", "`n"), $utf8)
}
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

# ---- CSS: animations, reveal, focus, reduced motion
$css = ReadF "resources/css/app.css"
if ($css -notmatch 'UI UPGRADE') {
    $add = @'


/* ---------- UI UPGRADE ---------- */
@theme {
  --animate-drift: drift 14s ease-in-out infinite alternate;
  --animate-drift-slow: drift 22s ease-in-out infinite alternate-reverse;
  --animate-dash: dash 14s linear infinite;

  @keyframes drift {
    from { transform: translate3d(-4%, -3%, 0) scale(1); }
    to   { transform: translate3d(5%, 4%, 0) scale(1.15); }
  }
  @keyframes dash {
    to { stroke-dashoffset: -400; }
  }
}

html { scroll-behavior: smooth; }

:focus-visible {
  outline: 2px solid #6366f1;
  outline-offset: 2px;
}

/* Sections start slightly lower and transparent, then glide in (class added by JavaScript only) */
.reveal-init { opacity: 0; transform: translateY(24px); transition: opacity .7s ease, transform .7s ease; }
.reveal-in   { opacity: 1; transform: none; }

@media (prefers-reduced-motion: reduce) {
  html { scroll-behavior: auto; }
  *, *::before, *::after {
    animation-duration: .01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: .01ms !important;
  }
}
'@
    SaveF "resources/css/app.css" ($css + $add)
    Write-Host "CSS upgraded"
} else { Write-Host "CSS already upgraded" }

# ---- JavaScript: scroll reveal (home page only)
$js = ReadF "resources/js/app.js"
if ($js -notmatch 'reveal-init') {
    $addJs = @'


// Reveal sections as they scroll into view (only inside [data-reveal-group], i.e. the Home page)
document.addEventListener('DOMContentLoaded', () => {
    const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (calm || !('IntersectionObserver' in window)) return;

    const items = document.querySelectorAll('[data-reveal-group] > section:not(:first-child)');
    const watcher = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-in');
                watcher.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    items.forEach((el) => {
        el.classList.add('reveal-init');
        watcher.observe(el);
    });
});
'@
    SaveF "resources/js/app.js" ($js + $addJs)
    Write-Host "JavaScript upgraded"
} else { Write-Host "JavaScript already upgraded" }

# ---- Navbar: sticky glass bar + mobile menu (no JavaScript needed for the menu)
SaveF "resources/views/partials/navbar.blade.php" @'
@php
    $links = [
        ['label' => 'Home',          'url' => url('/'),       'active' => request()->is('/')],
        ['label' => 'Create',        'url' => url('/create'), 'active' => request()->is('create')],
        ['label' => 'My Portfolios', 'url' => url('/manage'), 'active' => request()->is('manage')],
    ];
    $on  = 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300';
    $off = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white';
    $toggle = "(function(){var d=document.documentElement.classList.toggle('dark');try{localStorage.setItem('theme',d?'dark':'light')}catch(e){}})()";
@endphp

<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/75 backdrop-blur-lg dark:border-white/10 dark:bg-slate-950/70 print:hidden">
    <nav class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3" aria-label="Main">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-bold text-slate-900 dark:text-white">
            <img src="{{ asset('favicon.svg') }}?v=2" alt="" class="h-8 w-8">
            <span class="hidden min-[380px]:inline">Portfolio Generator</span>
        </a>

        <div class="flex items-center gap-1">
            {{-- Desktop links --}}
            <ul class="hidden items-center gap-1 sm:flex">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['url'] }}" @if ($link['active']) aria-current="page" @endif
                           class="rounded-md px-3 py-2 text-sm font-medium transition {{ $link['active'] ? $on : $off }}">{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>

            {{-- Dark / light toggle --}}
            <button type="button" aria-label="Toggle dark mode" title="Toggle dark mode" onclick="{{ $toggle }}"
                    class="flex h-10 w-10 items-center justify-center rounded-md text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white">
                <svg class="block dark:hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                <svg class="hidden dark:block" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>

            {{-- Mobile menu: a native <details>, so it works without JavaScript --}}
            <details class="group relative sm:hidden">
                <summary aria-label="Open menu"
                         class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-md text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/10 [&::-webkit-details-marker]:hidden">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path class="group-open:hidden" d="M4 7h16M4 12h16M4 17h16"/>
                        <path class="hidden group-open:block" d="M6 6l12 12M18 6 6 18"/>
                    </svg>
                </summary>
                <ul class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl dark:border-white/10 dark:bg-slate-900">
                    @foreach ($links as $link)
                        <li>
                            <a href="{{ $link['url'] }}"
                               class="block rounded-xl px-4 py-3 text-sm font-semibold {{ $link['active'] ? $on : $off }}">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </details>
        </div>
    </nav>
</header>
'@
Write-Host "navbar written"

# ---- Footer
$lp = "resources/views/layouts/app.blade.php"
$l = ReadF $lp
$footerRx = [regex]::new('(?s)<footer.*?</footer>')
if ($footerRx.IsMatch($l)) {
    $newFooter = @'
<footer class="mx-auto mt-8 max-w-6xl border-t border-slate-200 px-4 py-10 text-sm text-slate-500 dark:border-white/10 dark:text-slate-400 print:hidden">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <p class="flex items-center gap-2 font-semibold text-slate-700 dark:text-slate-200">
                <img src="{{ asset('favicon.svg') }}?v=2" alt="" class="h-6 w-6"> Portfolio Generator
            </p>
            <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2" aria-label="Footer">
                <a href="{{ url('/') }}" class="hover:text-indigo-600 dark:hover:text-indigo-300">Home</a>
                <a href="{{ url('/create') }}" class="hover:text-indigo-600 dark:hover:text-indigo-300">Create</a>
                <a href="{{ url('/manage') }}" class="hover:text-indigo-600 dark:hover:text-indigo-300">My Portfolios</a>
                <a href="#top" class="hover:text-indigo-600 dark:hover:text-indigo-300">Back to top &uarr;</a>
            </nav>
        </div>
        <p class="mt-6 text-center">Built with Laravel, Blade, Tailwind CSS &amp; Supabase &middot; No copyright &middot; Free to use and share</p>
    </footer>
'@
    $l = $footerRx.Replace($l, { param($m) $newFooter }, 1)
    if ($l -notmatch 'id="top"') { $l = $l.Replace('<body class="', '<body id="top" class="') }
    SaveF $lp $l
    Write-Host "footer upgraded"
} else { Write-Host "MANUAL: footer not found in layouts/app.blade.php" -ForegroundColor Yellow }

Write-Host ""
Write-Host "Done with 1!"