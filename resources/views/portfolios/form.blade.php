@extends('layouts.app')

@section('title', ($portfolio ? 'Edit' : 'Create').' Portfolio | Portfolio Generator')

@section('content')
@php
    $isEditing = (bool) $portfolio;
    // The Home page banner can send a name: /create?name=Alex
    $prefill = \Illuminate\Support\Str::limit(is_string(request('name')) ? request('name') : '', 100, '');

    // old(...) = what the user typed before a validation error; otherwise the saved or blank data
    $state = [
        'f' => [
            'full_name'      => old('full_name', $portfolio?->full_name ?? $prefill) ?? '',
            'email'          => old('email', $portfolio?->email ?? '') ?? '',
            'contact_number' => old('contact_number', $portfolio?->contact_number ?? '') ?? '',
            'address'        => old('address', $portfolio?->address ?? '') ?? '',
            'about_me'       => old('about_me', $portfolio?->about_me ?? '') ?? '',
        ],
        'education'    => old('education', $form['education']),
        'skills'       => old('skills', $form['skills']),
        'projects'     => old('projects', $form['projects']),
        'experiences'  => old('experiences', $form['experiences']),
        'social_links' => old('social_links', $form['social_links']),
        'image'        => $portfolio?->profile_image,
    ];

    $platforms = \App\Http\Requests\PortfolioRequest::PLATFORMS;
    $remove = 'rounded-lg px-2.5 py-1.5 text-sm font-semibold text-red-600 hover:bg-red-50';
    $add = 'inline-flex min-h-10 items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 transition hover:-translate-y-0.5 hover:bg-gray-50';
    $card = 'rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:p-5';
    $input = 'w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25';
@endphp

<div class="mx-auto max-w-6xl">

    {{-- ================= HERO ================= --}}
    <section class="theme-fixed relative isolate mb-8 overflow-hidden rounded-3xl bg-slate-950 px-6 py-10 text-white sm:px-10 sm:py-14">
        <div aria-hidden="true" class="pointer-events-none absolute -left-20 -top-24 -z-10 h-80 w-80 rounded-full bg-indigo-600/40 blur-[100px] motion-safe:animate-drift"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 right-0 -z-10 h-80 w-80 rounded-full bg-fuchsia-600/30 blur-[100px] motion-safe:animate-drift-slow"></div>

        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">{{ $isEditing ? 'Editing' : 'Step 1 of 3 Â· New portfolio' }}</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-5xl">
            @if ($isEditing)
                Edit <span class="bg-linear-to-r from-cyan-300 via-sky-300 to-fuchsia-300 bg-clip-text text-transparent">{{ $portfolio->full_name }}'s</span> portfolio
            @else
                Let's build your <span class="bg-linear-to-r from-cyan-300 via-sky-300 to-fuchsia-300 bg-clip-text text-transparent">portfolio</span>
            @endif
        </h1>
        <p class="mt-3 max-w-xl text-base text-slate-300 sm:text-lg">
            {{ $isEditing ? 'Change anything you like, then save. Your template choice stays the same.' : 'Fill in what you can. Watch the card on the right come alive as you type.' }}
        </p>
        <ul class="mt-6 flex flex-wrap gap-2 text-xs font-semibold">
            <li class="rounded-full border border-white/15 bg-white/5 px-3 py-1.5"><span class="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-cyan-300"></span>About 5 minutes</li>
            <li class="rounded-full border border-white/15 bg-white/5 px-3 py-1.5"><span class="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-fuchsia-300"></span>Only name and email required</li>
            <li class="rounded-full border border-white/15 bg-white/5 px-3 py-1.5"><span class="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-amber-300"></span>Saved online</li>
        </ul>
    </section>

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div x-data x-init="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })" role="alert"
             class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" novalidate
          action="{{ $isEditing ? route('portfolios.update', $portfolio) : route('portfolios.store') }}"
          x-data="portfolioForm({{ Js::from($state) }})"
          x-on:submit="submitting = true">
        @csrf
        @if ($isEditing) @method('PUT') @endif

        @include('portfolios.partials.mobile-bar')

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-start">

            {{-- ============ FORM SECTIONS ============ --}}
            <div class="space-y-6">

                {{-- 01 PERSONAL --}}
                <x-form-section section="personal" number="1" title="Personal information" description="Only your name and email are required.">

                    <div>
                        <span class="mb-1.5 block text-sm font-medium text-gray-700">Profile picture</span>
                        <div class="flex flex-col items-center gap-4 rounded-2xl border-2 border-dashed p-5 transition sm:flex-row"
                             :class="drag ? 'border-indigo-500 bg-indigo-500/10' : 'border-gray-300 bg-gray-50'"
                             x-on:dragover.prevent="drag = true" x-on:dragleave.prevent="drag = false" x-on:drop.prevent="dropImage($event)">
                            <img x-show="preview" :src="preview" alt="Profile preview" class="h-24 w-24 shrink-0 rounded-full object-cover ring-2 ring-gray-200">
                            <div x-show="!preview" class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-gray-100 text-3xl text-gray-400">&#128247;</div>

                            <div class="flex-1 text-center sm:text-left">
                                <p class="text-sm font-semibold text-gray-900">Drag and drop a photo here</p>
                                <p class="mt-0.5 text-xs text-gray-500">or choose one &middot; JPG, PNG, or WebP &middot; up to 2 MB</p>
                                <div class="mt-3 flex flex-wrap justify-center gap-2 sm:justify-start">
                                    <button type="button" x-on:click="$refs.file.click()" class="{{ $add }}">
                                        <span x-text="preview ? 'Replace photo' : 'Choose photo'"></span>
                                    </button>
                                    <button type="button" x-show="preview" x-on:click="clearImage()" class="{{ $remove }}">Remove</button>
                                </div>
                            </div>

                            <input type="file" name="image" x-ref="file" class="hidden"
                                   accept="image/jpeg,image/png,image/webp" x-on:change="pickImage($event)">
                            <input type="hidden" name="remove_image" :value="removeImage ? 1 : 0">
                        </div>
                        <p x-show="imageError" x-text="imageError" role="alert" class="mt-2 text-sm text-red-600"></p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form-input label="Full name" required model="f.full_name" name="'full_name'" max="100" placeholder="Juan Dela Cruz" autocomplete="name" />
                        <x-form-input label="Email" required type="email" model="f.email" name="'email'" max="254" placeholder="juan@example.com" autocomplete="email" />
                        <x-form-input label="Contact number" model="f.contact_number" name="'contact_number'" max="20" placeholder="+63 912 345 6789" autocomplete="tel" />
                        <x-form-input label="Address" model="f.address" name="'address'" max="200" placeholder="Cebu City, Philippines" />
                    </div>

                    <div class="space-y-3">
                        <x-form-input label="About me" :rows="5" max="1500" model="f.about_me" name="'about_me'" placeholder="Write a short introduction: who you are and what you love to build..." />

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Need a start?</span>
                            <template x-for="key in starterKeys" :key="key">
                                <button type="button" x-on:click="useStarter(key)" x-text="key"
                                        class="rounded-full border border-dashed border-gray-300 px-3 py-1 text-sm capitalize text-gray-600 transition hover:border-indigo-400 hover:text-indigo-600"></button>
                            </template>
                        </div>

                        <details class="group rounded-xl border border-gray-200 px-4 py-3 text-sm">
                            <summary class="cursor-pointer list-none font-semibold text-gray-700 [&::-webkit-details-marker]:hidden">
                                Writing tips <span class="ml-1 inline-block text-gray-400 transition group-open:rotate-180">&#9662;</span>
                            </summary>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-gray-600">
                                <li>Start with who you are and what you do.</li>
                                <li>Mention two or three things you are good at.</li>
                                <li>End with what you are looking for next.</li>
                            </ul>
                        </details>
                    </div>
                </x-form-section>

                {{-- 02 EDUCATION --}}
                <x-form-section section="education" number="2" title="Education" description="Empty rows are ignored, so you can leave one blank.">
                    <template x-for="(row, i) in education" :key="i">
                        <div class="{{ $card }}">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <h3 class="truncate text-sm font-semibold text-gray-800" x-text="row.school || 'Education ' + (i + 1)"></h3>
                                <button type="button" x-on:click="remove('education', i)" class="{{ $remove }}">Remove</button>
                            </div>
                            <div class="space-y-5">
                                <div class="grid gap-5 sm:grid-cols-2">
                                    <x-form-input label="School" required model="row.school" name="'education['+i+'][school]'" max="150" />
                                    <x-form-input label="Degree / Course" model="row.degree" name="'education['+i+'][degree]'" max="150" />
                                    <x-form-input label="Year" model="row.year" name="'education['+i+'][year]'" max="30" placeholder="2022 - 2026" />
                                </div>
                                <x-form-input label="Description" :rows="3" max="1000" model="row.description" name="'education['+i+'][description]'" />
                            </div>
                        </div>
                    </template>
                    <button type="button" x-on:click="add('education')" class="{{ $add }}">+ Add education</button>
                </x-form-section>

                {{-- 03 SKILLS --}}
                <x-form-section section="skills" number="3" title="Skills" description="Type a skill and press Enter, or tap a suggestion.">
                    <template x-for="(skill, i) in skills" :key="'h' + i">
                        <input type="hidden" name="skills[]" :value="skill">
                    </template>

                    <div class="flex gap-2">
                        <input type="text" x-model="draft" maxlength="300" placeholder="Type a skill and press Enter" aria-label="Add a skill"
                               x-on:keydown.enter.prevent="addSkill(draft)"
                               x-on:keydown.comma.prevent="addSkill(draft)"
                               class="{{ $input }}">
                        <button type="button" x-on:click="addSkill(draft)" class="{{ $add }} shrink-0 whitespace-nowrap">+ Add</button>
                    </div>
                    <p class="-mt-2 text-xs text-gray-500">Tip: paste several at once, separated by commas. (<span x-text="skills.length"></span>/30)</p>

                    <ul class="flex flex-wrap gap-2">
                        <template x-for="(skill, i) in skills" :key="'c' + i">
                            <li class="inline-flex items-center gap-1.5 rounded-full bg-indigo-500/10 py-1 pl-3.5 pr-1.5 text-sm font-medium text-indigo-700">
                                <span x-text="skill"></span>
                                <button type="button" x-on:click="removeSkill(i)" :aria-label="'Remove ' + skill"
                                        class="flex h-5 w-5 items-center justify-center rounded-full hover:bg-indigo-500/20">&times;</button>
                            </li>
                        </template>
                    </ul>
                    <p x-show="skills.length === 0" class="rounded-xl border border-dashed border-gray-300 p-4 text-center text-sm text-gray-500">No skills yet. Type one above or tap a suggestion.</p>

                    <div x-show="freeSuggestions.length > 0">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-gray-400">Quick add</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="name in freeSuggestions" :key="name">
                                <button type="button" x-on:click="addSkill(name)" x-text="'+ ' + name"
                                        class="rounded-full border border-dashed border-gray-300 px-3 py-1 text-sm text-gray-600 transition hover:border-indigo-400 hover:text-indigo-600"></button>
                            </template>
                        </div>
                    </div>
                </x-form-section>

                {{-- 04 PROJECTS --}}
                <x-form-section section="projects" number="4" title="Projects" description="Show off what you have built.">
                    <template x-for="(row, i) in projects" :key="i">
                        <div class="{{ $card }}">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <h3 class="truncate text-sm font-semibold text-gray-800" x-text="row.project_name || 'Project ' + (i + 1)"></h3>
                                <button type="button" x-on:click="remove('projects', i)" class="{{ $remove }}">Remove</button>
                            </div>
                            <div class="space-y-5">
                                <div class="grid gap-5 sm:grid-cols-2">
                                    <x-form-input label="Project name" required model="row.project_name" name="'projects['+i+'][project_name]'" max="150" />
                                    <x-form-input label="Technologies used" model="row.technologies" name="'projects['+i+'][technologies]'" max="200" placeholder="Laravel, MySQL, Tailwind" />
                                </div>
                                <x-form-input label="Project link" model="row.project_link" name="'projects['+i+'][project_link]'" max="500" placeholder="https://github.com/you/project" hint="Must start with https://" />
                                <x-form-input label="Description" :rows="3" max="1000" model="row.description" name="'projects['+i+'][description]'" />
                            </div>
                        </div>
                    </template>
                    <button type="button" x-on:click="add('projects')" class="{{ $add }}">+ Add project</button>
                </x-form-section>

                {{-- 05 EXPERIENCE --}}
                <x-form-section section="experience" number="5" title="Work experience" description="Internships and part-time work count too.">
                    <template x-for="(row, i) in experiences" :key="i">
                        <div class="{{ $card }}">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <h3 class="truncate text-sm font-semibold text-gray-800" x-text="row.job_title || 'Experience ' + (i + 1)"></h3>
                                <button type="button" x-on:click="remove('experiences', i)" class="{{ $remove }}">Remove</button>
                            </div>
                            <div class="space-y-5">
                                <div class="grid gap-5 sm:grid-cols-2">
                                    <x-form-input label="Job title" required model="row.job_title" name="'experiences['+i+'][job_title]'" max="150" />
                                    <x-form-input label="Company" required model="row.company" name="'experiences['+i+'][company]'" max="150" />
                                    <x-form-input label="Start date" model="row.start_date" name="'experiences['+i+'][start_date]'" max="30" placeholder="Jan 2024" />
                                    <x-form-input label="End date" model="row.end_date" name="'experiences['+i+'][end_date]'" max="30" placeholder="Present" />
                                </div>
                                <x-form-input label="Description" :rows="3" max="1000" model="row.description" name="'experiences['+i+'][description]'" />
                            </div>
                        </div>
                    </template>
                    <button type="button" x-on:click="add('experiences')" class="{{ $add }}">+ Add experience</button>
                </x-form-section>

                {{-- 06 LINKS --}}
                <x-form-section section="links" number="6" title="Social media and website links" description="Links must start with https:// (rows without a URL are ignored).">
                    <template x-for="(row, i) in social_links" :key="i">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                            <label class="block sm:w-44">
                                <span class="mb-1.5 block text-sm font-medium text-gray-700">Platform</span>
                                <select x-model="row.platform" :name="'social_links['+i+'][platform]'" class="{{ $input }}">
                                    @foreach ($platforms as $platform)
                                        <option value="{{ $platform }}">{{ $platform }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <div class="flex-1">
                                <x-form-input label="URL" model="row.url" name="'social_links['+i+'][url]'" max="500" placeholder="https://github.com/yourname" />
                            </div>
                            <button type="button" x-on:click="remove('social_links', i)" class="{{ $remove }}">Remove</button>
                        </div>
                    </template>
                    <button type="button" x-on:click="add('social_links')" class="{{ $add }}">+ Add link</button>
                </x-form-section>
            </div>

            {{-- ============ SIDE PANEL (desktop) ============ --}}
            <aside class="hidden space-y-4 lg:sticky lg:top-24 lg:block lg:max-h-[calc(100vh-7rem)] lg:overflow-y-auto lg:pb-2">
                @include('portfolios.partials.live-card')
                @include('portfolios.partials.progress-card')
            </aside>
        </div>

        {{-- ============ STICKY SAVE BAR ============ --}}
        <div class="sticky bottom-4 z-20 mt-8">
            <div class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-3 shadow-lg sm:flex-row sm:items-center sm:justify-between">
                <p class="hidden items-center gap-2 pl-2 text-sm text-gray-500 sm:flex">
                    <span class="font-bold text-indigo-600 dark:text-indigo-300" x-text="percent + '%'"></span> complete &middot;
                    {{ $isEditing ? 'your template choice stays the same.' : 'next step: choose a template.' }}
                </p>
                <div class="flex flex-col-reverse gap-2 sm:flex-row">
                    <a href="{{ $isEditing ? url('/portfolio/'.$portfolio->id) : url('/') }}"
                       class="inline-flex min-h-12 items-center justify-center rounded-xl border border-gray-300 px-6 text-base font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" :disabled="submitting"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60">
                        <span x-text="submitting ? 'Saving...' : '{{ $isEditing ? 'Save changes' : 'Save and choose template' }}'"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection