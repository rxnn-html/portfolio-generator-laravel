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

<div class="space-y-20 sm:space-y-28" data-reveal-group>

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

    {{-- ================= HOW IT WORKS ================= --}}
    <section id="how-it-works" class="scroll-mt-24">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">How it works</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">From blank page to portfolio in four steps</h2>
            <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">No design skills needed. Here is what to expect.</p>
        </div>

        <ol class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($steps as $step)
                <li class="rounded-3xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg dark:border-white/10">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/10 text-lg font-extrabold text-indigo-600 dark:text-indigo-300">
                        {{ $loop->iteration }}
                    </span>
                    <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-white">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $step['text'] }}</p>
                    <p class="mt-4 inline-block rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ $step['time'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="mt-8 grid gap-6 rounded-3xl border border-slate-200 bg-white p-6 dark:border-white/10 md:grid-cols-[1fr_2fr] md:p-8">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">What you'll need</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Only two things are required. Start small and add more later.</p>
                <a href="{{ url('/create') }}" class="mt-4 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Start now &rarr;</a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-slate-400">Required</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Full name', 'Email'] as $item)
                            <span class="rounded-full bg-indigo-500/10 px-3 py-1 text-sm font-medium text-indigo-700 dark:text-indigo-300">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-slate-400">Optional</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Profile photo', 'About me', 'Education', 'Skills', 'Projects', 'Experience', 'Social links'] as $item)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= TEMPLATES ================= --}}
    <section>
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">Templates</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">Three looks, one click apart</h2>
            <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">Same information, completely different personality.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ($templates as $template)
                <a href="{{ url('/create') }}"
                   class="group overflow-hidden rounded-3xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-xl dark:border-white/10">
                    <div class="h-44 overflow-hidden border-b border-slate-200 dark:border-white/10">
                        <x-template-preview :type="$template['key']" />
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $template['name'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $template['text'] }}</p>
                        <span class="mt-4 inline-block text-sm font-semibold text-indigo-600 transition group-hover:translate-x-1 dark:text-indigo-300">Try it &rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ================= FAQ ================= --}}
    <section>
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">FAQ</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">Questions? Answered.</h2>
        </div>

        <div class="mx-auto mt-10 max-w-3xl space-y-3">
            @foreach ($faqs as $faq)
                <details class="group rounded-2xl border border-slate-200 bg-white px-5 py-4 open:shadow-sm dark:border-white/10">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-900 dark:text-white [&::-webkit-details-marker]:hidden">
                        {{ $faq['q'] }}
                        <span class="shrink-0 text-slate-400 transition group-open:rotate-180">&#9662;</span>
                    </summary>
                    <p class="mt-3 leading-relaxed text-slate-600 dark:text-slate-300">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- ================= FINAL CTA ================= --}}
    <section class="relative overflow-hidden rounded-[2rem] bg-linear-to-br from-indigo-600 via-violet-600 to-fuchsia-600 px-6 py-14 text-center text-white sm:px-12 sm:py-20">
        <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Ready to build yours?</h2>
        <p class="mx-auto mt-4 max-w-xl text-lg text-indigo-100">It takes a few minutes, and you can change everything later.</p>
        <a href="{{ url('/create') }}"
           class="mt-8 inline-flex min-h-12 items-center justify-center rounded-xl bg-slate-50 px-7 text-base font-semibold text-slate-900 shadow-lg transition hover:bg-white">
            Create Portfolio &rarr;
        </a>
    </section>
</div>
@endsection