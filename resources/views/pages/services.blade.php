@extends('layouts.public')
@section('title', 'Service')

@section('content')
<div class="relative bg-brand-navy overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(245,158,11,0.8) 20px, rgba(245,158,11,0.8) 21px);"></div>
    <div class="max-w-7xl mx-auto px-4 pt-16 pb-14 relative">
        <div class="inline-block mb-4">
            <span class="text-brand-gold font-display text-xs font-semibold tracking-[0.2em] uppercase border border-brand-gold/30 px-3 py-1 rounded-full">Layanan</span>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-6xl font-bold tracking-[-0.02em] text-white uppercase text-balance">Service</h1>
        <p class="text-zinc-400 mt-3 text-sm md:text-base">Service profesional untuk motor dan mobil Anda</p>
    </div>
    <div class="h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>

<div class="max-w-7xl mx-auto px-4 py-10 md:py-14">
    <div class="text-center md:text-left mb-8">
        <span class="section-label">Layanan</span>
        <h2 class="section-heading">Layanan Kami</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($services as $service)
        <div class="card-hover p-6 border-l-4 border-transparent hover:border-brand-gold active:scale-[0.98] transition-all duration-200">
            <div class="w-12 h-12 bg-brand-gold/10 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="font-display font-semibold text-xl text-brand-ink uppercase">{{ $service->name }}</h3>
            <p class="text-sm text-brand-ink-muted mt-2 leading-relaxed">{{ $service->description }}</p>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-brand-border">
                <p class="text-2xl font-bold text-brand-gold font-mono">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                @auth
                <a href="{{ route('repairs.create', ['service' => $service->id]) }}" class="btn-primary btn-sm">Pesan</a>
                @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-brand-ink-muted border-2 border-brand-border px-4 py-2 rounded-xl hover:border-brand-gold hover:text-brand-gold transition-colors">Login</a>
                @endauth
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
