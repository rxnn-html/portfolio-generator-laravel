@extends('layouts.app')

@section('title', 'Page not found | Portfolio Generator')

@section('content')
<div class="mx-auto flex min-h-[60vh] max-w-lg flex-col items-center justify-center text-center">
    <p class="bg-linear-to-r from-indigo-600 via-fuchsia-500 to-amber-500 bg-clip-text text-8xl font-black tracking-tight text-transparent">404</p>
    <h1 class="mt-4 text-2xl font-bold text-gray-900">We couldn't find that page</h1>
    <p class="mt-2 text-gray-600">The link may be wrong, or the portfolio may have been deleted.</p>
    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
        <a href="{{ url('/') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white hover:bg-indigo-500">Go home</a>
        <a href="{{ url('/manage') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-gray-300 px-6 text-base font-semibold text-gray-700 hover:bg-gray-50">My portfolios</a>
    </div>
</div>
@endsection