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
</head>
<body class="font-sans antialiased text-brand-ink bg-brand-warm min-h-screen flex flex-col overflow-x-hidden">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-brand-border/80">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1 pb-16 md:pb-0">
            @yield('content')
            @isset($slot){{ $slot }}@endisset
        </main>

        <footer class="hidden md:block bg-brand-navy border-t border-brand-navy-2 text-zinc-400">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex flex-col items-start">
                        <div class="flex items-center gap-2.5 mb-3">
                            <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-8 w-auto">
                            <span class="font-display font-bold text-white text-xl tracking-[0.05em] uppercase">BVS Untirta</span>
                        </div>
                        <p class="text-sm leading-6 text-zinc-400 max-w-xs">Bengkel & Variasi Motor & Mobil — solusi perawatan kendaraan terpercaya.</p>
                    </div>
                    <div class="flex flex-col items-start">
                        <div class="font-semibold text-white text-xs mb-4 uppercase tracking-[0.2em] font-display">Navigasi</div>
                        <ul class="space-y-2.5">
                            <li><a href="{{ route('home') }}" class="text-sm text-zinc-400 hover:text-brand-gold transition-colors">Beranda</a></li>
                            <li><a href="{{ route('products') }}" class="text-sm text-zinc-400 hover:text-brand-gold transition-colors">Produk</a></li>
                            <li><a href="{{ route('services') }}" class="text-sm text-zinc-400 hover:text-brand-gold transition-colors">Service</a></li>
                        </ul>
                    </div>
                    <div class="flex flex-col items-start">
                        <div class="font-semibold text-white text-xs mb-4 uppercase tracking-[0.2em] font-display">Jam Operasional</div>
                        <ul class="space-y-2.5 w-full">
                            <li class="grid grid-cols-[1fr_auto] gap-x-2 text-sm"><span class="text-zinc-400">Senin - Sabtu</span><span class="font-medium text-white">08:00 - 17:00</span></li>
                            <li class="grid grid-cols-[1fr_auto] gap-x-2 text-sm"><span class="text-zinc-400">Minggu & Hari Besar</span><span class="text-zinc-500">Tutup</span></li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-x-8 gap-y-1 mt-8 pt-4 text-xs text-zinc-500 tracking-wide">
                    <span>&copy; {{ date('Y') }} {{ config('app.name') }} — FKIP UNTIRTA. All rights reserved.</span>
                </div>
            </div>
        </footer>
    </div>

    @include('layouts.partials.bottom-nav')

    <x-toast />
    <x-product-bottom-sheet />

    @stack('scripts')
</body>
</html>
