@extends('layouts.app')

@section('title', $portfolio->full_name.' | Portfolio Generator')

@section('content')
@use('App\Support\Format', 'F')
@php
    $template = in_array($portfolio->template, \App\Models\Portfolio::TEMPLATES) ? $portfolio->template : 'simple';

    $missing = array_values(array_filter([
        ! $portfolio->profile_image ? 'a profile photo' : null,
        ! $portfolio->about_me ? 'an introduction' : null,
        $portfolio->skills->isEmpty() ? 'your skills' : null,
        $portfolio->projects->isEmpty() ? 'a project' : null,
        $portfolio->experiences->isEmpty() ? 'work experience' : null,
        $portfolio->education->isEmpty() ? 'your education' : null,
        $portfolio->socialLinks->isEmpty() ? 'social links' : null,
    ]));
    $percent = (int) round((7 - count($missing)) / 7 * 100);
    $updated = $portfolio->updated_at?->timezone('Asia/Manila')->format('M j, Y');

    $btn = 'inline-flex min-h-11 items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 hover:bg-gray-50';
@endphp

<x-toast :notice="request('notice')" />

<div>
    {{-- HEADER (hidden when printing) --}}
    <div class="mb-6 space-y-4 print:hidden">
        <a href="{{ url('/manage') }}" class="inline-flex min-h-9 items-center text-sm font-semibold text-gray-600 hover:text-gray-900">&larr; Back to Manage Portfolios</a>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="h-20 bg-linear-to-r from-indigo-600 via-violet-600 to-fuchsia-600 sm:h-24"></div>

            <div class="px-5 pb-6 sm:px-7">
                <div class="-mt-10 flex items-end gap-4 sm:-mt-12">
                    @if ($portfolio->profile_image)
                        <img src="{{ $portfolio->profile_image }}" alt="{{ $portfolio->full_name }}"
                             class="h-20 w-20 shrink-0 rounded-full object-cover ring-4 ring-white dark:ring-[#131a2b] sm:h-24 sm:w-24">
                    @else
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white ring-4 ring-white dark:ring-[#131a2b] sm:h-24 sm:w-24 sm:text-3xl">
                            {{ F::initials($portfolio->full_name) }}
                        </div>
                    @endif
                    <div class="min-w-0 pb-1">
                        <h1 class="truncate text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">{{ $portfolio->full_name }}</h1>
                        <div class="mt-1.5 flex flex-wrap gap-2">
                            <span class="rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium capitalize text-indigo-700">{{ $template }} template</span>
                            @if ($updated)
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">Updated {{ $updated }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex items-center gap-3">
                    <div class="h-2 w-full max-w-48 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-linear-to-r from-indigo-500 to-fuchsia-500" style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="text-xs font-medium text-gray-500">{{ $percent }}% complete</span>
                </div>

                <div class="mt-6 flex flex-col gap-5 border-t border-gray-200 pt-5 lg:flex-row lg:items-center lg:justify-between">
                    {{-- Template switcher: one form, three buttons --}}
                    <div>
                        <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-gray-400">Change template</p>
                        <form method="POST" action="{{ route('portfolios.template', $portfolio) }}"
                              class="inline-flex rounded-xl bg-gray-100 p-1" role="radiogroup" aria-label="Portfolio template">
                            @csrf
                            @method('PATCH')
                            @foreach (\App\Models\Portfolio::TEMPLATES as $key)
                                <button type="submit" name="template" value="{{ $key }}" role="radio" aria-checked="{{ $key === $template ? 'true' : 'false' }}"
                                        class="min-h-9 rounded-lg px-4 text-sm font-semibold capitalize transition {{ $key === $template ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:text-gray-900' }}">
                                    {{ $key }}
                                </button>
                            @endforeach
                        </form>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('portfolios.edit', $portfolio) }}"
                           class="inline-flex min-h-11 items-center justify-center whitespace-nowrap rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Edit Portfolio</a>

                        <button type="button" class="{{ $btn }}" x-data="{ copied: false }"
                                x-on:click="navigator.clipboard.writeText(location.origin + location.pathname).then(() => { copied = true; setTimeout(() => copied = false, 2000) })">
                            <span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span>
                        </button>

                        <button type="button" class="{{ $btn }}" x-data="{ t: {{ Js::from($portfolio->full_name) }} }"
                                x-on:click="var o = document.title; document.title = t + ' - Portfolio'; window.print(); document.title = o">
                            Print / Save as PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ $errors->first() }}</div>
        @endif

        @if (count($missing) > 0)
            <div role="status" class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4 text-sm text-indigo-800 dark:border-indigo-400/30 dark:text-indigo-200">
                <p class="font-semibold">Make it shine</p>
                <p class="mt-1">Add {{ implode(', ', array_slice($missing, 0, 3)) }} to make your portfolio stand out.
                    <a href="{{ route('portfolios.edit', $portfolio) }}" class="font-semibold underline">Edit portfolio</a></p>
            </div>
        @endif
    </div>

    {{-- PREVIEW FRAME --}}
    <div class="rounded-3xl border border-gray-200 bg-gray-100 p-2 sm:p-3 print:border-0 print:bg-transparent print:p-0">
        <div class="mb-2 flex items-center gap-2 px-2 pt-1 print:hidden">
            <span class="h-3 w-3 rounded-full bg-red-400"></span>
            <span class="h-3 w-3 rounded-full bg-amber-400"></span>
            <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
            <span class="ml-3 truncate rounded-md bg-white px-3 py-1 text-xs capitalize text-gray-500">Live preview &middot; {{ $template }} template</span>
        </div>

        {{-- Only this part appears in the PDF. The database value picks the template file. --}}
        <div class="print-area theme-fixed" data-theme="{{ $template }}">
            @include('portfolio-templates.'.$template, ['portfolio' => $portfolio])
        </div>
    </div>
</div>
    <div class="mt-8 flex flex-col gap-3 rounded-2xl border border-red-200 p-5 sm:flex-row sm:items-center sm:justify-between print:hidden">
        <div>
            <p class="font-semibold text-gray-900">Delete this portfolio</p>
            <p class="text-sm text-gray-500">Removes the portfolio, its details, and its picture permanently.</p>
        </div>
        <button type="button" x-data
                x-on:click="$dispatch('confirm-delete', { action: '{{ route('portfolios.destroy', $portfolio) }}', name: {{ Js::from($portfolio->full_name) }} })"
                class="inline-flex min-h-11 items-center justify-center whitespace-nowrap rounded-xl border border-red-300 px-4 text-sm font-semibold text-red-600 hover:bg-red-50">Delete Portfolio</button>
    </div>
    <x-delete-dialog />
@endsection