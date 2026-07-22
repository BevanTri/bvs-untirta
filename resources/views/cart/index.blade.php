@extends('layouts.app')
@section('title', 'Keranjang')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-10">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-brand-gold/10 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
        </div>
        <div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-brand-ink uppercase tracking-wide">Keranjang</h1>
            @if($items->isNotEmpty())
            <p class="text-sm text-brand-ink-muted">{{ $items->count() }} item</p>
            @endif
        </div>
    </div>

    @if($items->isEmpty())
    <x-empty-state
        icon="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"
        title="Keranjang masih kosong"
        description="Belum ada produk di keranjang. Silakan pilih produk terlebih dahulu."
        actionLabel="Lihat Produk"
        actionUrl="{{ route('products') }}"
    />
    @else
    <form action="{{ route('cart.checkout') }}" method="POST" id="cart-form">
        @csrf
        <div class="space-y-3 mb-6">
            @foreach($items as $item)
            <div class="card p-4 flex items-start gap-4 active:scale-[0.99] transition-transform">
                <div class="shrink-0">
                    <label class="flex items-center justify-center w-6 h-6 cursor-pointer">
                        <input type="checkbox" name="selected[]" value="{{ $item->id }}" class="w-4 h-4 rounded border-brand-border text-brand-gold focus:ring-brand-gold/30" checked>
                    </label>
                </div>
                <div class="w-16 h-16 shrink-0 rounded-xl bg-brand-warm flex items-center justify-center overflow-hidden">
                    @if($item->itemable && $item->itemable_type === 'App\Models\Product' && $item->itemable->image && !str_starts_with($item->itemable->image, 'p_') && !str_starts_with($item->itemable->image, 'products/p_'))
                    <img src="{{ url('uploads/'.$item->itemable->image) }}" alt="{{ $item->name }}" class="w-full h-full object-contain" loading="lazy">
                    @else
                    <svg class="w-8 h-8 text-brand-ink-faint" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-brand-ink line-clamp-1">{{ $item->name }}</h3>
                    <p class="text-xs text-brand-ink-muted mt-0.5 font-mono">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <label class="text-xs text-brand-ink-faint">Qty:</label>
                        <input type="number" name="qty[{{ $item->id }}]" value="{{ $item->quantity }}" min="0" class="w-16 input-field !py-1.5 !min-h-[36px] text-center text-sm font-mono">
                        <button type="submit" form="delete-{{ $item->id }}" onclick="return confirm('Hapus {{ $item->name }} dari keranjang?')" class="text-xs text-red-500 hover:text-red-600 transition-colors ml-auto">Hapus</button>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <p class="font-bold text-brand-gold font-mono text-sm">Rp{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Sticky Checkout --}}
        <div class="sticky bottom-16 md:bottom-0 z-30 -mx-4 px-4 pb-4 md:pb-0 md:static md:mx-0 md:px-0">
            <div class="card p-4 md:p-5 shadow-level-4 md:shadow-level-1">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-brand-ink-muted">Subtotal</span>
                    <span class="font-bold text-lg text-brand-gold font-mono">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn-primary flex-1 text-base py-4">Lanjut ke Checkout</button>
                    <a href="{{ route('products') }}" class="btn-outline text-center">Tambah Barang</a>
                </div>
            </div>
        </div>
    </form>

    @foreach($items as $item)
    <form id="delete-{{ $item->id }}" action="{{ route('cart.destroy', $item) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
    @endif
</div>
@endsection
