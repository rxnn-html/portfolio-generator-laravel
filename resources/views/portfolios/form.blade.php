@extends('layouts.app')

@section('title', ($portfolio ? 'Edit' : 'Create').' Portfolio | Portfolio Generator')

@section('content')
@php
    $isEditing = (bool) $portfolio;

    // old(...) = what the user typed before a validation error; otherwise the saved or blank data
    $state = [
        'f' => [
            'full_name'      => old('full_name', $portfolio?->full_name ?? \Illuminate\Support\Str::limit(is_string(request('name')) ? request('name') : '', 100, '')),
            'email'          => old('email', $portfolio?->email ?? ''),
            'contact_number' => old('contact_number', $portfolio?->contact_number ?? ''),
            'address'        => old('address', $portfolio?->address ?? ''),
            'about_me'       => old('about_me', $portfolio?->about_me ?? ''),
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
    $add = 'inline-flex min-h-9 items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 hover:bg-gray-50';
    $card = 'rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:p-5';
@endphp

<div class="mx-auto max-w-4xl">
    {{-- HEADER --}}
    <section class="relative mb-8 overflow-hidden rounded-3xl bg-linear-to-br from-indigo-600 via-violet-600 to-fuchsia-600 px-6 py-9 text-white sm:px-10 sm:py-12">
        <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-white/15 blur-3xl"></div>
        <p class="relative text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100">{{ $isEditing ? 'Editing' : 'New portfolio' }}</p>
        <h1 class="relative mt-2 text-3xl font-extrabold tracking-tight sm:text-5xl">
            {{ $isEditing ? 'Edit '.$portfolio->full_name."'s portfolio" : "Let's build your portfolio" }}
        </h1>
        <p class="relative mt-3 max-w-xl text-base text-indigo-100 sm:text-lg">
            {{ $isEditing ? 'Change anything you like, then save. Your template choice stays the same.' : 'Start with your name and email. Everything else is optional.' }}
        </p>
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
          x-on:submit="submitting = true"
          class="space-y-6">
        @csrf
        @if ($isEditing) @method('PUT') @endif

        {{-- 01 PERSONAL --}}
        <x-form-section number="1" title="Personal information" description="Only your name and email are required.">
            <div>
                <span class="mb-1.5 block text-sm font-medium text-gray-700">Profile picture</span>
                <div class="flex flex-col items-center gap-4 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 p-5 sm:flex-row">
                    <img x-show="preview" :src="preview" alt="Profile preview" class="h-24 w-24 shrink-0 rounded-full object-cover ring-2 ring-gray-200">
                    <div x-show="!preview" class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-gray-100 text-3xl text-gray-400">&#128247;</div>

                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-sm font-medium text-gray-900">JPG, PNG, or WebP &middot; up to 2 MB</p>
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

            <x-form-input label="About me" :rows="5" max="1500" model="f.about_me" name="'about_me'" placeholder="Write a short introduction: who you are and what you love to build..." />
        </x-form-section>

        {{-- 02 EDUCATION --}}
        <x-form-section number="2" title="Education" description="Empty rows are ignored, so you can leave one blank.">
            <template x-for="(row, i) in education" :key="i">
                <div class="{{ $card }}">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800" x-text="row.school || 'Education ' + (i + 1)"></h3>
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
        <x-form-section number="3" title="Skills" description="Type a skill and press Enter, or tap a suggestion.">
            <template x-for="(skill, i) in skills" :key="'h' + i">
                <input type="hidden" name="skills[]" :value="skill">
            </template>

            <div class="flex gap-2">
                <input type="text" x-model="draft" maxlength="300" placeholder="Type a skill and press Enter"
                       x-on:keydown.enter.prevent="addSkill(draft)"
                       x-on:keydown.comma.prevent="addSkill(draft)"
                       class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25">
                <button type="button" x-on:click="addSkill(draft)" class="{{ $add }} shrink-0 whitespace-nowrap">+ Add</button>
            </div>

            <ul class="flex flex-wrap gap-2">
                <template x-for="(skill, i) in skills" :key="'c' + i">
                    <li class="inline-flex items-center gap-1.5 rounded-full bg-indigo-500/10 py-1 pl-3.5 pr-1.5 text-sm font-medium text-indigo-700">
                        <span x-text="skill"></span>
                        <button type="button" x-on:click="removeSkill(i)" :aria-label="'Remove ' + skill"
                                class="flex h-5 w-5 items-center justify-center rounded-full hover:bg-indigo-500/20">&times;</button>
                    </li>
                </template>
            </ul>
            <p x-show="skills.length === 0" class="rounded-xl border border-dashed border-gray-300 p-4 text-center text-sm text-gray-500">No skills yet.</p>

            <div x-show="freeSuggestions.length > 0">
                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-gray-400">Quick add</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="name in freeSuggestions" :key="name">
                        <button type="button" x-on:click="addSkill(name)"
                                class="rounded-full border border-dashed border-gray-300 px-3 py-1 text-sm text-gray-600 hover:border-indigo-400 hover:text-indigo-600"
                                x-text="'+ ' + name"></button>
                    </template>
                </div>
            </div>
        </x-form-section>

        {{-- 04 PROJECTS --}}
        <x-form-section number="4" title="Projects" description="Show off what you have built.">
            <template x-for="(row, i) in projects" :key="i">
                <div class="{{ $card }}">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800" x-text="row.project_name || 'Project ' + (i + 1)"></h3>
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
        <x-form-section number="5" title="Work experience" description="Internships and part-time work count too.">
            <template x-for="(row, i) in experiences" :key="i">
                <div class="{{ $card }}">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800" x-text="row.job_title || 'Experience ' + (i + 1)"></h3>
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
        <x-form-section number="6" title="Social media and website links" description="Links must start with https:// (rows without a URL are ignored).">
            <template x-for="(row, i) in social_links" :key="i">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <label class="block sm:w-44">
                        <span class="mb-1.5 block text-sm font-medium text-gray-700">Platform</span>
                        <select x-model="row.platform" :name="'social_links['+i+'][platform]'"
                                class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25">
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

        {{-- STICKY SAVE BAR --}}
        <div class="sticky bottom-4 z-20 pt-2">
            <div class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-3 shadow-lg sm:flex-row sm:items-center sm:justify-between">
                <p class="hidden pl-2 text-sm text-gray-500 sm:block">
                    {{ $isEditing ? 'Your template choice stays the same.' : 'Next step: choose a template.' }}
                </p>
                <div class="flex flex-col-reverse gap-2 sm:flex-row">
                    <a href="{{ $isEditing ? url('/portfolio/'.$portfolio->id) : url('/') }}"
                       class="inline-flex min-h-12 items-center justify-center rounded-xl border border-gray-300 px-6 text-base font-semibold text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" :disabled="submitting"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-60">
                        <span x-text="submitting ? 'Saving...' : '{{ $isEditing ? 'Save changes' : 'Save and choose template' }}'"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection