@extends('layouts.app')

@section('title', 'Sign in | Portfolio Generator')

@section('content')
<div class="mx-auto flex min-h-[65vh] max-w-md flex-col justify-center">
    <div class="theme-fixed relative isolate overflow-hidden rounded-3xl bg-slate-950 p-8 text-white shadow-2xl sm:p-10">
        <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-16 -z-10 h-56 w-56 rounded-full bg-indigo-600/40 blur-[90px]"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-16 -left-16 -z-10 h-56 w-56 rounded-full bg-fuchsia-600/30 blur-[90px]"></div>

        <img src="{{ asset('favicon.svg') }}?v=2" alt="" class="h-12 w-12">
        <h1 class="mt-5 text-3xl font-extrabold tracking-tight">Sign in</h1>
        <p class="mt-2 text-slate-300">Sign in so only <strong class="text-white">you</strong> can edit your portfolios.</p>

        @if ($errors->any())
            <div role="alert" class="mt-5 rounded-xl border border-red-400/40 bg-red-500/10 p-3 text-sm text-red-200">{{ $errors->first() }}</div>
        @endif

        <a href="{{ route('auth.google') }}"
           class="mt-7 inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-xl bg-slate-50 px-5 text-base font-semibold text-slate-900 shadow-lg transition hover:-translate-y-0.5 hover:bg-white">
            <svg width="20" height="20" viewBox="0 0 48 48" aria-hidden="true">
                <path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.6-.4-3.9z"/>
                <path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.8 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/>
                <path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/>
                <path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C37 39.2 44 34 44 24c0-1.3-.1-2.6-.4-3.9z"/>
            </svg>
            Continue with Google
        </a>

        <p class="mt-5 text-xs leading-relaxed text-slate-400">We only receive your name, email, and profile picture. We never see your Google password.</p>
    </div>
</div>
@endsection