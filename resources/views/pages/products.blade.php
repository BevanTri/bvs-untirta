@extends('layouts.public')
@section('title', 'Produk')

@section('content')
<div class="relative bg-brand-navy overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(245,158,11,0.8) 20px, rgba(245,158,11,0.8) 21px);"></div>
    <div class="max-w-7xl mx-auto px-4 pt-16 pb-14 relative">
        <div class="inline-block mb-4">
            <span class="text-brand-gold font-display text-xs font-semibold tracking-[0.2em] uppercase border border-brand-gold/30 px-3 py-1 rounded-full">Katalog</span>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-6xl font-bold tracking-[-0.02em] text-white uppercase text-balance">Produk</h1>
        <p class="text-zinc-400 mt-3 text-sm md:text-base">Dapatkan kebutuhan kendaraan kesayangan anda</p>
    </div>
    <div class="h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>

<div class="max-w-7xl mx-auto px-4 pt-8">
    {{-- Category Tabs --}}
    <div class="overflow-x-auto scrollbar-hide -mx-4 px-4 mb-8">
        <div class="flex gap-2">
            <a href="{{ route('products') }}" class="px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-display font-semibold uppercase tracking-wide transition-all border-2 rounded-xl whitespace-nowrap {{ !$category ? 'bg-brand-gold text-white border-brand-gold shadow-level-2' : 'text-brand-ink-muted border-brand-border hover:border-brand-gold hover:text-brand-gold' }}">Semua</a>
            @foreach($categories as $cat)
            <a href="{{ route('products.category', $cat) }}" class="px-4 sm:px-5 py-2.5 text-xs sm:text-sm font-display font-semibold uppercase tracking-wide transition-all border-2 rounded-xl whitespace-nowrap {{ $category && $category->id === $cat->id ? 'bg-brand-gold text-white border-brand-gold shadow-level-2' : 'text-brand-ink-muted border-brand-border hover:border-brand-gold hover:text-brand-gold' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($products as $product)
        @php
            $existingCart = Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->where('itemable_type', 'App\Models\Product')->where('itemable_id', $product->id)->first() : null;
            $hasRealImage = $product->image && !str_starts_with($product->image, 'p_') && !str_starts_with($product->image, 'products/p_') && !str_starts_with($product->image, 'products/placeholder_');
        @endphp
        <script>window._productData = window._productData || {}; window._productData[{{ $product->id }}] = {id:{{ $product->id }},name:'{{ $product->name }}',price:{{ $product->price }},stock:{{ $product->stock ?? 'null' }},cartQty:{{ $existingCart ? $existingCart->quantity : 0 }},image:'{{ $hasRealImage ? url('uploads/'.$product->image) : '' }}'};</script>
        <div class="card-hover group active:scale-[0.98] transition-all duration-200">
            <a href="{{ route('product.detail', $product) }}">
                <div class="aspect-[4/3] bg-white flex items-center justify-center p-4 border-b border-brand-border rounded-t-2xl overflow-hidden">
                    @if($hasRealImage)
                    <img src="{{ url('uploads/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain" loading="lazy" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center bg-brand-warm text-brand-ink-faint\'><svg class=\'w-8 h-8 mb-1\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/><\/svg><span class=\'text-xs font-medium\'>Foto tidak tersedia<\/span><\/div>'">
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
                <div class="flex items-center justify-between mt-3">
                    <p class="font-bold text-brand-gold tabular-nums font-mono text-base">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    @if($product->stock !== null && $product->stock < 5)
                    <span class="badge-warning text-[10px] px-2 py-0.5">Sisa {{ $product->stock }}</span>
                    @endif
                </div>
                @auth
                <div class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-1.5">
                    <button type="button" onclick="ProductSheet.open({{ $product->id }})" class="btn-primary flex-1 btn-xs">+ Keranjang</button>
                    <button type="button" onclick="ProductSheet.open({{ $product->id }})" class="btn-outline btn-xs">Beli</button>
                </div>
                @else
                <a href="{{ route('login') }}" class="mt-3 block text-center btn-xs text-brand-ink-muted border-2 border-brand-border py-2 uppercase tracking-wider font-display rounded-xl hover:border-brand-gold hover:text-brand-gold transition-colors">Login untuk Membeli</a>
                @endauth
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <x-empty-state title="Belum ada produk" description="Belum ada produk di kategori ini." actionLabel="Lihat Semua" actionUrl="{{ route('products') }}" />
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(method_exists($products, 'links'))
    <div class="mt-8">
        {{ $products->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>
@endsection
