@use('App\Support\Format', 'F')
@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;0,900;1,600;1,900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>.tpl-body{font-family:'Space Grotesk',system-ui,sans-serif}.tpl-display{font-family:'Fraunces',Georgia,serif}</style>
@endpush

@php
    $p = $portfolio;

    // Name split: "Juan Dela Cruz" -> "Juan Dela" + "Cruz" (the last word gets the gradient)
    $words = preg_split('/\s+/', trim($p->full_name), -1, PREG_SPLIT_NO_EMPTY);
    $last = array_pop($words);
    $first = implode(' ', $words);

    // Section numbers only count sections that are actually shown (01, 02, 03...)
    $n = 0;
    $num = function () use (&$n) { return str_pad(++$n, 2, '0', STR_PAD_LEFT); };

    // Experience and education share one timeline layout
    $timelines = [];
    if ($p->experiences->isNotEmpty()) {
        $timelines['Journey'] = $p->experiences->map(fn ($x) => [
            'when' => F::dateRange($x->start_date, $x->end_date), 'title' => $x->job_title, 'sub' => $x->company, 'text' => $x->description,
        ])->all();
    }
    if ($p->education->isNotEmpty()) {
        $timelines['Learning'] = $p->education->map(fn ($e) => [
            'when' => $e->year, 'title' => $e->school, 'sub' => $e->degree, 'text' => $e->description,
        ])->all();
    }
@endphp

<div class="tpl-body overflow-hidden rounded-[2rem] bg-slate-950 text-slate-200 shadow-2xl">

    {{-- HERO --}}
    <header class="relative overflow-hidden px-6 pb-14 pt-10 sm:px-12 sm:pt-14 lg:px-16">
        <div aria-hidden="true" class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-fuchsia-600/30 blur-[120px]"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -left-24 bottom-0 h-80 w-80 rounded-full bg-amber-400/20 blur-[120px]"></div>

        <div class="relative grid items-end gap-10 lg:grid-cols-[1fr_auto]">
            <div class="min-w-0">
                <p class="font-mono text-xs uppercase tracking-[0.3em] text-amber-300">{{ $p->address ? 'Based in '.$p->address : 'Portfolio' }}</p>
                <h1 class="tpl-display mt-6 text-5xl font-black leading-[0.95] tracking-tight sm:text-7xl lg:text-8xl">
                    @if ($first) <span class="block text-white">{{ $first }}</span> @endif
                    <span class="block bg-linear-to-r from-amber-300 via-fuchsia-400 to-violet-400 bg-clip-text italic text-transparent">{{ $last }}</span>
                </h1>
            </div>

            <div class="relative mx-auto lg:mx-0">
                <div class="absolute inset-0 translate-x-3 translate-y-3 rotate-3 rounded-3xl border-2 border-amber-300"></div>
                @if ($p->profile_image)
                    <img src="{{ $p->profile_image }}" alt="{{ $p->full_name }}" class="relative h-44 w-44 -rotate-3 rounded-3xl object-cover sm:h-56 sm:w-56">
                @else
                    <div class="tpl-display relative flex h-44 w-44 -rotate-3 items-center justify-center rounded-3xl bg-amber-300 text-6xl font-black text-slate-950 sm:h-56 sm:w-56">{{ F::initials($p->full_name) }}</div>
                @endif
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <div class="space-y-20 px-6 py-14 sm:px-12 lg:px-16">

        @if ($p->about_me)
            <section>
                <div class="mb-8 flex items-baseline gap-4">
                    <span class="font-mono text-sm text-amber-300">{{ $num() }}</span>
                    <h2 class="tpl-display text-3xl font-bold italic text-white sm:text-4xl">Hello.</h2>
                    <span class="h-px flex-1 bg-white/10"></span>
                </div>
                <p class="max-w-3xl whitespace-pre-line text-xl leading-relaxed text-slate-300 sm:text-2xl">{{ $p->about_me }}</p>
            </section>
        @endif

        @if ($p->skills->isNotEmpty())
            <section>
                <div class="mb-8 flex items-baseline gap-4">
                    <span class="font-mono text-sm text-amber-300">{{ $num() }}</span>
                    <h2 class="tpl-display text-3xl font-bold italic text-white sm:text-4xl">Toolbox</h2>
                    <span class="h-px flex-1 bg-white/10"></span>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach ($p->skills as $skill)
                        <span class="border border-amber-300/60 bg-amber-300/5 px-4 py-2 text-sm font-semibold text-amber-200 {{ $loop->even ? 'rotate-1' : '-rotate-1' }}">{{ $skill->skill_name }}</span>
                    @endforeach
                </div>
            </section>
        @endif

        @if (count($timelines) > 0)
            <div class="grid gap-16 {{ count($timelines) > 1 ? 'lg:grid-cols-2' : '' }}">
                @foreach ($timelines as $title => $items)
                    <section>
                        <div class="mb-8 flex items-baseline gap-4">
                            <span class="font-mono text-sm text-amber-300">{{ $num() }}</span>
                            <h2 class="tpl-display text-3xl font-bold italic text-white sm:text-4xl">{{ $title }}</h2>
                            <span class="h-px flex-1 bg-white/10"></span>
                        </div>
                        <ol class="relative ml-2 border-l border-white/15">
                            @foreach ($items as $item)
                                <li class="relative mb-9 pl-8 last:mb-0">
                                    <span class="absolute -left-[7px] top-1.5 h-3.5 w-3.5 rounded-full border-2 border-slate-950 bg-amber-300 shadow-[0_0_0_4px_rgba(251,191,36,0.15)]"></span>
                                    @if ($item['when']) <p class="font-mono text-xs uppercase tracking-widest text-fuchsia-300">{{ $item['when'] }}</p> @endif
                                    <h3 class="mt-1 text-xl font-bold text-white">{{ $item['title'] }}</h3>
                                    @if ($item['sub']) <p class="text-sm text-slate-400">{{ $item['sub'] }}</p> @endif
                                    @if ($item['text']) <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $item['text'] }}</p> @endif
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endforeach
            </div>
        @endif

        @if ($p->projects->isNotEmpty())
            <section>
                <div class="mb-8 flex items-baseline gap-4">
                    <span class="font-mono text-sm text-amber-300">{{ $num() }}</span>
                    <h2 class="tpl-display text-3xl font-bold italic text-white sm:text-4xl">Selected work</h2>
                    <span class="h-px flex-1 bg-white/10"></span>
                </div>
                <div class="border-b border-white/10">
                    @foreach ($p->projects as $project)
                        <article class="group grid gap-3 border-t border-white/10 py-8 transition hover:bg-white/[0.03] md:grid-cols-[6rem_1fr] md:gap-6">
                            <span class="tpl-display text-5xl font-black text-fuchsia-500/40 transition group-hover:text-amber-300 sm:text-6xl">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="min-w-0">
                                <h3 class="text-2xl font-bold text-white sm:text-3xl">{{ $project->project_name }}</h3>
                                @if ($project->description) <p class="mt-2 max-w-2xl leading-relaxed text-slate-400">{{ $project->description }}</p> @endif
                                @if ($project->technologies)
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach (F::splitList($project->technologies) as $tech)
                                            <span class="rounded-full border border-white/15 px-3 py-0.5 font-mono text-xs text-slate-300">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                @if ($url = F::safeUrl($project->project_link))
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-fuchsia-300 hover:text-amber-300">Open project &#8599;</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    {{-- FOOTER / CONTACT --}}
    <footer class="border-t border-white/10 bg-black/30 px-6 py-14 sm:px-12 lg:px-16">
        <p class="font-mono text-xs uppercase tracking-[0.3em] text-fuchsia-300">Let's work together</p>
        <a href="mailto:{{ $p->email }}" class="tpl-display mt-4 block break-all text-3xl font-black italic text-white transition hover:text-amber-300 sm:text-5xl">{{ $p->email }}</a>

        <div class="mt-6 flex flex-wrap gap-x-8 gap-y-1 text-sm text-slate-400">
            @if ($p->contact_number) <p>{{ $p->contact_number }}</p> @endif
            @if ($p->address) <p>{{ $p->address }}</p> @endif
        </div>

        @if ($p->socialLinks->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($p->socialLinks as $social)
                    @if ($url = F::safeUrl($social->url))
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 rounded-full border border-fuchsia-400/60 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-fuchsia-200 transition hover:bg-fuchsia-400 hover:text-slate-950">{{ $social->platform }} &#8599;</a>
                    @endif
                @endforeach
            </div>
        @endif
    </footer>
</div>