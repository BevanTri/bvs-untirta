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
    <link rel="stylesheet" href="{{ asset('build/assets/app-C4yZfVnF.css') }}">
    <script type="module" src="{{ asset('build/assets/app-B9qO1Jfl.js') }}"></script>
</head>
<body class="font-sans antialiased bg-brand-warm text-brand-ink min-h-screen flex flex-col overflow-x-hidden">
    <nav class="bg-white/95 backdrop-blur border-b border-brand-border sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-8 w-auto">
                    <span class="font-display font-bold text-brand-navy text-xl tracking-[0.05em] uppercase">BVS Untirta</span>
                </a>
                <div class="hidden sm:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-brand-ink-muted hover:text-brand-blue text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('home') ? 'text-brand-blue' : '' }}">Beranda</a>
                    <a href="{{ route('products') }}" class="text-brand-ink-muted hover:text-brand-blue text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('products*') ? 'text-brand-blue' : '' }}">Produk</a>
                    <a href="{{ route('services') }}" class="text-brand-ink-muted hover:text-brand-blue text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('services*') ? 'text-brand-blue' : '' }}">Service</a>
                    @auth
                        @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-brand-ink-muted hover:text-brand-blue text-sm font-medium transition-colors tracking-wide uppercase font-display">Admin</a>
                        @endif
                    @endauth
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('cart.index') }}" class="text-brand-ink-muted hover:text-brand-blue transition-colors" title="Keranjang">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </a>
                        <a href="{{ route('orders.history') }}" class="text-brand-ink-muted hover:text-brand-blue text-sm transition-colors uppercase font-display tracking-wide">Pesanan</a>
                        <div class="relative">
                            <button onclick="var d=document.getElementById('public-user-menu');if(d){d.classList.toggle('hidden')}" type="button" class="flex items-center text-brand-ink-muted hover:text-brand-navy transition-colors cursor-pointer">
                                <span class="w-7 h-7 rounded-full bg-brand-blue/10 flex items-center justify-center text-xs font-bold text-brand-blue shrink-0">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </button>
                            <div id="public-user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-brand-border z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-brand-ink hover:bg-brand-warm first:rounded-t-lg transition-colors">Profile</a>
                                <div class="border-t border-brand-border"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-brand-ink hover:bg-brand-warm last:rounded-b-lg transition-colors">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-brand-ink-muted hover:text-brand-blue text-sm transition-colors uppercase font-display tracking-wide">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary !py-2 !px-5">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-1 pb-16 md:pb-0">
        @yield('content')
    </main>
    <footer class="bg-brand-navy border-t border-brand-navy-2 text-brand-ink-faint shrink-0">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-7 w-auto">
                        <span class="font-display font-bold text-white text-xl tracking-[0.05em] uppercase">BVS Untirta</span>
                    </div>
                    <p class="text-sm leading-relaxed text-zinc-400">Bengkel & Variasi Motor & Mobil — solusi perawatan kendaraan terpercaya.</p>
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
