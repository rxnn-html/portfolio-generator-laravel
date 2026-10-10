$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) { [System.IO.File]::WriteAllText((Join-Path (Get-Location) $p), $t.Replace("`r`n", "`n"), $utf8) }
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

$newHero = @'
{{-- ================= HERO ================= --}}
    <section class="relative isolate overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-16 text-white sm:px-12 sm:py-24">
        {{-- Glow blobs and wave lines (decoration only) --}}
        <div aria-hidden="true" class="pointer-events-none absolute -left-24 -top-24 -z-10 h-96 w-96 rounded-full bg-indigo-600/40 blur-[110px] motion-safe:animate-drift"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-32 right-0 -z-10 h-96 w-96 rounded-full bg-fuchsia-600/30 blur-[110px] motion-safe:animate-drift-slow"></div>
        <div aria-hidden="true" class="pointer-events-none absolute left-1/3 top-1/4 -z-10 h-64 w-64 rounded-full bg-cyan-500/20 blur-[100px] motion-safe:animate-drift-slow"></div>

        <svg aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10 h-full w-full opacity-60" viewBox="0 0 1200 600" preserveAspectRatio="none" fill="none">
            <defs>
                <linearGradient id="waves" x1="0" y1="0" x2="1" y2="0">
                    <stop offset="0" stop-color="#22d3ee"/>
                    <stop offset="0.5" stop-color="#6366f1"/>
                    <stop offset="1" stop-color="#d946ef"/>
                </linearGradient>
            </defs>
            @foreach (range(0, 7) as $i)
                <path d="M-50 {{ 380 + $i * 18 }} C 200 {{ 250 + $i * 12 }}, 450 {{ 520 - $i * 10 }}, 700 {{ 380 + $i * 8 }} S 1050 {{ 260 + $i * 14 }}, 1250 {{ 340 + $i * 10 }}"
                      stroke="url(#waves)" stroke-opacity="{{ number_format(0.6 - $i * 0.05, 2) }}" stroke-width="1"
                      stroke-dasharray="6 14" class="motion-safe:animate-dash"/>
            @endforeach
        </svg>

        <div class="relative grid items-center gap-14 lg:grid-cols-3 lg:gap-10">
            <div class="lg:col-span-2">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-xs font-semibold text-cyan-200 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Free portfolio builder
                </p>

                <h1 class="mt-6 text-4xl font-semibold leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl">
                    Turn your story into a
                    <span class="block bg-linear-to-r from-cyan-300 via-sky-400 to-violet-400 bg-clip-text pb-2 text-transparent">stunning portfolio</span>
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-300">
                    Enter your details once, pick one of three professionally designed templates, and preview your finished portfolio in seconds.
                    Made for
                    <span x-data="{ words: ['developers','designers','students','freelancers','creators'], i: 0 }"
                          x-init="setInterval(() => i = (i + 1) % words.length, 2200)"
                          x-text="words[i]" class="font-semibold text-white">developers</span>.
                </p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ url('/create') }}"
                       class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-linear-to-r from-indigo-600 to-violet-600 px-7 text-base font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:-translate-y-0.5 hover:shadow-indigo-500/50">
                        Create Portfolio &rarr;
                    </a>
                    <a href="#how-it-works"
                       class="inline-flex min-h-12 items-center justify-center rounded-xl bg-white px-7 text-base font-semibold text-slate-900 transition hover:-translate-y-0.5 hover:bg-slate-100">
                        See how it works
                    </a>
                </div>

                <dl class="mt-12 grid max-w-md grid-cols-3 gap-4 border-t border-white/10 pt-6">
                    <div><dt class="text-xs uppercase tracking-wider text-slate-400">Templates</dt><dd class="mt-1 text-2xl font-bold">3</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-400">Setup</dt><dd class="mt-1 text-2xl font-bold">~5 min</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-400">Export</dt><dd class="mt-1 text-2xl font-bold">PDF</dd></div>
                </dl>
            </div>

            {{-- A fan of the three templates (right third) --}}
            <div class="relative mx-auto h-60 w-full max-w-xs sm:h-64 sm:max-w-sm lg:max-w-none" aria-hidden="true">
                <div class="absolute left-0 top-0 h-28 w-40 -rotate-6 overflow-hidden rounded-2xl border border-white/15 shadow-2xl sm:h-32 sm:w-48 lg:w-44">
                    <x-template-preview type="creative" />
                </div>
                <div class="absolute right-0 top-8 h-28 w-40 rotate-6 overflow-hidden rounded-2xl border border-white/15 shadow-2xl sm:h-32 sm:w-48 lg:w-44">
                    <x-template-preview type="modern" />
                </div>
                <div class="absolute left-1/2 top-24 -translate-x-1/2 sm:top-28">
                    <div class="motion-safe:animate-float">
                        <div class="h-28 w-40 overflow-hidden rounded-2xl border border-white/20 shadow-2xl sm:h-32 sm:w-48 lg:w-44">
                            <x-template-preview type="simple" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
'@

$hp = "resources/views/home.blade.php"
$h = ReadF $hp
$heroRx = [regex]::new('(?s)\{\{-- =+ HERO =+ --\}\}.*?(?=\{\{-- =+ HOW IT WORKS =+ --\}\})')

if ($h -match 'animate-drift') {
    Write-Host "hero already upgraded"
} elseif ($heroRx.IsMatch($h)) {
    $h = $heroRx.Replace($h, { param($m) $newHero }, 1)
    $h = $h.Replace('<div class="space-y-20 sm:space-y-28">', '<div class="space-y-20 sm:space-y-28" data-reveal-group>')
    SaveF $hp $h
    Write-Host "Home hero upgraded"
} else {
    Write-Host "MANUAL: could not find the HERO section in home.blade.php (it was edited). Send me the file and I will adapt it." -ForegroundColor Yellow
}

php artisan view:clear
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "The build failed. Send me the first red line." -ForegroundColor Red; return }

$tracked = git ls-files
if ($tracked -contains '.env') { Write-Host "STOP: .env is tracked by Git. Run: git rm --cached .env  then run this script again." -ForegroundColor Red; return }
git add .
git commit -m "UI upgrade: aurora hero, glass navbar, mobile menu, scroll reveals"
git push

Write-Host ""
Write-Host "ALL DONE."