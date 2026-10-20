@extends('layouts.app')

@section('title', 'Not allowed | Portfolio Generator')

@section('content')
<div class="mx-auto flex min-h-[60vh] max-w-lg flex-col items-center justify-center text-center">
    <p class="bg-linear-to-r from-indigo-600 via-fuchsia-500 to-amber-500 bg-clip-text text-8xl font-black tracking-tight text-transparent">403</p>
    <h1 class="mt-4 text-2xl font-bold text-gray-900">This portfolio isn't yours</h1>
    <p class="mt-2 text-gray-600">Only the person who created it can change it. You can still view it, or make your own.</p>
    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
        <a href="{{ url('/manage') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white hover:bg-indigo-500">My portfolios</a>
        <a href="{{ url('/') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-gray-300 px-6 text-base font-semibold text-gray-700 hover:bg-gray-50">Go home</a>
    </div>
</div>
@endsection