@extends('layouts.public')
@section('title', $product->name)

@section('content')
<div class="relative bg-brand-navy overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(245,158,11,0.8) 20px, rgba(245,158,11,0.8) 21px);"></div>
    <div class="max-w-7xl mx-auto px-4 pt-16 pb-14 relative">
        <div class="inline-block mb-3">
            <span class="badge badge-neutral text-brand-gold border border-brand-gold/30">{{ $product->category->name }}</span>
        </div>
        <h1 class="font-display text-2xl sm:text-4xl md:text-6xl font-bold tracking-[-0.02em] text-white uppercase break-words">{{ $product->name }}</h1>
    </div>
    <div class="h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-6">
        <x-card class="overflow-hidden">
            <div class="aspect-[4/3] flex items-center justify-center p-5">
@php $hasRealImage = $product->image && !str_starts_with($product->image, 'p_') && !str_starts_with($product->image, 'products/p_') && !str_starts_with($product->image, 'products/placeholder_'); @endphp
@if($hasRealImage)
                <img src="{{ url('uploads/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center bg-brand-warm text-brand-ink-faint\'><svg class=\'w-8 h-8 mb-1\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/><\/svg><span class=\'text-xs font-medium\'>Foto tidak tersedia<\/span><\/div>'" >
@else
@php $colors = ['#1E3A5F','#92400E','#065F46','#991B1B','#5B21B6','#9D174D','#0F766E','#9A3412']; $c = $colors[$product->category_id % 8]; @endphp
                <div class="w-full h-full rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,{{ $c }},#0008);box-shadow:inset 0 -40px 60px #0002">
                    <span class="text-6xl font-bold text-white/80 font-display" style="text-shadow:0 2px 8px #0003">{{ mb_substr($product->name,0,2) }}</span>
                </div>
@endif
            </div>
        </x-card>
        <x-card>
            <p class="text-brand-ink-muted leading-relaxed text-sm">{{ $product->description ?? 'Deskripsi belum tersedia untuk produk ini.' }}</p>
            <div class="mt-6 pt-6 border-t border-brand-border">
                <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Harga</p>
                <p class="font-display text-4xl font-bold mt-1 text-brand-gold font-mono tabular-nums">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-brand-ink-muted mt-1 font-mono">Stok: {{ $product->stock ?? 'Tidak terbatas' }}</p>
            </div>
            @auth
            @php
                $existingCart = \App\Models\CartItem::where('user_id', Auth::id())->where('itemable_type', 'App\Models\Product')->where('itemable_id', $product->id)->first();
                $hasRealImage = $product->image && !str_starts_with($product->image, 'products/p_') && !str_starts_with($product->image, 'products/placeholder_');
            @endphp
            <script>window._productData = window._productData || {}; window._productData[{{ $product->id }}] = {id:{{ $product->id }},name:'{{ $product->name }}',price:{{ $product->price }},stock:{{ $product->stock ?? 'null' }},cartQty:{{ $existingCart ? $existingCart->quantity : 0 }},image:'{{ $hasRealImage ? url('uploads/'.$product->image) : '' }}'};</script>
            <div class="mt-6 flex flex-col gap-3">
                <button type="button" onclick="ProductSheet.open({{ $product->id }})" class="btn-primary w-full min-h-[44px]">+ Keranjang</button>
                <button type="button" onclick="ProductSheet.open({{ $product->id }})" class="btn-outline w-full min-h-[44px] border-brand-gold text-brand-gold hover:bg-brand-gold hover:text-white">Beli Sekarang</button>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn-primary w-full text-center mt-6">Login untuk Membeli</a>
            @endauth
        </x-card>
    </div>
</div>
@endsection
