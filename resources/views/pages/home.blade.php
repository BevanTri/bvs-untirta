@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
{{-- Hero --}}
<div class="relative bg-brand-navy overflow-hidden">
    <div class="absolute inset-0">
        <img src="/images/BUAT-COVER-WEB_20250305_140626_0000.jpg" alt="" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-navy via-brand-navy/80 to-brand-navy/40"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 pt-20 pb-16 md:pt-28 md:pb-20 relative">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-brand-gold/10 border border-brand-gold/30 rounded-full px-4 py-1.5 mb-4">
                <span class="w-2 h-2 bg-brand-gold rounded-full"></span>
                <span class="text-brand-gold font-semibold text-xs uppercase tracking-[0.15em]">Vokasi & Sains</span>
            </div>
            <h1 class="font-display text-4xl sm:text-5xl md:text-7xl font-bold leading-none tracking-[-0.02em] text-white uppercase break-words">
                Bengkel<br><span class="text-brand-gold">Vokasi & Sains</span>
            </h1>
            <p class="text-white text-base md:text-lg mt-5 max-w-xl leading-relaxed">Layanan otomotif profesional, pendidikan vokasi, dan riset terpadu. Dari FKIP Untirta untuk masyarakat.</p>
            <div class="flex flex-wrap gap-4 mt-8">
                <a href="{{ route('products') }}" class="btn-primary">Lihat Produk</a>
                <a href="{{ route('services') }}" class="inline-flex items-center justify-center gap-2 border-2 border-white/20 text-white px-6 py-3 text-sm hover:border-brand-gold hover:text-brand-gold transition-all duration-200 rounded-xl">Lihat Service</a>
            </div>
        </div>
    </div>
    <div class="h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>

{{-- Stats --}}
<div class="bg-white border-y border-brand-border">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid {{ $brands->count() ? 'grid-cols-2' : 'grid-cols-1' }} gap-6 text-center max-w-md mx-auto">
            <div class="{{ $brands->count() ? 'border-r border-brand-border' : '' }}">
                <div class="font-display text-3xl sm:text-4xl md:text-5xl font-bold text-brand-blue leading-none break-words">{{ $totalProducts }}+</div>
                <div class="text-brand-ink-faint text-xs uppercase tracking-[0.15em] mt-2 font-display">Produk</div>
            </div>
            @if($brands->count())
            <div>
                <div class="font-display text-3xl sm:text-4xl md:text-5xl font-bold text-brand-blue leading-none break-words">{{ $brands->count() }}</div>
                <div class="text-brand-ink-faint text-xs uppercase tracking-[0.15em] mt-2 font-display">Brand</div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Categories --}}
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="mb-8">
        <span class="section-label">Kategori</span>
        <h2 class="section-heading">Pilih Kebutuhan</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $cat)
        <a href="{{ route('products.category', $cat) }}" class="card-hover p-6 text-center group border-l-4 border-transparent hover:border-brand-gold">
            <div class="w-12 h-12 mx-auto bg-brand-gold/10 flex items-center justify-center mb-3 rounded-xl group-hover:bg-brand-gold/20 transition-colors">
                <span class="text-brand-gold text-xl font-mono">
                    @switch($cat->name)
                        @case('Ban')⏺@break
                        @case('Aki')▦@break
                        @case('Shock Absorber')⇅@break
                        @case('Oli')≋@break
                        @default▧
                    @endswitch
                </span>
            </div>
            <div class="font-display font-semibold text-lg text-brand-ink uppercase tracking-wide">{{ $cat->name }}</div>
        </a>
        @endforeach
    </div>
</div>

{{-- Products --}}
<div class="bg-brand-warm border-y border-brand-border py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="section-label">Produk</span>
                <h2 class="section-heading">Produk Terbaru</h2>
            </div>
            <a href="{{ route('products') }}" class="btn-primary hidden sm:inline-flex">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($products as $product)
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
            @endforeach
        </div>
        <div class="text-center mt-8 sm:hidden">
            <a href="{{ route('products') }}" class="btn-primary">Lihat Semua</a>
        </div>
    </div>
</div>

{{-- Services --}}
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="section-label">Service</span>
            <h2 class="section-heading">Layanan Kami</h2>
        </div>
        <a href="{{ route('services') }}" class="btn-primary hidden sm:inline-flex">Lihat Semua</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($services as $service)
        <div class="card-hover p-6 border-l-4 border-transparent hover:border-brand-gold">
            <h3 class="font-display font-semibold text-xl text-brand-ink uppercase tracking-wide">{{ $service->name }}</h3>
            <p class="text-sm text-brand-ink-muted mt-2 leading-relaxed">{{ $service->description }}</p>
            <p class="text-2xl font-bold mt-4 text-brand-gold tabular-nums font-mono">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
            @auth
            <a href="{{ route('repairs.create', ['service' => $service->id]) }}" class="mt-4 block text-center btn-primary text-sm">Pesan</a>
            @else
            <a href="{{ route('login') }}" class="mt-4 block text-center text-sm text-brand-ink-muted border-2 border-brand-border py-3 uppercase tracking-wider font-display rounded-lg hover:border-brand-gold hover:text-brand-gold transition-colors">Login untuk Memesan</a>
            @endauth
        </div>
        @endforeach
    </div>
</div>

{{-- Location --}}
<div class="bg-brand-warm border-y border-brand-border py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-8">
            <span class="section-label">Lokasi</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold tracking-wide text-brand-ink">Temukan Kami</h2>
            <p class="text-brand-ink-muted text-sm mt-2">Kampus FKIP Untirta, Ciwaru — Cipocok Jaya, Kota Serang</p>
        </div>
        <div class="flex justify-center">
            <div class="rounded-xl overflow-hidden shadow-lg border border-brand-border w-full max-w-2xl">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.0004907772327!2d106.1631634748024!3d-6.130634593856165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e41f5004af1ce39%3A0x9b9749007ed664c2!2sBengkel%20Vokasi%20dan%20Sains%20Untirta!5e0!3m2!1sid!2sid!4v1784398866170!5m2!1sid!2sid" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
        </div>
    </div>
</div>

{{-- Brands --}}
@if($brands->count())
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="section-label">Brand</span>
            <h2 class="section-heading">Brand Partner</h2>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <button id="brand-scroll-prev" class="w-9 h-9 border-2 border-brand-border flex items-center justify-center text-brand-ink-muted hover:border-brand-gold hover:text-brand-gold transition-all rounded-lg text-lg leading-none">&lsaquo;</button>
            <button id="brand-scroll-next" class="w-9 h-9 border-2 border-brand-border flex items-center justify-center text-brand-ink-muted hover:border-brand-gold hover:text-brand-gold transition-all rounded-lg text-lg leading-none">&rsaquo;</button>
        </div>
    </div>
    <div class="-mx-4 px-4 overflow-hidden max-w-full">
        <div id="brand-track" class="flex gap-4 items-center overflow-x-auto scrollbar-hide scroll-smooth" style="-webkit-overflow-scrolling: touch; -ms-overflow-style: none;">
            @foreach($brands as $brand)
            <div class="card p-4 flex items-center justify-center h-20 w-40 shrink-0 hover:border-brand-gold-light">
                @if($brand->logo)
                <img src="{{ url('uploads/'.$brand->logo) }}" alt="{{ $brand->name }}" class="max-h-full max-w-full object-contain opacity-60 hover:opacity-100 transition-opacity">
                @else
                <span class="text-sm font-semibold text-brand-ink-muted font-display uppercase tracking-wide">{{ $brand->name }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var track = document.getElementById('brand-track');
    var prev = document.getElementById('brand-scroll-prev');
    var next = document.getElementById('brand-scroll-next');
    if (track && prev && next) {
        prev.addEventListener('click', function() { track.scrollBy({ left: -240, behavior: 'smooth' }); });
        next.addEventListener('click', function() { track.scrollBy({ left: 240, behavior: 'smooth' }); });
    }
});
</script>
@endif
@endsection