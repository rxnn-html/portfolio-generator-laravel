@extends('layouts.app')

@section('title', 'Choose a Template | Portfolio Generator')

@section('content')
@php
    $options = [
        'simple'   => ['name' => 'Simple',   'text' => 'Clean and minimal with a white background and a traditional layout. Easy to read.'],
        'modern'   => ['name' => 'Modern',   'text' => 'Rounded cards, skill badges, project cards, and a sticky navigation bar.'],
        'creative' => ['name' => 'Creative', 'text' => 'A bold dark design with a visual timeline and a unique layout that stands out.'],
    ];
@endphp

<div class="mx-auto max-w-6xl">
    <section class="relative mb-8 overflow-hidden rounded-3xl bg-linear-to-br from-indigo-600 via-violet-600 to-fuchsia-600 px-6 py-9 text-white sm:px-10 sm:py-12">
        <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-white/15 blur-3xl"></div>
        <p class="relative text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100">Step 2 of 3</p>
        <h1 class="relative mt-2 text-3xl font-extrabold tracking-tight sm:text-5xl">Choose a template</h1>
        <p class="relative mt-3 max-w-xl text-base text-indigo-100 sm:text-lg">
            {{ $portfolio ? "Pick the design for {$portfolio->full_name}'s portfolio. You can change it any time." : 'Pick a design. You can change it any time.' }}
        </p>
    </section>

    @include('partials.stepper', ['current' => 1])

    @unless ($portfolio)
        <div role="status" class="mb-8 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-200">
            <p class="font-semibold">No portfolio selected</p>
            <p class="mt-1">Create a portfolio first, or choose an existing one to apply a template.</p>
            <a href="{{ url('/create') }}" class="mt-3 inline-block font-semibold underline">Create portfolio</a>
            &middot; <a href="{{ url('/manage') }}" class="font-semibold underline">My portfolios</a>
        </div>
    @endunless

    <div class="grid gap-6 md:grid-cols-3">
        @foreach ($options as $key => $option)
            @php $current = $portfolio && $portfolio->template === $key; @endphp
            <div class="flex flex-col overflow-hidden rounded-2xl border bg-white shadow-sm {{ $current ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-200' }}">
                <div class="h-44 overflow-hidden border-b border-gray-200">
                    <x-template-preview :type="$key" />
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $option['name'] }}</h2>
                        @if ($current)
                            <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Current</span>
                        @endif
                    </div>
                    <p class="mt-2 flex-1 text-sm text-gray-600">{{ $option['text'] }}</p>

                    @if ($portfolio)
                        <form method="POST" action="{{ route('portfolios.template', $portfolio) }}" class="mt-5">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="template" value="{{ $key }}">
                            <input type="hidden" name="from" value="select">
                            <button type="submit" class="min-h-11 w-full rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white hover:bg-indigo-500">
                                Use This Template
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="mt-5 min-h-11 w-full cursor-not-allowed rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white opacity-50">
                            Use This Template
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection