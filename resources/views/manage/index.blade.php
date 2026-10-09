@extends('layouts.app')

@section('title', 'My Portfolios | Portfolio Generator')

@section('content')
@php
    $stats = [
        ['Total', count($items)],
        ['Simple', $counts['simple'] ?? 0],
        ['Modern', $counts['modern'] ?? 0],
        ['Creative', $counts['creative'] ?? 0],
    ];
    $outline = 'inline-flex min-h-9 items-center justify-center whitespace-nowrap rounded-xl border border-gray-300 bg-white px-3 text-sm font-semibold text-gray-700 hover:bg-gray-50';
@endphp

<x-toast :notice="request('notice')" />

<div class="mx-auto max-w-6xl">

    {{-- HEADER --}}
    <section class="relative mb-8 overflow-hidden rounded-3xl bg-linear-to-br from-indigo-600 via-violet-600 to-fuchsia-600 px-6 py-9 text-white sm:px-10 sm:py-12">
        <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-white/15 blur-3xl"></div>
        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100">Dashboard</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-5xl">My Portfolios</h1>
                <p class="mt-2 max-w-md text-indigo-100">View, edit, or delete your saved portfolios.</p>
            </div>
            <a href="{{ url('/create') }}" class="inline-flex min-h-12 shrink-0 items-center justify-center rounded-xl bg-slate-50 px-6 text-base font-semibold text-slate-900 hover:bg-white">+ New Portfolio</a>
        </div>
        <dl class="relative mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ($stats as [$name, $value])
                <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                    <dd class="text-2xl font-extrabold">{{ $value }}</dd>
                    <dt class="text-xs uppercase tracking-wider text-indigo-100">{{ $name }}</dt>
                </div>
            @endforeach
        </dl>
    </section>

    @if ($error)
        <div role="alert" class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Could not load your portfolios</p>
            <p class="mt-1">Please refresh the page and try again.</p>
        </div>
    @elseif ($errors->any())
        <div role="alert" class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ $errors->first() }}</div>
    @endif

    {{-- EMPTY STATE --}}
    @if (! $error && count($items) === 0)
        <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-xl font-bold text-gray-900">No portfolios yet</p>
            <p class="mt-2 text-gray-600">Create your first one. It only takes a few minutes.</p>
            <a href="{{ url('/create') }}" class="mt-6 inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white hover:bg-indigo-500">Create Portfolio</a>
        </div>
    @endif

    {{-- LIST: search and filter run in the browser with Alpine --}}
    @if (! $error && count($items) > 0)
        <div x-data="{
                q: '', filter: 'all', items: {{ Js::from($items) }},
                filters: ['all', 'simple', 'modern', 'creative'],
                covers: { simple: 'from-slate-500 to-slate-700', modern: 'from-indigo-600 to-violet-600', creative: 'from-fuchsia-600 to-amber-500' },
                get visible() {
                    const t = this.q.trim().toLowerCase();
                    return this.items.filter(p =>
                        (this.filter === 'all' || p.template === this.filter) &&
                        (!t || p.full_name.toLowerCase().includes(t) || p.email.toLowerCase().includes(t)));
                }
            }">

            <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <input type="search" x-model="q" placeholder="Search by name or email" aria-label="Search portfolios"
                       class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25 lg:w-96">

                <div class="flex flex-wrap gap-1 rounded-xl bg-gray-100 p-1" role="radiogroup" aria-label="Filter by template">
                    <template x-for="name in filters" :key="name">
                        <button type="button" role="radio" :aria-checked="filter === name" x-on:click="filter = name"
                                class="min-h-9 rounded-lg px-4 text-sm font-semibold capitalize transition"
                                :class="filter === name ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:text-gray-900'"
                                x-text="name"></button>
                    </template>
                </div>
            </div>

            <div x-show="visible.length === 0" class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <p class="text-lg font-semibold text-gray-900">No matches found</p>
                <p class="mt-1 text-sm text-gray-600">Try a different name or template filter.</p>
                <button type="button" x-on:click="q = ''; filter = 'all'" class="{{ $outline }} mt-5">Clear search</button>
            </div>

            <ul class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <template x-for="p in visible" :key="p.id">
                    <li class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="h-20 bg-linear-to-r" :class="covers[p.template]"></div>

                        <div class="flex flex-1 flex-col px-5 pb-5">
                            <div class="-mt-9">
                                <img x-show="p.profile_image" :src="p.profile_image" :alt="p.full_name"
                                     class="h-[4.5rem] w-[4.5rem] rounded-full object-cover ring-4 ring-white dark:ring-[#131a2b]">
                                <div x-show="!p.profile_image" x-text="p.initials"
                                     class="flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-full bg-indigo-600 text-xl font-bold text-white ring-4 ring-white dark:ring-[#131a2b]"></div>
                            </div>

                            <h2 class="mt-3 truncate text-lg font-bold text-gray-900" x-text="p.full_name"></h2>
                            <p class="truncate text-sm text-gray-500" x-text="p.email"></p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium capitalize text-indigo-700" x-text="p.template"></span>
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700" x-text="'Created ' + p.created"></span>
                            </div>

                            <div class="mt-auto flex flex-wrap items-center gap-2 pt-5">
                                <a :href="'{{ url('/portfolio') }}/' + p.id"
                                   class="inline-flex min-h-9 items-center justify-center rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white hover:bg-indigo-500">View</a>
                                <a :href="'{{ url('/portfolios') }}/' + p.id + '/edit'" class="{{ $outline }}">Edit</a>
                                <button type="button"
                                        x-on:click="$dispatch('confirm-delete', { action: '{{ url('/portfolios') }}/' + p.id, name: p.full_name })"
                                        class="inline-flex min-h-9 items-center justify-center rounded-xl border border-red-300 px-3 text-sm font-semibold text-red-600 hover:bg-red-50">Delete</button>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    @endif
</div>

<x-delete-dialog />
@endsection