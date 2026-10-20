@extends('layouts.app')

@section('title', 'Portfolio Generator')

@section('content')
@php
    $steps = [
        ['title' => 'Add your details', 'text' => 'Your name and email are all you need to start. Add skills, projects, and experience whenever you like.', 'time' => 'About 5 minutes'],
        ['title' => 'Pick a template',  'text' => 'Choose Simple, Modern, or Creative. You can switch later without retyping anything.', 'time' => 'One click'],
        ['title' => 'Preview it',       'text' => 'See your portfolio instantly, on desktop or phone. Want a change? Edit it and save.', 'time' => 'Instant'],
        ['title' => 'Keep or share it', 'text' => 'It stays saved online. Save it as a PDF, or update it anytime from My Portfolios.', 'time' => 'Always saved'],
    ];

    $templates = [
        ['key' => 'simple',   'name' => 'Simple',   'text' => 'Clean, minimal, and print-friendly. A classic resume layout.'],
        ['key' => 'modern',   'name' => 'Modern',   'text' => 'Rounded cards, skill badges, and a sticky navigation bar.'],
        ['key' => 'creative', 'name' => 'Creative', 'text' => 'A bold dark design with a timeline and oversized type.'],
    ];

    $faqs = [
        ['q' => 'Do I need an account?', 'a' => 'No. There is no sign-up. Open the form, fill it in, and your portfolio is saved.'],
        ['q' => 'Can I edit my portfolio later?', 'a' => 'Yes. Open My Portfolios and press Edit. You can also change the template at any time.'],
        ['q' => 'Can I download it as a PDF?', 'a' => 'Yes. Open your portfolio and use Print / Save as PDF. For the dark Creative template, tick "Background graphics" in the print window.'],
        ['q' => 'Is my information private?', 'a' => 'There are no accounts yet, so saved portfolios can be seen by anyone who visits the My Portfolios page. Please do not enter anything you want to keep private.'],
        ['q' => 'Does it work on my phone?', 'a' => 'Yes. Every page and all three templates adapt to phones, tablets, and desktops.'],
    ];
@endphp

<div class="space-y-20 sm:space-y-28">

    {{-- ================= HERO ================= --}}
    <section class="theme-fixed relative isolate overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-16 text-white sm:px-12 sm:py-24">
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
    </span>.
                </p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ url('/create') }}"
                       class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-linear-to-r from-indigo-600 to-violet-600 px-7 text-base font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:-translate-y-0.5 hover:shadow-indigo-500/50">
                        Create Portfolio &rarr;
                    </a>
                    <a href="#how-it-works"
                       class="inline-flex min-h-12 items-center justify-center rounded-xl bg-slate-50 px-7 text-base font-semibold text-slate-900 transition hover:-translate-y-0.5 hover:bg-white">
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

    {{-- ================= FAQ ================= --}}
    @php
        $qa = [
            ['q' => 'Do I need an account?', 'a' => 'Yes, but it takes one click. Sign in with your Google account, then fill in the form. There is no password to create or remember.'],
            ['q' => 'Can I edit my portfolio later?', 'a' => 'Yes. Open My Portfolios and press Edit. You can also switch the template at any time without retyping anything.'],
            ['q' => 'Can I download it as a PDF?', 'a' => 'Yes. Open your portfolio and press Print / Save as PDF. For the dark Creative template, tick "Background graphics" in the print window so the colours are included.'],
            ['q' => 'Is my information private?', 'a' => 'Only you can edit or delete your portfolios. Anyone with the link to a portfolio can view it, so do not enter anything you want to keep private.'],
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

    {{-- ================= FINAL CTA ================= --}}
    <section class="theme-fixed relative isolate overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-16 text-white sm:px-12 sm:py-24"
             x-data="{ name: '',
                       get shown() { return this.name.trim() || 'Your Name'; },
                       get initials() { return this.name.trim().split(/\s+/).filter(Boolean).slice(0, 2).map(w => w[0].toUpperCase()).join('') || 'YN'; },
                       get first() { return this.name.trim().split(/\s+/)[0] || ''; },
                       get url() { return '{{ url('/create') }}' + (this.name.trim() ? '?name=' + encodeURIComponent(this.name.trim().slice(0, 100)) : ''); } }">

        <div aria-hidden="true" class="pointer-events-none absolute -left-20 -top-20 -z-10 h-96 w-96 rounded-full bg-indigo-600/40 blur-[110px] motion-safe:animate-drift"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 right-0 -z-10 h-96 w-96 rounded-full bg-fuchsia-600/30 blur-[110px] motion-safe:animate-drift-slow"></div>

        <div class="relative grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">Ready when you are</p>
                <h2 class="mt-3 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">
                    Ready to build <span class="bg-linear-to-r from-cyan-300 via-sky-300 to-fuchsia-300 bg-clip-text text-transparent">yours?</span>
                </h2>
                <p class="mt-5 max-w-md text-lg text-slate-300">Type your name and watch your portfolio card come alive. We'll carry it into the form for you.</p>

                <label for="cta-name" class="mt-8 block text-sm font-medium text-slate-300">Your name</label>
                <input id="cta-name" type="text" x-model="name" maxlength="60" autocomplete="name" placeholder="e.g. Alex Rivera"
                       class="mt-2 w-full max-w-md rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-base text-white placeholder-slate-400 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30">

                <a :href="url"
                   class="mt-5 inline-flex min-h-12 items-center justify-center rounded-xl bg-slate-50 px-7 text-base font-semibold text-slate-900 shadow-lg transition hover:-translate-y-0.5 hover:bg-white">
                    Create Portfolio<span x-show="first">&nbsp;as&nbsp;<span x-text="first"></span></span>&nbsp;&rarr;
                </a>
            </div>

            {{-- the live card, with a spinning gradient border --}}
            <div class="relative mx-auto w-full max-w-sm" aria-hidden="true">
                <div class="relative overflow-hidden rounded-3xl p-px">
                    <div class="absolute -inset-[100%] bg-[conic-gradient(from_0deg,#22d3ee,#6366f1,#d946ef,#fcd34d,#22d3ee)] motion-safe:animate-[spin_8s_linear_infinite]"></div>
                    <div class="relative rounded-[calc(1.5rem-1px)] bg-slate-900 p-7">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-cyan-400 to-fuchsia-500 text-xl font-extrabold text-slate-950" x-text="initials"></div>
                            <div class="min-w-0">
                                <p class="truncate text-2xl font-extrabold" x-text="shown"></p>
                                <p class="flex items-center gap-1.5 text-xs text-emerald-300"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Open to opportunities</p>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200">PHP</span>
                            <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200">Laravel</span>
                            <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200">SQL</span>
                            <span class="rounded-full bg-fuchsia-500/20 px-3 py-1 text-xs font-semibold text-fuchsia-200">UI / UX</span>
                        </div>
                        <div class="mt-6 space-y-2">
                            <div class="h-2 w-full rounded bg-white/10"></div>
                            <div class="h-2 w-5/6 rounded bg-white/10"></div>
                            <div class="h-2 w-2/3 rounded bg-white/10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection