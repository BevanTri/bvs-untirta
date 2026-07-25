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
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-brand-warm dark:bg-brand-navy text-brand-ink dark:text-zinc-100 min-h-screen flex flex-col overflow-x-hidden">
    <nav class="bg-white/90 dark:bg-brand-navy-2/90 backdrop-blur-md border-b border-brand-border/80 dark:border-brand-navy-3 sticky top-0 z-40 supports-backdrop-blur:bg-white/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                    <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-7 w-auto">
                    <span class="font-display font-bold text-brand-navy dark:text-white text-lg tracking-[0.05em] uppercase hidden xs:inline">BVS Untirta</span>
                </a>
                <div class="hidden sm:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('home') ? 'text-brand-blue dark:text-brand-blue-light' : 'text-brand-ink-muted dark:text-zinc-400 hover:text-brand-blue dark:hover:text-brand-blue-light' }}">Beranda</a>
                    <a href="{{ route('products') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('products*') ? 'text-brand-blue dark:text-brand-blue-light' : 'text-brand-ink-muted dark:text-zinc-400 hover:text-brand-blue dark:hover:text-brand-blue-light' }}">Produk</a>
                    <a href="{{ route('services') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('services*') ? 'text-brand-blue dark:text-brand-blue-light' : 'text-brand-ink-muted dark:text-zinc-400 hover:text-brand-blue dark:hover:text-brand-blue-light' }}">Service</a>
                    @auth
                        @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('admin.*') ? 'text-brand-gold' : 'text-brand-ink-muted dark:text-zinc-400 hover:text-brand-gold' }}">Admin</a>
                        @endif
                    @endauth
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" class="theme-toggle text-brand-ink-muted hover:text-brand-blue dark:text-zinc-400 dark:hover:text-brand-gold p-2 transition-colors cursor-pointer touch-target flex items-center justify-center rounded-xl hover:bg-brand-warm-2 dark:hover:bg-brand-navy-3" title="Ubah Tema">
                        <svg class="theme-toggle-dark-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="theme-toggle-light-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                    @auth
                        <a href="{{ route('orders.history') }}" id="public-nav-orders-link" class="text-brand-ink-muted dark:text-zinc-400 hover:text-brand-blue dark:hover:text-brand-blue-light text-sm transition-colors uppercase font-display tracking-wide touch-target flex items-center">Pesanan</a>
                        <a href="{{ route('cart.index') }}" class="hidden sm:inline-flex text-brand-ink-muted dark:text-zinc-400 hover:text-brand-blue dark:hover:text-brand-blue-light transition-colors touch-target items-center justify-center" title="Keranjang">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </a>
                        <div class="relative">
                            <button onclick="togglePublicUserMenu()" type="button" class="flex items-center text-brand-ink-muted dark:text-zinc-400 hover:text-brand-navy dark:hover:text-zinc-200 transition-colors cursor-pointer touch-target">
                                <span class="w-7 h-7 rounded-full bg-brand-blue/10 flex items-center justify-center text-xs font-bold text-brand-blue shrink-0">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </button>
                            <div id="public-user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-brand-navy-2 rounded-xl shadow-level-4 border border-brand-border dark:border-brand-navy-3 z-[70] overflow-hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-brand-ink dark:text-zinc-200 hover:bg-brand-warm dark:hover:bg-brand-navy-3 transition-colors">Profile</a>
                                <div class="border-t border-brand-border dark:border-brand-navy-3"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-brand-ink dark:text-zinc-200 hover:bg-brand-warm dark:hover:bg-brand-navy-3 transition-colors">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-brand-ink-muted dark:text-zinc-400 hover:text-brand-blue dark:hover:text-brand-blue-light text-sm transition-colors uppercase font-display tracking-wide touch-target flex items-center">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary !py-2 !px-5 text-xs">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    @auth
    <script>
    function togglePublicUserMenu() {
        document.getElementById('public-user-menu').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        var m = document.getElementById('public-user-menu');
        var btn = document.querySelector('[onclick="togglePublicUserMenu()"]');
        if (m && !m.classList.contains('hidden') && !m.contains(e.target) && !btn.contains(e.target)) {
            m.classList.add('hidden');
        }
    });
    </script>
    @endauth

    <main class="flex-1 pb-16 md:pb-0">
        @yield('content')
    </main>

    <footer class="hidden md:block bg-brand-navy border-t border-brand-navy-2 text-zinc-400 shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-start">
                    <div class="flex items-center gap-2.5 mb-3">
                        <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-8 w-auto">
                        <span class="font-display font-bold text-white text-xl tracking-[0.05em] uppercase">BVS Untirta</span>
                    </div>
                        <p class="text-sm leading-6 text-zinc-400 max-w-xs">Bengkel & Variasi Motor & Mobil — solusi perawatan kendaraan terpercaya. Terintegrasi dengan program Vokasi FKIP Untirta.</p>
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
                        <li class="grid grid-cols-[max-content_auto] gap-x-3 text-sm"><span class="text-zinc-400">Senin - Sabtu</span><span class="font-medium text-white">08:00 - 17:00</span></li>
                        <li class="grid grid-cols-[max-content_auto] gap-x-3 text-sm"><span class="text-zinc-400">Minggu & Hari Besar</span><span class="text-zinc-500">Tutup</span></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-x-8 gap-y-1 mt-8 pt-4 text-xs text-zinc-500 tracking-wide">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }} — FKIP UNTIRTA. All rights reserved.</span>
            </div>
        </div>
    </footer>

    @include('layouts.partials.bottom-nav')

    <x-toast />
    <x-product-bottom-sheet />

    @stack('scripts')
</body>
</html>
