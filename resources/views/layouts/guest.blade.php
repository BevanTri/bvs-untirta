<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BVS Untirta') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=oswald:500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/webp" href="{{ asset('images/logo-untirta.webp') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app-DhU0CEjf.css') }}">
    <script type="module" src="{{ asset('build/assets/app-B9qO1Jfl.js') }}"></script>
</head>
<body class="font-sans antialiased bg-brand-navy min-h-screen flex flex-col overflow-x-hidden">
    <div class="flex-1 flex flex-col sm:justify-center items-center px-4 py-12">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-8">
            <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-9 w-auto">
            <span class="font-display font-bold text-white text-2xl tracking-[0.05em] uppercase">BVS Untirta</span>
        </a>
        <div class="w-full sm:max-w-md">
            <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
        <p class="text-zinc-500 text-xs mt-6 tracking-wide">&copy; {{ date('Y') }} Bengkel Vokasi &amp; Sains Untirta</p>
    </div>
</body>
</html>
