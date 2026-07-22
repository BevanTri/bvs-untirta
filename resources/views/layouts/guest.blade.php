<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BVS Untirta') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=oswald:500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/webp" href="{{ asset('images/logo-untirta.webp') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app-CgeNcYon.css') }}">
    <script type="module" src="{{ asset('build/assets/app-B9qO1Jfl.js') }}"></script>
    <style>
        .auth-bg { background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%); }
        .auth-glow { position: absolute; width: 400px; height: 400px; border-radius: 50%; filter: blur(120px); opacity: 0.08; pointer-events: none; }
    </style>
</head>
<body class="font-sans antialiased auth-bg min-h-screen flex flex-col overflow-x-hidden">
    <div class="flex-1 flex flex-col items-center justify-center px-4 py-12 relative">
        <div class="auth-glow bg-brand-gold" style="top:-100px;right:-100px"></div>
        <div class="auth-glow bg-brand-blue" style="bottom:-100px;left:-100px"></div>

        <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-8 relative">
            <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-9 w-auto">
            <span class="font-display font-bold text-white text-2xl tracking-[0.05em] uppercase">BVS Untirta</span>
        </a>

        <div class="w-full sm:max-w-md relative">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-level-4 p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>

        <p class="text-zinc-500 text-xs mt-8 tracking-wide relative">&copy; {{ date('Y') }} Bengkel Vokasi &amp; Sains Untirta</p>
    </div>

    <x-toast />
</body>
</html>
