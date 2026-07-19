@extends('layouts.public')
@section('title', $product->name)

@section('content')
<div class="relative bg-brand-navy overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(245,158,11,0.8) 20px, rgba(245,158,11,0.8) 21px);"></div>
    <div class="max-w-7xl mx-auto px-4 pt-16 pb-14 relative">
        <div class="inline-block mb-3">
            <span class="text-brand-gold font-display text-sm font-semibold tracking-[0.2em] uppercase border border-brand-gold/30 px-3 py-1">{{ $product->category->name }}</span>
        </div>
        <h1 class="font-display text-2xl sm:text-4xl md:text-6xl font-bold tracking-[-0.02em] text-white uppercase break-words">{{ $product->name }}</h1>
    </div>
    <div class="h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-8">
        <div class="card overflow-hidden">
            <div class="aspect-[4/3] flex items-center justify-center p-4 rounded-t-xl">
@php $hasRealImage = $product->image && !str_starts_with($product->image, 'products/p_') && !str_starts_with($product->image, 'products/placeholder_'); @endphp
@if($hasRealImage)
                <img src="{{ url('uploads/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
@else
@php $colors = ['#1E3A5F','#92400E','#065F46','#991B1B','#5B21B6','#9D174D','#0F766E','#9A3412']; $c = $colors[$product->category_id % 8]; @endphp
                <div class="w-full h-full rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,{{ $c }},#0008);box-shadow:inset 0 -40px 60px #0002">
                    <span class="text-6xl font-bold text-white/80 font-display" style="text-shadow:0 2px 8px #0003">{{ mb_substr($product->name,0,2) }}</span>
                </div>
@endif
            </div>
        </div>
        <div class="card p-6">
            <p class="text-brand-ink-muted leading-relaxed text-sm">{{ $product->description ?? 'Deskripsi belum tersedia untuk produk ini.' }}</p>
            <div class="mt-6 pt-6 border-t border-brand-border">
                <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Harga</p>
                <p class="font-display text-4xl font-bold mt-1 text-brand-gold tabular-nums font-mono">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-brand-ink-muted mt-1 font-mono">Stok: {{ $product->stock ?? 'Tidak terbatas' }}</p>
            </div>
            @auth
            <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex items-center gap-2">
                @csrf
                <input type="hidden" name="type" value="product">
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" class="w-16 input-field text-center font-mono">
                <button type="submit" class="btn-primary flex-1">+ Keranjang</button>
                <button type="submit" name="buy_now" value="1" class="btn-outline">Beli Sekarang</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn-primary w-full text-center mt-6">Login untuk Membeli</a>
            @endauth
        </div>
    </div>
</div>
@endsection