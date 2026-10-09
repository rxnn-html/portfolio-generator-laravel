@use('App\Support\Format', 'F')
@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>.tpl-modern{font-family:'Plus Jakarta Sans',system-ui,sans-serif}</style>
@endpush

@php
    $p = $portfolio;
    $hasJourney = $p->experiences->isNotEmpty() || $p->education->isNotEmpty();
    $both = $p->experiences->isNotEmpty() && $p->education->isNotEmpty();

    $nav = array_values(array_filter([
        $p->about_me ? ['#about', 'About'] : null,
        $p->skills->isNotEmpty() ? ['#skills', 'Skills'] : null,
        $p->projects->isNotEmpty() ? ['#projects', 'Projects'] : null,
        $hasJourney ? ['#journey', 'Journey'] : null,
        ['#contact', 'Contact'],
    ]));
    $stats = array_values(array_filter([
        $p->projects->isNotEmpty() ? ['Projects', $p->projects->count()] : null,
        $p->skills->isNotEmpty() ? ['Skills', $p->skills->count()] : null,
        $p->experiences->isNotEmpty() ? ['Roles', $p->experiences->count()] : null,
    ]));

    $card = 'scroll-mt-24 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 sm:p-8';
    $h2 = 'text-xl font-bold tracking-tight text-slate-900 sm:text-2xl';
@endphp

<div class="tpl-modern space-y-5 rounded-[2rem] bg-slate-100 p-3 sm:p-5">

    {{-- STICKY NAVIGATION --}}
    <nav class="sticky top-3 z-10 flex items-center justify-between gap-3 rounded-full bg-white/80 px-3 py-2 shadow-sm ring-1 ring-slate-200 backdrop-blur">
        <span class="flex shrink-0 items-center gap-2 pl-1">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">{{ F::initials($p->full_name) }}</span>
            <span class="hidden text-sm font-semibold text-slate-900 sm:inline">{{ $p->full_name }}</span>
        </span>
        <div class="flex gap-1 overflow-x-auto">
            @foreach ($nav as [$href, $name])
                <a href="{{ $href }}" class="whitespace-nowrap rounded-full px-3.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">{{ $name }}</a>
            @endforeach
        </div>
    </nav>

    {{-- HERO --}}
    <header class="relative overflow-hidden rounded-3xl bg-slate-900 p-6 text-white sm:p-10">
        <div aria-hidden="true" class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-indigo-500/40 blur-3xl"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-violet-500/30 blur-3xl"></div>

        <div class="relative flex flex-col items-center gap-6 text-center sm:flex-row sm:text-left">
            @if ($p->profile_image)
                <img src="{{ $p->profile_image }}" alt="{{ $p->full_name }}" class="h-32 w-32 shrink-0 rounded-3xl object-cover ring-4 ring-white/20 sm:h-36 sm:w-36">
            @else
                <div class="flex h-32 w-32 shrink-0 items-center justify-center rounded-3xl bg-white/10 text-4xl font-bold ring-4 ring-white/20 sm:h-36 sm:w-36">{{ F::initials($p->full_name) }}</div>
            @endif
            <div class="min-w-0">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl">{{ $p->full_name }}</h1>
                @if ($p->address) <p class="mt-2 text-slate-300">{{ $p->address }}</p> @endif
                <a href="mailto:{{ $p->email }}" class="mt-5 inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-indigo-100">Get in touch</a>
            </div>
        </div>

        @if (count($stats) > 0)
            <div class="relative mt-8 flex gap-3">
                @foreach ($stats as [$name, $value])
                    <div class="flex-1 rounded-2xl bg-white/10 p-4 backdrop-blur">
                        <p class="text-2xl font-extrabold">{{ $value }}</p>
                        <p class="text-xs uppercase tracking-wider text-slate-300">{{ $name }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </header>

    @if ($p->about_me)
        <section id="about" class="{{ $card }}">
            <h2 class="{{ $h2 }}">About me</h2>
            <p class="mt-5 whitespace-pre-line leading-relaxed text-slate-600">{{ $p->about_me }}</p>
        </section>
    @endif

    @if ($p->skills->isNotEmpty())
        <section id="skills" class="{{ $card }}">
            <h2 class="{{ $h2 }}">Skills</h2>
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($p->skills as $skill)
                    <span class="rounded-full bg-indigo-50 px-4 py-1.5 text-sm font-semibold text-indigo-700 ring-1 ring-indigo-100">{{ $skill->skill_name }}</span>
                @endforeach
            </div>
        </section>
    @endif

    @if ($p->projects->isNotEmpty())
        <section id="projects" class="{{ $card }}">
            <h2 class="{{ $h2 }}">Projects</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                @foreach ($p->projects as $project)
                    <article class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="h-1.5 bg-linear-to-r from-indigo-500 to-violet-500"></div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="text-lg font-bold text-slate-900">{{ $project->project_name }}</h3>
                            @if ($project->description) <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $project->description }}</p> @endif
                            <div class="mt-4 flex flex-wrap gap-1.5">
                                @foreach (F::splitList($project->technologies) as $tech)
                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $tech }}</span>
                                @endforeach
                            </div>
                            @if ($url = F::safeUrl($project->project_link))
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-1 self-start text-sm font-semibold text-indigo-600 hover:text-indigo-800">View project &#8599;</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($hasJourney)
        <div id="journey" class="grid scroll-mt-24 gap-5 lg:grid-cols-2">
            @if ($p->experiences->isNotEmpty())
                <section class="{{ $card }} {{ $both ? '' : 'lg:col-span-2' }}">
                    <h2 class="{{ $h2 }}">Experience</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($p->experiences as $job)
                            <div class="flex gap-4 rounded-2xl bg-slate-50 p-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white">{{ mb_strtoupper(mb_substr($job->company, 0, 1)) }}</div>
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-slate-900">{{ $job->job_title }}</h3>
                                    <p class="text-sm font-medium text-indigo-600">{{ $job->company }}</p>
                                    <p class="text-xs text-slate-500">{{ F::dateRange($job->start_date, $job->end_date) }}</p>
                                    @if ($job->description) <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $job->description }}</p> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($p->education->isNotEmpty())
                <section class="{{ $card }} {{ $both ? '' : 'lg:col-span-2' }}">
                    <h2 class="{{ $h2 }}">Education</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($p->education as $item)
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <h3 class="font-semibold text-slate-900">{{ $item->school }}</h3>
                                @if ($item->degree) <p class="text-sm font-medium text-indigo-600">{{ $item->degree }}</p> @endif
                                @if ($item->year) <p class="text-xs text-slate-500">{{ $item->year }}</p> @endif
                                @if ($item->description) <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $item->description }}</p> @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    @endif

    <section id="contact" class="{{ $card }}">
        <h2 class="{{ $h2 }}">Contact</h2>
        <ul class="mt-5 grid gap-3 text-sm text-slate-600 sm:grid-cols-3">
            <li class="rounded-xl bg-slate-50 p-3">{{ $p->email }}</li>
            @if ($p->contact_number) <li class="rounded-xl bg-slate-50 p-3">{{ $p->contact_number }}</li> @endif
            @if ($p->address) <li class="rounded-xl bg-slate-50 p-3">{{ $p->address }}</li> @endif
        </ul>
        @if ($p->socialLinks->isNotEmpty())
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($p->socialLinks as $social)
                    @if ($url = F::safeUrl($social->url))
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700">{{ $social->platform }} &#8599;</a>
                    @endif
                @endforeach
            </div>
        @endif
    </section>
</div>