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
    <section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white/60 px-6 py-14 dark:border-white/10 dark:bg-white/[0.03] sm:px-12 sm:py-20">
        <div aria-hidden="true" class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-indigo-400/30 blur-3xl dark:bg-indigo-500/20"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 right-0 h-72 w-72 rounded-full bg-fuchsia-400/30 blur-3xl dark:bg-fuchsia-500/20"></div>

        {{-- Three columns: text = 2/3, visual = 1/3 (rule of thirds) --}}
        <div class="relative grid items-center gap-12 lg:grid-cols-3 lg:gap-10">
            <div class="lg:col-span-2">
                <p class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-500/10 px-3 py-1 text-xs font-semibold text-indigo-700 dark:border-indigo-400/30 dark:text-indigo-300">
                    &#10022; Free portfolio builder
                </p>

                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl lg:text-6xl">
                    Your portfolio, made for a

                    {{-- Alpine.js: a word that rotates every 2.2 seconds --}}
                    <span x-data="{ words: ['developer','designer','student','freelancer','creator'], i: 0 }"
                          x-init="setInterval(() => i = (i + 1) % words.length, 2200)"
                          class="relative block h-[1.25em] overflow-hidden">
                        <template x-for="(word, n) in words" :key="n">
                            <span x-show="i === n" x-text="word"
                                  x-transition:enter="transition duration-500"
                                  x-transition:enter-start="translate-y-4 opacity-0"
                                  x-transition:enter-end="translate-y-0 opacity-100"
                                  class="absolute inset-0 bg-linear-to-r from-indigo-600 via-fuchsia-500 to-amber-500 bg-clip-text text-transparent dark:from-indigo-300 dark:via-fuchsia-300 dark:to-amber-200"></span>
                        </template>
                    </span>
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-600 dark:text-slate-300">
                    Enter your details once, pick a template, and get a clean portfolio that stays saved online.
                    Edit it anytime and save it as a PDF.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ url('/create') }}"
                       class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white shadow-lg shadow-indigo-600/25 transition hover:bg-indigo-500">
                        Create Portfolio &rarr;
                    </a>
                    <a href="#how-it-works"
                       class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-300 px-6 text-base font-semibold text-slate-800 transition hover:bg-slate-50 dark:border-white/15 dark:text-white dark:hover:bg-white/10">
                        See how it works
                    </a>
                </div>

                <ul class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-600 dark:text-slate-400">
                    @foreach (['No sign-up needed', '3 designer templates', 'Save as PDF'] as $item)
                        <li class="flex items-center gap-1.5"><span class="text-emerald-500">&#10003;</span> {{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- A fan of the three templates --}}
            <div class="relative mx-auto h-60 w-full max-w-xs sm:h-64 sm:max-w-sm lg:max-w-none" aria-hidden="true">
                <div class="absolute left-0 top-0 h-28 w-40 -rotate-6 overflow-hidden rounded-2xl border border-slate-200 shadow-xl dark:border-white/10 sm:h-32 sm:w-48 lg:w-44">
                    <x-template-preview type="creative" />
                </div>
                <div class="absolute right-0 top-8 h-28 w-40 rotate-6 overflow-hidden rounded-2xl border border-slate-200 shadow-xl dark:border-white/10 sm:h-32 sm:w-48 lg:w-44">
                    <x-template-preview type="modern" />
                </div>
                <div class="absolute left-1/2 top-24 -translate-x-1/2 sm:top-28">
                    <div class="motion-safe:animate-float">
                        <div class="h-28 w-40 overflow-hidden rounded-2xl border border-slate-200 shadow-xl dark:border-white/10 sm:h-32 sm:w-48 lg:w-44">
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