<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BVS Untirta') }} @isset($title)- {{ $title }}@endisset</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=oswald:500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/webp" href="{{ asset('images/logo-untirta.webp') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app-CgeNcYon.css') }}">
    <script type="module" src="{{ asset('build/assets/app-B9qO1Jfl.js') }}"></script>
</head>
<body class="font-sans antialiased bg-brand-warm text-brand-ink">
    <div class="flex md:h-screen flex-col md:flex-row">
        {{-- Sidebar --}}
        <aside class="hidden md:flex md:flex-col w-64 bg-brand-navy text-white shrink-0 md:min-h-screen">
            <div class="h-16 flex items-center px-6 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-6 w-auto">
                    <span class="font-display font-bold text-white text-lg tracking-[0.05em] uppercase">BVS Untirta</span>
                </a>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <div class="mb-1 px-3 py-1 text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-zinc-500">Umum</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.dashboard') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <div class="mt-4 mb-1 px-3 py-1 text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-zinc-500">Data Master</div>
                <a href="{{ route('admin.customers') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.customers') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Pelanggan
                </a>
                <a href="{{ route('admin.vehicles') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.vehicles') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Kendaraan
                </a>
                <a href="{{ route('admin.mechanics') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.mechanics') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Mekanik
                </a>
                <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.categories') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Kategori
                </a>
                <a href="{{ route('admin.products') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.products') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Produk
                </a>
                <a href="{{ route('admin.brands') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.brands') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Brand
                </a>
                <div class="mt-4 mb-1 px-3 py-1 text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-zinc-500">Transaksi</div>
                <a href="{{ route('admin.repair-orders') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.repair-orders*') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Servis
                </a>
                <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.orders') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Pesanan
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.reports') ? 'text-brand-gold bg-brand-gold/10' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Laporan
                </a>
            </nav>
            <div class="px-3 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-7 h-7 rounded-full bg-brand-gold/20 flex items-center justify-center text-xs font-bold text-brand-gold">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-zinc-500">Admin</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-zinc-500 hover:text-brand-gold text-xs transition-colors" title="Logout">&larr;</button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Mobile Header --}}
        <div class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-brand-border flex items-center justify-between px-4 h-14">
            <a href="{{ route('admin.dashboard') }}" class="font-display font-bold text-brand-navy text-lg tracking-[0.05em] uppercase">BVS Untirta</a>
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="text-xs text-brand-ink-muted hover:text-brand-blue transition-colors tracking-wide">&larr; Lihat Toko</a>
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')" class="text-brand-ink-muted hover:text-brand-blue p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Sidebar --}}
        <div id="mobile-sidebar" class="md:hidden fixed inset-0 z-[70] bg-white/95 backdrop-blur-sm hidden">
            <div class="flex items-center justify-between px-4 h-14 border-b border-brand-border">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-6 w-auto">
                    <span class="font-display font-bold text-brand-navy text-lg tracking-[0.05em] uppercase">BVS Untirta</span>
                </a>
                <button onclick="document.getElementById('mobile-sidebar').classList.add('hidden')" class="text-brand-ink-muted hover:text-brand-blue p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="p-4">
                <div class="mb-1 px-3 py-1 text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-zinc-400">Umum</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.dashboard') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg> Dashboard</a>
                <div class="mt-4 mb-1 px-3 py-1 text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-zinc-400">Data Master</div>
                <a href="{{ route('admin.customers') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.customers') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg> Pelanggan</a>
                <a href="{{ route('admin.vehicles') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.vehicles') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Kendaraan</a>
                <a href="{{ route('admin.mechanics') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.mechanics') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Mekanik</a>
                <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.categories') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> Kategori</a>
                <a href="{{ route('admin.products') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.products') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg> Produk</a>
                <a href="{{ route('admin.brands') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.brands') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> Brand</a>
                <div class="mt-4 mb-1 px-3 py-1 text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-zinc-400">Transaksi</div>
                <a href="{{ route('admin.repair-orders') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.repair-orders*') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Servis</a>
                <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.orders') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> Pesanan</a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.reports') ? 'text-brand-gold bg-brand-gold/10' : 'text-brand-ink-muted hover:text-brand-navy hover:bg-brand-warm-2' }}"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> Laporan</a>
                <hr class="border-brand-border my-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2 w-full text-sm font-medium text-brand-ink-muted hover:text-brand-blue hover:bg-brand-warm-2 transition-colors rounded-lg"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Logout</button>
                </form>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen md:min-h-0 md:pt-0 pt-14">
            <header class="hidden md:flex h-16 items-center justify-between px-6 border-b border-brand-border bg-white">
                <div>
                    @isset($title)
                    <h1 class="font-display font-bold text-xl text-brand-navy uppercase tracking-wide">{{ $title }}</h1>
                    @endisset
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-xs text-brand-ink-muted hover:text-brand-blue transition-colors tracking-wide">&larr; Lihat Toko</a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="py-6 px-4 md:px-6 min-w-0">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>