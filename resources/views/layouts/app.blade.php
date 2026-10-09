<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portfolio Generator')</title>

    {{-- Runs BEFORE the page paints, so dark mode has no white flash. --}}
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('theme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (stored === 'dark' || (!stored && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <meta name="theme-color" content="#4f46e5">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen antialiased">
    @include('partials.navbar')

    <main class="mx-auto max-w-6xl px-4 py-8 print:max-w-none print:p-0">
        @yield('content')
    </main>

    <footer class="mx-auto max-w-6xl px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400 print:hidden">
        <p>Portfolio Generator &middot; Built with Laravel, Blade, Tailwind CSS &amp; Supabase</p>
        <p class="mt-1">No copyright &middot; Free to use and share</p>
    </footer>
</body>
</html>