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
    <link rel="stylesheet" href="{{ asset('build/assets/app-BZ5sQYsh.css') }}">
    <script type="module" src="{{ asset('build/assets/app-B9qO1Jfl.js') }}"></script>
</head>
    <body class="font-sans antialiased text-brand-ink bg-brand-warm min-h-screen flex flex-col overflow-x-hidden">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white border-b border-brand-border">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 pb-16 md:pb-0">
                {{ $slot }}
            </main>

            <footer class="bg-brand-navy border-t border-brand-navy-2 text-zinc-400">
                <div class="max-w-7xl mx-auto px-4 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <div class="flex items-center gap-2.5 mb-3">
                                <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-7 w-auto">
                                <span class="font-display font-bold text-white text-xl tracking-[0.05em] uppercase">BVS Untirta</span>
                            </div>
                            <p class="text-sm leading-relaxed text-zinc-400">Bengkel Vokasi & Sains — solusi perawatan kendaraan terpercaya di lingkungan Universitas Sultan Ageng Tirtayasa.</p>
                        </div>
                        <div>
                            <div class="font-semibold text-white text-sm mb-4 uppercase tracking-[0.15em] font-display">Navigasi</div>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('home') }}" class="text-zinc-400 hover:text-brand-gold transition-colors">Beranda</a></li>
                                <li><a href="{{ route('products') }}" class="text-zinc-400 hover:text-brand-gold transition-colors">Produk</a></li>
                                <li><a href="{{ route('services') }}" class="text-zinc-400 hover:text-brand-gold transition-colors">Service</a></li>
                            </ul>
                        </div>
                        <div>
                            <div class="font-semibold text-white text-sm mb-4 uppercase tracking-[0.15em] font-display">Jam Operasional</div>
                            <p class="text-sm text-zinc-400">Senin - Sabtu: 08:00 - 17:00</p>
                            <p class="text-sm text-zinc-400">Minggu & Hari Besar: Tutup</p>
                        </div>
                    </div>
                    <div class="h-px bg-gradient-to-r from-transparent via-brand-gold/30 to-transparent mt-10 mb-6"></div>
                    <div class="text-center text-xs text-zinc-500 tracking-wide">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>

        @include('layouts.partials.bottom-nav')

        <div id="toast" class="fixed bottom-20 right-4 md:bottom-6 md:right-6 bg-brand-navy border border-brand-navy-2 text-white px-5 py-3 text-sm font-medium z-50 rounded-lg shadow-lg transition-all duration-300 translate-y-4 opacity-0 pointer-events-none">toast</div>

        <script>
        (function() {
            const t = document.getElementById('toast');
            @if(session('toast'))
            t.textContent = '{{ session('toast') }}';
            t.classList.remove('translate-y-4', 'opacity-0', 'pointer-events-none');
            t.classList.add('translate-y-0', 'opacity-100');
            setTimeout(() => { t.classList.add('translate-y-4', 'opacity-0', 'pointer-events-none'); t.classList.remove('translate-y-0', 'opacity-100'); }, 3000);
            @endif
            window.showToast = function(msg) {
                t.textContent = msg;
                t.classList.remove('translate-y-4', 'opacity-0', 'pointer-events-none');
                t.classList.add('translate-y-0', 'opacity-100');
                setTimeout(() => { t.classList.add('translate-y-4', 'opacity-0', 'pointer-events-none'); t.classList.remove('translate-y-0', 'opacity-100'); }, 3000);
            };
        })();
        </script>
    </body>
</html>
