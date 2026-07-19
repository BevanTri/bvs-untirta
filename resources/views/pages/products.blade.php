@extends('layouts.public')
@section('title', 'Produk')

@section('content')
<div class="relative bg-brand-navy overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(245,158,11,0.8) 20px, rgba(245,158,11,0.8) 21px);"></div>
    <div class="max-w-7xl mx-auto px-4 pt-16 pb-14 relative">
        <div class="inline-block mb-3">
            <span class="text-brand-gold font-display text-sm font-semibold tracking-[0.2em] uppercase border border-brand-gold/30 px-3 py-1">Katalog</span>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-6xl font-bold tracking-[-0.02em] text-white uppercase break-words">Produk</h1>
        <p class="text-zinc-400 mt-3 text-sm">Dapatkan kebutuhan kendaraan kesayangan anda</p>
    </div>
    <div class="h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>
<div class="max-w-7xl mx-auto px-4 pt-8">
    <div class="flex flex-wrap gap-2 mb-8 max-w-full">
        <a href="{{ route('products') }}" class="px-3 sm:px-5 py-2 text-xs sm:text-sm font-display font-semibold uppercase tracking-wide transition-all border-2 {{ !$category ? 'bg-brand-gold text-white border-brand-gold' : 'text-brand-ink-muted border-brand-border hover:border-brand-gold hover:text-brand-gold' }} rounded-lg">Semua</a>
        @foreach($categories as $cat)
        <a href="{{ route('products.category', $cat) }}" class="px-3 sm:px-5 py-2 text-xs sm:text-sm font-display font-semibold uppercase tracking-wide transition-all border-2 {{ $category && $category->id === $cat->id ? 'bg-brand-gold text-white border-brand-gold' : 'text-brand-ink-muted border-brand-border hover:border-brand-gold hover:text-brand-gold' }} rounded-lg">{{ $cat->name }}</a>
        @endforeach
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        @forelse($products as $product)
        <div class="card-hover group">
            <a href="{{ route('product.detail', $product) }}">
                <div class="aspect-[4/3] bg-white flex items-center justify-center p-4 border-b border-brand-border rounded-t-xl">
                    @php $hasRealImage = $product->image && !str_starts_with($product->image, 'products/p_') && !str_starts_with($product->image, 'products/placeholder_'); @endphp
@if($hasRealImage)
<img src="{{ url('uploads/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
@else
@php $colors = ['#1E3A5F','#92400E','#065F46','#991B1B','#5B21B6','#9D174D','#0F766E','#9A3412']; $c = $colors[$product->category_id % 8]; @endphp
<div class="w-full h-full rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,{{ $c }},#0008);box-shadow:inset 0 -40px 60px #0002">
<span class="text-5xl font-bold text-white/80 font-display" style="text-shadow:0 2px 8px #0003">{{ mb_substr($product->name,0,2) }}</span>
</div>
@endif
                </div>
            </a>
            <div class="p-4">
                <p class="text-xs text-brand-blue font-display uppercase tracking-[0.15em] font-semibold">{{ $product->category->name }}</p>
                <a href="{{ route('product.detail', $product) }}">
                    <h3 class="text-sm font-medium mt-1 leading-snug line-clamp-2 text-brand-ink group-hover:text-brand-blue transition-colors">{{ $product->name }}</h3>
                </a>
                <p class="font-bold mt-3 text-brand-gold tabular-nums font-mono">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                @auth
                <form action="{{ route('cart.add') }}" method="POST" class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-1.5">
                    @csrf
                    <input type="hidden" name="type" value="product">
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-primary flex-1 text-xs">+ Keranjang</button>
                    <button type="submit" name="buy_now" value="1" class="btn-outline text-xs">Beli</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="mt-3 block text-center text-xs text-brand-ink-muted border-2 border-brand-border py-2 uppercase tracking-wider font-display rounded-lg hover:border-brand-gold hover:text-brand-gold transition-colors">Login</a>
                @endauth
            </div>
        </div>
        @empty
        <p class="col-span-full text-center text-brand-ink-faint py-12 font-display uppercase tracking-wide">Belum ada produk di kategori ini.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
</div>
@endsection