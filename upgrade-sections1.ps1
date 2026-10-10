$utf8 = New-Object System.Text.UTF8Encoding($false)
function ReadF($p) { [System.IO.File]::ReadAllText((Join-Path (Get-Location) $p)) }
function SaveF($p, $t) { [System.IO.File]::WriteAllText((Join-Path (Get-Location) $p), $t.Replace("`r`n", "`n"), $utf8) }
if (-not (Test-Path "artisan")) { Write-Host "Run this inside the portfolio-laravel folder." -ForegroundColor Red; return }

$hp = "resources/views/home.blade.php"
$h = ReadF $hp

# ============================================================ HOW IT WORKS: the scroll journey
$how = @'
{{-- ================= HOW IT WORKS ================= --}}
    @php
        $journey = [
            ['title' => 'Add your details', 'text' => 'Your name and email are all you need. Add skills, projects, and experience whenever you like.', 'time' => 'About 5 minutes'],
            ['title' => 'Pick a template',  'text' => 'Choose Simple, Modern, or Creative. Switch later without retyping anything.', 'time' => 'One click'],
            ['title' => 'Preview it',       'text' => 'See your portfolio instantly on desktop or phone. Want a change? Edit it and save.', 'time' => 'Instant'],
            ['title' => 'Keep or share it', 'text' => 'It stays saved online. Save it as a PDF, or update it anytime from My Portfolios.', 'time' => 'Always saved'],
        ];
    @endphp

    <section id="how-it-works"
             class="theme-fixed relative isolate scroll-mt-24 overflow-hidden rounded-[2rem] bg-slate-950 px-5 py-16 text-white sm:px-10 sm:py-24"
             x-data="{ p: 0, update() { const el = this.$refs.track; if (!el) return; const r = el.getBoundingClientRect(); this.p = Math.min(1, Math.max(0, (innerHeight * 0.65 - r.top) / r.height)); } }"
             x-init="update()" x-on:scroll.window.passive="update()" x-on:resize.window="update()">

        <div aria-hidden="true" class="pointer-events-none absolute -left-24 top-1/4 -z-10 h-80 w-80 rounded-full bg-cyan-500/15 blur-[110px]"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -right-24 bottom-1/4 -z-10 h-80 w-80 rounded-full bg-fuchsia-500/20 blur-[110px]"></div>

        <div class="mx-auto max-w-2xl text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">How it works</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Four steps. Scroll to light the way.</h2>
            <p class="mt-4 text-lg text-slate-300">No design skills needed. Here is what to expect.</p>
        </div>

        <div class="relative mx-auto mt-14 max-w-5xl" x-ref="track">
            {{-- the line, and the glowing part that grows as you scroll --}}
            <div aria-hidden="true" class="absolute bottom-2 left-4 top-2 w-px bg-white/10 md:left-1/2"></div>
            <div aria-hidden="true" class="absolute left-4 top-2 w-px bg-linear-to-b from-cyan-400 via-indigo-400 to-fuchsia-400 shadow-[0_0_14px_rgba(99,102,241,0.9)] transition-[height] duration-150 md:left-1/2"
                 :style="`height: calc((100% - 1rem) * ${p})`"></div>

            <ol class="space-y-10 md:space-y-16">
                @foreach ($journey as $i => $step)
                    <li class="relative pl-14 md:grid md:grid-cols-2 md:gap-20 md:pl-0">
                        <span class="absolute left-4 top-6 flex h-9 w-9 -translate-x-1/2 items-center justify-center rounded-full border text-sm font-bold transition-all duration-500 md:left-1/2"
                              :class="p >= ({{ $i }} + 0.25) / 4 ? 'scale-110 border-transparent bg-linear-to-br from-cyan-400 to-fuchsia-500 text-slate-950 shadow-[0_0_24px_rgba(99,102,241,0.8)]' : 'border-white/20 bg-slate-900 text-slate-400'">{{ $i + 1 }}</span>

                        <div class="{{ $i % 2 === 0 ? 'md:col-start-1' : 'md:col-start-2' }}">
                            <div class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 backdrop-blur transition hover:border-indigo-400/40 hover:bg-white/[0.07]">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-cyan-200">{{ $step['time'] }}</span>
                                    <span class="font-mono text-xs text-slate-500">STEP 0{{ $i + 1 }}</span>
                                </div>
                                <h3 class="mt-4 text-xl font-bold">{{ $step['title'] }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $step['text'] }}</p>

                                <div class="mt-5 rounded-2xl border border-white/10 bg-slate-900/70 p-4" aria-hidden="true">
                                    @if ($i === 0)
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2 rounded-lg bg-white/5 px-3 py-2"><span class="w-10 text-[10px] text-slate-500">Name</span><span class="h-2 w-24 rounded bg-slate-300"></span><span class="h-3.5 w-px animate-pulse bg-cyan-300"></span></div>
                                            <div class="flex items-center gap-2 rounded-lg bg-white/5 px-3 py-2"><span class="w-10 text-[10px] text-slate-500">Email</span><span class="h-2 w-32 rounded bg-slate-500/70"></span></div>
                                        </div>
                                    @elseif ($i === 1)
                                        <div class="flex gap-2">
                                            <span class="h-12 flex-1 rounded-lg bg-white"></span>
                                            <span class="h-12 flex-1 rounded-lg bg-indigo-500 ring-2 ring-cyan-300"></span>
                                            <span class="h-12 flex-1 rounded-lg bg-slate-700"></span>
                                        </div>
                                    @elseif ($i === 2)
                                        <div class="overflow-hidden rounded-lg border border-white/10">
                                            <div class="flex gap-1 border-b border-white/10 px-2 py-1.5"><span class="h-1.5 w-1.5 rounded-full bg-red-400"></span><span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span></div>
                                            <div class="flex items-center gap-3 p-3"><span class="h-8 w-8 rounded-full bg-indigo-500"></span><div class="flex-1 space-y-1.5"><div class="h-2 w-1/2 rounded bg-slate-300"></div><div class="h-1.5 w-3/4 rounded bg-slate-600"></div></div></div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between">
                                            <span class="rounded-md bg-red-500/20 px-2 py-1 font-mono text-[10px] text-red-300">PDF</span>
                                            <span class="text-xs text-emerald-300">&#10003; Saved online</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

        {{-- What you'll need --}}
        <div class="mx-auto mt-16 max-w-5xl rounded-3xl border border-white/10 bg-white/[0.04] p-6 md:flex md:items-start md:justify-between md:gap-10 md:p-8">
            <div class="md:max-w-xs">
                <h3 class="text-xl font-bold">What you'll need</h3>
                <p class="mt-2 text-sm text-slate-300">Only two things are required. Start small and add more later.</p>
                <a href="{{ url('/create') }}" class="mt-4 inline-block text-sm font-semibold text-cyan-300 hover:text-cyan-200">Start now &rarr;</a>
            </div>
            <div class="mt-6 grid gap-5 sm:grid-cols-2 md:mt-0 md:flex-1">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-slate-400">Required</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Full name', 'Email'] as $item)
                            <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-sm font-medium text-indigo-200">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-slate-400">Optional</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Profile photo', 'About me', 'Education', 'Skills', 'Projects', 'Experience', 'Social links'] as $item)
                            <span class="rounded-full bg-white/10 px-3 py-1 text-sm text-slate-300">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    
'@
$howRx = [regex]::new('(?s)\{\{-- =+ HOW IT WORKS =+ --\}\}.*?(?=\{\{-- =+ TEMPLATES =+ --\}\})')
if ($h -match 'x-ref="track"') { Write-Host "how it works: already upgraded" }
elseif ($howRx.IsMatch($h)) { $h = $howRx.Replace($h, { param($m) $how }, 1); Write-Host "how it works: upgraded" }
else { Write-Host "MANUAL: could not find the HOW IT WORKS section markers in home.blade.php" -ForegroundColor Yellow }

# ============================================================ FAQ: the chat
$faq = @'
{{-- ================= FAQ ================= --}}
    @php
        $qa = [
            ['q' => 'Do I need an account?', 'a' => 'Nope! There is no sign-up. Open the form, fill it in, and your portfolio is saved online.'],
            ['q' => 'Can I edit my portfolio later?', 'a' => 'Yes. Open My Portfolios and press Edit. You can also switch the template at any time without retyping anything.'],
            ['q' => 'Can I download it as a PDF?', 'a' => 'Yes. Open your portfolio and press Print / Save as PDF. For the dark Creative template, tick "Background graphics" in the print window so the colours are included.'],
            ['q' => 'Is my information private?', 'a' => 'Honest answer: there are no accounts yet, so anyone who visits My Portfolios can see saved portfolios. Please do not enter anything you want to keep private.'],
            ['q' => 'Does it work on my phone?', 'a' => 'Yes! Every page and all three templates adapt to phones, tablets, and desktops.'],
        ];
    @endphp

    <section class="theme-fixed relative isolate overflow-hidden rounded-[2rem] bg-slate-950 px-5 py-16 text-white sm:px-10 sm:py-24"
             x-data="{ i: 0, typing: false, qa: {{ Js::from($qa) }},
                       ask(n) { if (n === this.i) return; this.i = n; this.typing = true; setTimeout(() => this.typing = false, 750); } }">
        <div aria-hidden="true" class="pointer-events-none absolute -right-24 top-0 -z-10 h-80 w-80 rounded-full bg-indigo-500/20 blur-[110px]"></div>

        <div class="mx-auto max-w-2xl text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">FAQ</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Ask away. We'll answer.</h2>
            <p class="mt-4 text-lg text-slate-300">Tap a question and get a reply.</p>
        </div>

        <div class="mx-auto mt-12 grid max-w-5xl gap-6 lg:grid-cols-[1fr_1.35fr]">
            {{-- questions --}}
            <div class="space-y-2">
                <template x-for="(item, n) in qa" :key="n">
                    <button type="button" x-on:click="ask(n)" :aria-pressed="i === n"
                            class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3.5 text-left text-sm font-semibold transition"
                            :class="i === n ? 'border-cyan-400/50 bg-white/10 text-white' : 'border-white/10 bg-white/[0.03] text-slate-300 hover:bg-white/[0.07]'">
                        <span class="font-mono text-xs text-cyan-300" x-text="'0' + (n + 1)"></span>
                        <span x-text="item.q"></span>
                    </button>
                </template>
            </div>

            {{-- chat window --}}
            <div class="flex min-h-[20rem] flex-col rounded-3xl border border-white/10 bg-slate-900/80 shadow-2xl shadow-black/40">
                <div class="flex items-center gap-3 border-b border-white/10 px-5 py-3">
                    <img src="{{ asset('favicon.svg') }}?v=2" alt="" class="h-8 w-8">
                    <div>
                        <p class="text-sm font-bold">Portfolio Bot</p>
                        <p class="flex items-center gap-1.5 text-xs text-emerald-300"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Online</p>
                    </div>
                </div>

                <div class="flex flex-1 flex-col justify-end gap-4 p-5" aria-live="polite">
                    <div class="flex justify-end">
                        <p class="max-w-[85%] rounded-2xl rounded-br-md bg-linear-to-r from-indigo-600 to-violet-600 px-4 py-2.5 text-sm font-medium" x-text="qa[i].q"></p>
                    </div>
                    <div class="flex">
                        <div class="max-w-[90%] rounded-2xl rounded-bl-md bg-white/10 px-4 py-3 text-sm leading-relaxed text-slate-100">
                            <span x-show="typing" class="flex gap-1 py-1" aria-label="Typing">
                                <span class="h-2 w-2 animate-bounce rounded-full bg-slate-300" style="animation-delay:-0.3s"></span>
                                <span class="h-2 w-2 animate-bounce rounded-full bg-slate-300" style="animation-delay:-0.15s"></span>
                                <span class="h-2 w-2 animate-bounce rounded-full bg-slate-300"></span>
                            </span>
                            <span x-show="!typing" x-text="qa[i].a"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
'@
$faqRx = [regex]::new('(?s)\{\{-- =+ FAQ =+ --\}\}.*?(?=\{\{-- =+ FINAL CTA =+ --\}\})')
if ($h -match 'Portfolio Bot') { Write-Host "faq: already upgraded" }
elseif ($faqRx.IsMatch($h)) { $h = $faqRx.Replace($h, { param($m) $faq }, 1); Write-Host "faq: upgraded" }
else { Write-Host "MANUAL: could not find the FAQ section markers in home.blade.php" -ForegroundColor Yellow }

SaveF $hp $h
Write-Host ""
Write-Host "Done with 1!"