@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
{{-- Hero --}}
<div class="relative bg-brand-navy min-h-[60vh] md:min-h-[55vh] flex items-center overflow-hidden">
    <div class="absolute inset-0">
        <img src="/images/BUAT-COVER-WEB_20250305_140626_0000.jpg" alt="" class="w-full h-full object-cover opacity-25" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-navy via-brand-navy/80 to-brand-navy/40"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 24px, rgba(245,158,11,0.6) 24px, rgba(245,158,11,0.6) 25px);"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 pt-24 pb-16 md:pt-28 md:pb-20 relative w-full">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-brand-gold/10 border border-brand-gold/30 rounded-full px-4 py-1.5 mb-5">
                <span class="w-2 h-2 bg-brand-gold rounded-full animate-pulse"></span>
                <span class="text-brand-gold font-semibold text-xs uppercase tracking-[0.15em]">Vokasi & Sains</span>
            </div>
            <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-bold leading-none tracking-[-0.02em] text-white uppercase text-balance">
                Bengkel<br><span class="text-brand-gold">Vokasi & Sains</span>
            </h1>
            <p class="text-white/80 text-base md:text-lg mt-4 max-w-xl leading-relaxed">Layanan otomotif profesional, pendidikan vokasi, dan riset terpadu. Dari FKIP Untirta untuk masyarakat.</p>
            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <a href="{{ route('products') }}" class="btn-primary flex-1 text-center text-base py-4 shadow-lg shadow-brand-gold/20">Lihat Produk</a>
                <a href="{{ route('services') }}" class="inline-flex items-center justify-center gap-2 border-2 border-white/30 text-white px-6 py-4 text-base hover:border-brand-gold hover:text-brand-gold hover:bg-brand-gold/10 transition-all duration-200 rounded-xl flex-1 text-center font-semibold">Lihat Service</a>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold"></div>
</div>

{{-- Stats --}}
<div class="bg-white border-b border-brand-border">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
        <div class="grid grid-cols-2 gap-6 md:gap-8 max-w-lg mx-auto">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-brand-blue/10 rounded-xl mb-3">
                    <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="font-display text-3xl sm:text-4xl md:text-5xl font-bold text-brand-blue leading-none">{{ $totalProducts }}+</div>
                <div class="text-brand-ink-faint text-xs uppercase tracking-[0.15em] mt-2 font-display">Produk Tersedia</div>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-brand-gold/10 rounded-xl mb-3">
                    <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="font-display text-3xl sm:text-4xl md:text-5xl font-bold text-brand-gold leading-none">{{ $brands->count() }}</div>
                <div class="text-brand-ink-faint text-xs uppercase tracking-[0.15em] mt-2 font-display">Brand Partner</div>
            </div>
        </div>
    </div>
</div>

{{-- Categories --}}
<div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
    <div class="text-center md:text-left mb-8">
        <span class="section-label">Kategori</span>
        <h2 class="section-heading">Pilih Kebutuhan</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $cat)
        <a href="{{ route('products.category', $cat) }}" class="card-hover p-5 md:p-6 text-center group border-l-4 border-transparent hover:border-brand-gold active:scale-[0.98] transition-all duration-200">
            <div class="w-14 h-14 mx-auto bg-brand-gold/10 flex items-center justify-center mb-3 rounded-2xl text-2xl group-hover:bg-brand-gold/20 group-hover:scale-110 transition-all duration-200">
                <span class="text-brand-gold">
                    @switch($cat->name)
                        @case('Ban')⏺@break
                        @case('Aki')▦@break
                        @case('Shock Absorber')⇅@break
                        @case('Oli')≋@break
                        @default▧
                    @endswitch
                </span>
            </div>
            <div class="font-display font-semibold text-base md:text-lg text-brand-ink uppercase tracking-wide">{{ $cat->name }}</div>
        </a>
        @endforeach
    </div>
</div>

{{-- Products --}}
<div class="bg-brand-warm border-y border-brand-border py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="section-label">Produk</span>
                <h2 class="section-heading">Produk Terbaru</h2>
            </div>
            <a href="{{ route('products') }}" class="btn-primary hidden sm:inline-flex btn-sm">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($products as $product)
            @php
                $existingCart = Auth::check() ? \App\Models\CartItem::where('user_id', Auth::id())->where('itemable_type', 'App\Models\Product')->where('itemable_id', $product->id)->first() : null;
                $imgHtml = '';
                $hasRealImage = $product->image && !str_starts_with($product->image, 'p_') && !str_starts_with($product->image, 'products/p_') && !str_starts_with($product->image, 'products/placeholder_');
                if ($hasRealImage) {
                    $imgHtml = '<img src="'.url('uploads/'.$product->image).'" alt="'.$product->name.'" class="w-full h-full object-contain" loading="lazy" onerror="this.onerror=null;this.parentElement.innerHTML=\'<div class=\\\'w-full h-full flex flex-col items-center justify-center bg-brand-warm text-brand-ink-faint\\\'><svg class=\\\'w-8 h-8 mb-1\\\' fill=\\\'none\\\' stroke=\\\'currentColor\\\' viewBox=\\\'0 0 24 24\\\'><path stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' stroke-width=\\\'1.5\\\' d=\\\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\\\'/><\\\/svg><span class=\\\'text-xs font-medium\\\'>Foto tidak tersedia<\\\/span><\\\/div>\'">';
                } else {
                    $colors = ['#1E3A5F','#92400E','#065F46','#991B1B','#5B21B6','#9D174D','#0F766E','#9A3412'];
                    $c = $colors[$product->category_id % 8];
                    $initials = mb_substr($product->name, 0, 2);
                    $imgHtml = '<div class="w-full h-full rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,'.$c.',#0008);box-shadow:inset 0 -40px 60px #0002"><span class="text-5xl font-bold text-white/80 font-display" style="text-shadow:0 2px 8px #0003">'.$initials.'</span></div>';
                }
            @endphp
            <script>window._productData = window._productData || {}; window._productData[{{ $product->id }}] = {id:{{ $product->id }},name:'{{ $product->name }}',price:{{ $product->price }},stock:{{ $product->stock ?? 'null' }},cartQty:{{ $existingCart ? $existingCart->quantity : 0 }},image:'{{ $hasRealImage ? url('uploads/'.$product->image) : '' }}'};</script>
            <div class="card-hover group active:scale-[0.98] transition-all duration-200">
                <a href="{{ route('product.detail', $product) }}">
                    <div class="aspect-[4/3] bg-white flex items-center justify-center p-4 border-b border-brand-border rounded-t-2xl overflow-hidden">
                        {!! $imgHtml !!}
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
                    <a href="{{ route('login') }}" class="mt-3 block text-center btn-xs text-brand-ink-muted border-2 border-brand-border py-2 uppercase tracking-wider font-display rounded-lg hover:border-brand-gold hover:text-brand-gold transition-colors">Login untuk Membeli</a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8 sm:hidden">
            <a href="{{ route('products') }}" class="btn-primary w-full">Lihat Semua</a>
        </div>
    </div>
</div>

{{-- Services --}}
<div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="section-label">Service</span>
            <h2 class="section-heading">Layanan Kami</h2>
        </div>
        <a href="{{ route('services') }}" class="btn-primary hidden sm:inline-flex btn-sm">Lihat Semua</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($services as $service)
        <div class="card-hover p-6 border-l-4 border-transparent hover:border-brand-gold active:scale-[0.98] transition-all duration-200">
            <h3 class="font-display font-semibold text-xl text-brand-ink uppercase">{{ $service->name }}</h3>
            <p class="text-sm text-brand-ink-muted mt-2 leading-relaxed">{{ $service->description }}</p>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-brand-border">
                <p class="text-2xl font-bold text-brand-gold tabular-nums font-mono">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
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

{{-- Location --}}
<div class="bg-brand-warm border-y border-brand-border py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-8">
            <span class="section-label">Lokasi</span>
            <h2 class="font-display text-3xl md:text-4xl font-bold tracking-wide text-brand-ink">Temukan Kami</h2>
            <p class="text-brand-ink-muted text-sm mt-2">Kampus FKIP Untirta, Ciwaru — Cipocok Jaya, Kota Serang</p>
        </div>
        <div class="flex justify-center">
            <div class="rounded-2xl overflow-hidden shadow-level-3 border border-brand-border w-full max-w-2xl">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.0004907772327!2d106.1631634748024!3d-6.130634593856165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e41f5004af1ce39%3A0x9b9749007ed664c2!2sBengkel%20Vokasi%20dan%20Sains%20Untirta!5e0!3m2!1sid!2sid!4v1784398866170!5m2!1sid!2sid" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="Lokasi Bengkel Vokasi dan Sains Untirta"></iframe>
            </div>
        </div>
    </div>
</div>

{{-- Brands --}}
@if($brands->count())
<div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <span class="section-label">Brand</span>
            <h2 class="section-heading">Brand Partner</h2>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <button id="brand-scroll-prev" class="btn-icon border-2 border-brand-border text-brand-ink-muted hover:border-brand-gold hover:text-brand-gold rounded-xl" aria-label="Scroll kiri">&lsaquo;</button>
            <button id="brand-scroll-next" class="btn-icon border-2 border-brand-border text-brand-ink-muted hover:border-brand-gold hover:text-brand-gold rounded-xl" aria-label="Scroll kanan">&rsaquo;</button>
        </div>
    </div>
    <div class="-mx-4 px-4 overflow-hidden">
        <div id="brand-track" class="flex gap-4 items-center overflow-x-auto scrollbar-hide scroll-smooth pb-2" style="-webkit-overflow-scrolling: touch;">
            @foreach($brands as $brand)
            <x-card padding="p-4" class="flex items-center justify-center h-20 w-40 shrink-0 hover:border-brand-gold-light">
                @if($brand->logo)
                <img src="{{ url('uploads/'.$brand->logo) }}" alt="{{ $brand->name }}" class="max-h-full max-w-full object-contain opacity-60 hover:opacity-100 transition-opacity" loading="lazy">
                @else
                <span class="text-sm font-semibold text-brand-ink-muted font-display uppercase tracking-wide">{{ $brand->name }}</span>
                @endif
            </x-card>
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
        prev.addEventListener('click', function() { track.scrollBy({ left: -280, behavior: 'smooth' }); });
        next.addEventListener('click', function() { track.scrollBy({ left: 280, behavior: 'smooth' }); });
    }
});
</script>
@endif
@endsection
