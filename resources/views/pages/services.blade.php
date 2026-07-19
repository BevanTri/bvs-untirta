@extends('layouts.public')
@section('title', 'Service')

@section('content')
<div class="relative bg-brand-navy overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(245,158,11,0.8) 20px, rgba(245,158,11,0.8) 21px);"></div>
    <div class="max-w-7xl mx-auto px-4 pt-16 pb-14 relative">
        <div class="inline-block mb-3">
            <span class="text-brand-gold font-display text-sm font-semibold tracking-[0.2em] uppercase border border-brand-gold/30 px-3 py-1">Layanan</span>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-6xl font-bold tracking-[-0.02em] text-white uppercase break-words">Service</h1>
        <p class="text-zinc-400 mt-3 text-sm">Service profesional untuk motor dan mobil Anda</p>
    </div>
    <div class="h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>

{{-- Layanan Kami --}}
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="mb-10">
        <span class="section-label">Layanan</span>
        <h2 class="section-heading">Layanan Kami</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($services as $service)
        <div class="card-hover p-6 border-l-4 border-transparent hover:border-brand-gold">
            <h3 class="font-display font-semibold text-xl text-brand-ink uppercase tracking-wide">{{ $service->name }}</h3>
            <p class="text-sm text-brand-ink-muted mt-2 leading-relaxed">{{ $service->description }}</p>
            <p class="text-2xl font-bold mt-4 text-brand-gold tabular-nums font-mono">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
            @auth
            <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="type" value="service">
                <input type="hidden" name="id" value="{{ $service->id }}">
                <input type="hidden" name="quantity" value="1">
                @if($service->name === 'Ganti Oli')
                <select name="service_product_id" class="w-full input-field mb-3">
                    <option value="">Pilih Oli (opsional)</option>
                    @foreach($oliProducts as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} - Rp{{ number_format($p->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
                @endif
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary flex-1 text-sm">+ Keranjang</button>
                    <button type="submit" name="buy_now" value="1" class="btn-outline text-sm">Beli</button>
                </div>
            </form>
            @else
            <a href="{{ route('login') }}" class="mt-4 block text-center text-sm text-brand-ink-muted border-2 border-brand-border py-3 uppercase tracking-wider font-display rounded-lg hover:border-brand-gold hover:text-brand-gold transition-colors">Login untuk Memesan</a>
            @endauth
        </div>
        @endforeach
    </div>
</div>
@endsection