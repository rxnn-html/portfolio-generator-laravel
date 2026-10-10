$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) { [System.IO.File]::WriteAllText((Join-Path (Get-Location) $p), $t.Replace("`r`n", "`n"), $utf8) }
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

$hp = "resources/views/home.blade.php"
$h = ReadF $hp

# ============================================================ 1. the typewriter word
$word = @'
<span x-data="{ words: ['developers','designers','students','freelancers','creators'], i: 0, text: '', erasing: false,
                tick() {
                    const w = this.words[this.i];
                    if (!this.erasing) {
                        this.text = w.slice(0, this.text.length + 1);
                        if (this.text === w) { this.erasing = true; return setTimeout(() => this.tick(), 1600); }
                    } else {
                        this.text = w.slice(0, this.text.length - 1);
                        if (this.text === '') { this.erasing = false; this.i = (this.i + 1) % this.words.length; }
                    }
                    setTimeout(() => this.tick(), this.erasing ? 40 : 85);
                } }"
          x-init="matchMedia('(prefers-reduced-motion: reduce)').matches ? text = words[0] : tick()"
          class="whitespace-nowrap">
        <span class="sr-only">developers, designers, students, freelancers and creators</span>
        <span aria-hidden="true" class="inline-flex min-w-[10ch] items-center rounded-lg bg-linear-to-r from-cyan-400/20 to-fuchsia-500/20 px-2.5 py-0.5 align-baseline font-bold ring-1 ring-white/20">
            <span class="bg-linear-to-r from-cyan-300 via-sky-300 to-fuchsia-300 bg-clip-text text-transparent" x-text="text"></span>
            <span class="ml-0.5 inline-block h-[1.1em] w-0.5 animate-pulse bg-cyan-300"></span>
        </span>
    </span>
'@
$wordRx = [regex]::new('(?s)<span x-data="\{ words: \[''developers''.*?</span>')
if ($h -match 'erasing: false') {
    Write-Host "word: already upgraded"
} elseif ($wordRx.IsMatch($h)) {
    $h = $wordRx.Replace($h, { param($m) $word }, 1)
    Write-Host "word: upgraded"
} else {
    Write-Host "MANUAL: could not find the rotating word in home.blade.php" -ForegroundColor Yellow
}

# ============================================================ 2. the template stage
$stage = @'
{{-- ================= TEMPLATES ================= --}}
    @php
        $options = [
            'simple'   => ['name' => 'Simple',   'text' => 'Clean, minimal, and print-friendly. A classic resume layout.', 'tags' => ['Clean', 'Print-ready', 'Classic']],
            'modern'   => ['name' => 'Modern',   'text' => 'Rounded cards, skill badges, and a sticky navigation bar.',      'tags' => ['Cards', 'Badges', 'Sticky nav']],
            'creative' => ['name' => 'Creative', 'text' => 'A bold dark design with a timeline and oversized type.',        'tags' => ['Dark', 'Timeline', 'Bold type']],
        ];
    @endphp

    <section class="theme-fixed relative isolate overflow-hidden rounded-[2rem] bg-slate-950 px-5 py-14 text-white sm:px-10 sm:py-20"
             x-data="{
                active: 'simple', auto: true, tx: 0, ty: 0,
                order: ['simple', 'modern', 'creative'],
                names: { simple: 'Simple', modern: 'Modern', creative: 'Creative' },
                pick(key) { this.active = key; this.auto = false; },
                tilt(e) {
                    const r = e.currentTarget.getBoundingClientRect();
                    this.tx = ((e.clientX - r.left) / r.width - 0.5) * 14;
                    this.ty = ((e.clientY - r.top) / r.height - 0.5) * -10;
                },
                flat() { this.tx = 0; this.ty = 0; }
             }"
             x-init="if (!matchMedia('(prefers-reduced-motion: reduce)').matches) setInterval(() => { if (auto) active = order[(order.indexOf(active) + 1) % 3]; }, 4500)">

        {{-- Glow behind the stage: its colour follows the chosen template --}}
        <div aria-hidden="true" class="pointer-events-none absolute right-0 top-1/3 -z-10 h-[28rem] w-[28rem] rounded-full blur-[120px] transition-colors duration-700"
             :class="{ 'bg-slate-400/20': active === 'simple', 'bg-indigo-500/30': active === 'modern', 'bg-fuchsia-500/30': active === 'creative' }"></div>

        <div class="mx-auto max-w-2xl text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">Templates</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Same story, three personalities</h2>
            <p class="mt-4 text-lg text-slate-300">Tap a style to switch. Move your mouse over the preview to tilt it.</p>
        </div>

        <div class="mt-12 grid items-center gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.25fr)] lg:gap-12">

            {{-- LEFT: style cards --}}
            <div>
                <div role="tablist" aria-label="Portfolio templates" class="space-y-3">
                    @foreach ($options as $key => $option)
                        <button type="button" role="tab" x-on:click="pick('{{ $key }}')" :aria-selected="active === '{{ $key }}'"
                                class="flex w-full items-start gap-4 rounded-2xl border p-4 text-left transition sm:p-5"
                                :class="active === '{{ $key }}' ? 'border-indigo-400/60 bg-white/10 shadow-lg shadow-indigo-500/10' : 'border-white/10 bg-white/[0.03] hover:bg-white/[0.07]'">
                            <span class="font-mono text-sm text-cyan-300">0{{ $loop->iteration }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-lg font-bold">{{ $option['name'] }}</span>
                                <span class="mt-1 block text-sm leading-relaxed text-slate-300">{{ $option['text'] }}</span>
                                <span class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($option['tags'] as $tag)
                                        <span class="rounded-full border border-white/15 px-2.5 py-0.5 text-[11px] text-slate-300">{{ $tag }}</span>
                                    @endforeach
                                </span>
                            </span>
                        </button>
                    @endforeach
                </div>

                <a href="{{ url('/create') }}"
                   class="mt-6 inline-flex min-h-12 items-center justify-center rounded-xl bg-slate-50 px-6 text-base font-semibold text-slate-900 transition hover:-translate-y-0.5 hover:bg-white">
                    Build with <span class="mx-1" x-text="names[active]"></span> &rarr;
                </a>
            </div>

            {{-- RIGHT: the stage --}}
            <div class="relative" style="perspective: 1200px" aria-hidden="true">
                <div class="rounded-2xl border border-white/15 bg-slate-900 shadow-2xl shadow-black/50 transition-transform duration-200 ease-out will-change-transform"
                     :style="`transform: rotateY(${tx}deg) rotateX(${ty}deg)`"
                     x-on:mousemove="tilt($event)" x-on:mouseleave="flat()">

                    <div class="flex items-center gap-1.5 border-b border-white/10 px-4 py-2.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        <span class="ml-3 truncate rounded-md bg-white/10 px-3 py-0.5 text-[11px] text-slate-300" x-text="'portfolio.app/alex-' + active"></span>
                    </div>

                    <div class="relative h-72 overflow-hidden rounded-b-2xl sm:h-96">

                        {{-- SIMPLE --}}
                        <div x-show="active === 'simple'" x-transition.opacity.duration.400ms class="absolute inset-0">
                            <div class="theme-fixed h-full bg-white p-5 text-slate-700 sm:p-8">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 shrink-0 rounded-full bg-slate-200 sm:h-16 sm:w-16"></div>
                                    <div>
                                        <p class="font-serif text-xl font-semibold text-slate-900 sm:text-2xl">Alex Rivera</p>
                                        <p class="text-xs text-slate-500">alex@email.com &middot; Cebu City</p>
                                    </div>
                                </div>
                                <div class="mt-5 grid grid-cols-[4.5rem_1fr] gap-3 border-t border-slate-200 pt-4 sm:grid-cols-[6rem_1fr]">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">About</p>
                                    <div class="space-y-1.5"><div class="h-1.5 rounded bg-slate-200"></div><div class="h-1.5 w-11/12 rounded bg-slate-200"></div><div class="h-1.5 w-2/3 rounded bg-slate-200"></div></div>
                                </div>
                                <div class="mt-4 grid grid-cols-[4.5rem_1fr] gap-3 border-t border-slate-200 pt-4 sm:grid-cols-[6rem_1fr]">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Experience</p>
                                    <div class="space-y-1.5"><div class="h-2 w-1/2 rounded bg-slate-300"></div><div class="h-1.5 rounded bg-slate-200"></div><div class="h-1.5 w-4/5 rounded bg-slate-200"></div></div>
                                </div>
                                <div class="mt-4 grid grid-cols-[4.5rem_1fr] gap-3 border-t border-slate-200 pt-4 sm:grid-cols-[6rem_1fr]">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Skills</p>
                                    <div class="flex flex-wrap gap-1.5"><span class="rounded border border-slate-200 px-2 py-0.5 text-[10px]">PHP</span><span class="rounded border border-slate-200 px-2 py-0.5 text-[10px]">Laravel</span><span class="rounded border border-slate-200 px-2 py-0.5 text-[10px]">SQL</span></div>
                                </div>
                            </div>
                        </div>

                        {{-- MODERN --}}
                        <div x-show="active === 'modern'" x-transition.opacity.duration.400ms class="absolute inset-0">
                            <div class="theme-fixed h-full space-y-3 bg-slate-100 p-4 sm:p-5">
                                <div class="flex items-center justify-between rounded-full bg-white px-3 py-1.5 shadow-sm">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-[9px] font-bold text-white">AR</span>
                                    <div class="flex gap-1.5"><div class="h-1.5 w-8 rounded bg-slate-300"></div><div class="h-1.5 w-8 rounded bg-slate-300"></div><div class="h-1.5 w-8 rounded bg-slate-300"></div></div>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl bg-slate-900 p-4 text-white">
                                    <div class="h-12 w-12 shrink-0 rounded-2xl bg-indigo-500"></div>
                                    <div><p class="font-bold">Alex Rivera</p><p class="text-xs text-slate-300">Cebu City</p></div>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-[10px] font-semibold text-indigo-700">PHP</span>
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-[10px] font-semibold text-indigo-700">Laravel</span>
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-[10px] font-semibold text-indigo-700">Tailwind</span>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="overflow-hidden rounded-xl bg-white shadow-sm"><div class="h-1.5 bg-linear-to-r from-indigo-500 to-violet-500"></div><div class="space-y-1.5 p-3"><div class="h-2 w-2/3 rounded bg-slate-300"></div><div class="h-1.5 rounded bg-slate-200"></div><div class="h-1.5 w-4/5 rounded bg-slate-200"></div></div></div>
                                    <div class="overflow-hidden rounded-xl bg-white shadow-sm"><div class="h-1.5 bg-linear-to-r from-indigo-500 to-violet-500"></div><div class="space-y-1.5 p-3"><div class="h-2 w-1/2 rounded bg-slate-300"></div><div class="h-1.5 rounded bg-slate-200"></div><div class="h-1.5 w-3/4 rounded bg-slate-200"></div></div></div>
                                </div>
                            </div>
                        </div>

                        {{-- CREATIVE --}}
                        <div x-show="active === 'creative'" x-transition.opacity.duration.400ms class="absolute inset-0">
                            <div class="theme-fixed relative h-full overflow-hidden bg-slate-950 p-5 sm:p-8">
                                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-fuchsia-600/30 blur-3xl"></div>
                                <p class="relative font-mono text-[10px] uppercase tracking-[0.3em] text-amber-300">Based in Cebu</p>
                                <p class="relative mt-2 font-serif text-4xl font-black italic leading-none text-white sm:text-5xl">Alex <span class="bg-linear-to-r from-amber-300 to-fuchsia-400 bg-clip-text text-transparent">Rivera</span></p>
                                <ul class="relative ml-1 mt-6 space-y-4 border-l border-white/20 pl-5">
                                    <li class="relative"><span class="absolute -left-[26px] top-1 h-2.5 w-2.5 rounded-full bg-amber-300"></span><p class="font-mono text-[10px] uppercase tracking-widest text-fuchsia-300">2024 &ndash; Now</p><div class="mt-1 h-2 w-1/2 rounded bg-slate-300"></div><div class="mt-1.5 h-1.5 w-3/4 rounded bg-slate-700"></div></li>
                                    <li class="relative"><span class="absolute -left-[26px] top-1 h-2.5 w-2.5 rounded-full bg-amber-300"></span><p class="font-mono text-[10px] uppercase tracking-widest text-fuchsia-300">2022 &ndash; 2024</p><div class="mt-1 h-2 w-2/5 rounded bg-slate-300"></div><div class="mt-1.5 h-1.5 w-2/3 rounded bg-slate-700"></div></li>
                                    <li class="relative"><span class="absolute -left-[26px] top-1 h-2.5 w-2.5 rounded-full bg-amber-300"></span><p class="font-mono text-[10px] uppercase tracking-widest text-fuchsia-300">2018 &ndash; 2022</p><div class="mt-1 h-2 w-1/3 rounded bg-slate-300"></div></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    
'@
$stageRx = [regex]::new('(?s)\{\{-- =+ TEMPLATES =+ --\}\}.*?(?=\{\{-- =+ FAQ =+ --\}\})')
if ($h -match 'Same story, three personalities') {
    Write-Host "templates: already upgraded"
} elseif ($stageRx.IsMatch($h)) {
    $h = $stageRx.Replace($h, { param($m) $stage }, 1)
    Write-Host "templates: upgraded"
} else {
    Write-Host "MANUAL: could not find the TEMPLATES section in home.blade.php. Send me the file." -ForegroundColor Yellow
}

SaveF $hp $h

php artisan view:clear
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "The build failed. Send me the first red line." -ForegroundColor Red; return }

$tracked = git ls-files
if ($tracked -contains '.env') { Write-Host "STOP: .env is tracked by Git. Run: git rm --cached .env  then run this script again." -ForegroundColor Red; return }
git add .
git commit -m "Typewriter word and template stage on the home page"
git push

Write-Host ""
Write-Host "ALL DONE."