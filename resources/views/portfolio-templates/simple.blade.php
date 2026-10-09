@use('App\Support\Format', 'F')
@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Source+Serif+4:wght@400;600&display=swap" rel="stylesheet">
<style>.tpl-sans{font-family:'Inter',system-ui,sans-serif}.tpl-serif{font-family:'Source Serif 4',Georgia,serif}</style>
@endpush

@php
    $p = $portfolio;
    $row = 'grid gap-4 border-t border-slate-200 py-8 md:grid-cols-[10rem_1fr] md:gap-10';
    $label = 'text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400';
    $link = 'inline-flex items-center gap-1 text-sm font-medium text-slate-900 underline decoration-slate-300 underline-offset-4 hover:decoration-slate-900';
@endphp

<article class="tpl-sans mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white px-6 py-12 text-slate-700 shadow-sm sm:px-14 sm:py-16 print:border-0 print:shadow-none">

    {{-- PROFILE --}}
    <header class="flex flex-col items-start gap-6 pb-10 sm:flex-row sm:items-center sm:gap-8">
        @if ($p->profile_image)
            <img src="{{ $p->profile_image }}" alt="{{ $p->full_name }}" class="h-28 w-28 shrink-0 rounded-full object-cover ring-1 ring-slate-200">
        @else
            <div class="tpl-serif flex h-28 w-28 shrink-0 items-center justify-center rounded-full bg-slate-100 text-3xl font-semibold text-slate-500">{{ F::initials($p->full_name) }}</div>
        @endif

        <div class="min-w-0">
            <h1 class="tpl-serif text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">{{ $p->full_name }}</h1>
            <ul class="mt-4 flex flex-wrap gap-x-5 gap-y-1.5 text-sm text-slate-500">
                <li>{{ $p->email }}</li>
                @if ($p->contact_number) <li>{{ $p->contact_number }}</li> @endif
                @if ($p->address) <li>{{ $p->address }}</li> @endif
            </ul>
        </div>
    </header>

    @if ($p->about_me)
        <section class="{{ $row }}">
            <h2 class="{{ $label }}">About</h2>
            <p class="tpl-serif whitespace-pre-line text-lg leading-relaxed text-slate-700">{{ $p->about_me }}</p>
        </section>
    @endif

    @if ($p->experiences->isNotEmpty())
        <section class="{{ $row }}">
            <h2 class="{{ $label }}">Experience</h2>
            <div class="min-w-0 space-y-6">
                @foreach ($p->experiences as $job)
                    <div>
                        <div class="flex flex-col gap-0.5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                            <h3 class="tpl-serif text-lg font-semibold text-slate-900">{{ $job->job_title }}</h3>
                            <span class="shrink-0 text-xs tabular-nums tracking-wide text-slate-500">{{ F::dateRange($job->start_date, $job->end_date) }}</span>
                        </div>
                        <p class="text-sm text-slate-500">{{ $job->company }}</p>
                        @if ($job->description) <p class="mt-2 text-[15px] leading-relaxed text-slate-600">{{ $job->description }}</p> @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($p->projects->isNotEmpty())
        <section class="{{ $row }}">
            <h2 class="{{ $label }}">Projects</h2>
            <div class="min-w-0 space-y-6">
                @foreach ($p->projects as $project)
                    <div>
                        <h3 class="tpl-serif text-lg font-semibold text-slate-900">{{ $project->project_name }}</h3>
                        @if ($project->description) <p class="mt-2 text-[15px] leading-relaxed text-slate-600">{{ $project->description }}</p> @endif
                        @if ($project->technologies)
                            <p class="mt-2 font-mono text-xs text-slate-500">{{ implode('  Â·  ', F::splitList($project->technologies)) }}</p>
                        @endif
                        @if ($url = F::safeUrl($project->project_link))
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="{{ $link }} mt-2">{{ F::displayUrl($url) }} &#8599;</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($p->education->isNotEmpty())
        <section class="{{ $row }}">
            <h2 class="{{ $label }}">Education</h2>
            <div class="min-w-0 space-y-6">
                @foreach ($p->education as $item)
                    <div>
                        <div class="flex flex-col gap-0.5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                            <h3 class="tpl-serif text-lg font-semibold text-slate-900">{{ $item->school }}</h3>
                            @if ($item->year) <span class="shrink-0 text-xs tabular-nums tracking-wide text-slate-500">{{ $item->year }}</span> @endif
                        </div>
                        @if ($item->degree) <p class="text-sm text-slate-500">{{ $item->degree }}</p> @endif
                        @if ($item->description) <p class="mt-2 text-[15px] leading-relaxed text-slate-600">{{ $item->description }}</p> @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($p->skills->isNotEmpty())
        <section class="{{ $row }}">
            <h2 class="{{ $label }}">Skills</h2>
            <ul class="flex flex-wrap gap-2">
                @foreach ($p->skills as $skill)
                    <li class="rounded-md border border-slate-200 px-2.5 py-1 text-sm text-slate-700">{{ $skill->skill_name }}</li>
                @endforeach
            </ul>
        </section>
    @endif

    <section class="{{ $row }}">
        <h2 class="{{ $label }}">Contact</h2>
        <dl class="grid gap-x-8 gap-y-3 text-sm sm:grid-cols-[6rem_1fr]">
            <dt class="text-slate-400">Email</dt><dd class="text-slate-900">{{ $p->email }}</dd>
            @if ($p->contact_number) <dt class="text-slate-400">Phone</dt><dd class="text-slate-900">{{ $p->contact_number }}</dd> @endif
            @if ($p->address) <dt class="text-slate-400">Location</dt><dd class="text-slate-900">{{ $p->address }}</dd> @endif
            @foreach ($p->socialLinks as $social)
                @if ($url = F::safeUrl($social->url))
                    <dt class="text-slate-400">{{ $social->platform }}</dt>
                    <dd><a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="{{ $link }}">{{ F::displayUrl($url) }} &#8599;</a></dd>
                @endif
            @endforeach
        </dl>
    </section>
</article>