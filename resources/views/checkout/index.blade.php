<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-blue/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-brand-ink uppercase tracking-wide">Checkout</h2>
                <p class="text-xs text-brand-ink-muted">Konfirmasi pesanan anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-10">
        <div class="max-w-3xl mx-auto px-4">
            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf
                <input type="hidden" name="customer_name" value="{{ Auth::user()->name }}">

                {{-- Ringkasan Pesanan --}}
                <div class="card overflow-hidden mb-6">
                    <div class="px-4 py-3 border-b border-brand-border bg-brand-warm">
                        <h3 class="font-display font-semibold text-sm text-brand-ink uppercase tracking-wide">Ringkasan Pesanan</h3>
                    </div>
                    <div class="divide-y divide-brand-border/60">
                        @foreach($items as $item)
                        <div class="flex items-center gap-3 p-4 hover:bg-brand-warm/50 transition-colors">
                            <div class="w-12 h-12 shrink-0 rounded-lg bg-brand-warm flex items-center justify-center overflow-hidden">
                                @if($item->itemable && $item->itemable_type === 'App\Models\Product' && $item->itemable->image && !str_starts_with($item->itemable->image, 'p_'))
                                <img src="{{ url('uploads/'.$item->itemable->image) }}" alt="{{ $item->name }}" class="w-full h-full object-contain" loading="lazy">
                                @else
                                <svg class="w-6 h-6 text-brand-ink-faint" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-brand-ink truncate">{{ $item->name }}</p>
                                <p class="text-xs text-brand-ink-faint font-mono">{{ $item->quantity }}x Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-brand-gold text-sm font-mono whitespace-nowrap">Rp{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center px-4 py-4 bg-brand-gold/5 border-t border-brand-border">
                        <span class="font-display font-semibold text-brand-ink uppercase tracking-wide text-sm">Total</span>
                        <span class="font-display font-bold text-xl md:text-2xl text-brand-gold font-mono">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Form --}}
                <div class="card p-5 mb-6">
                    <h3 class="font-display font-semibold text-sm text-brand-ink uppercase tracking-wide mb-4">Detail Pesanan</h3>
                    <div class="mb-4">
                        <label for="notes" class="block text-xs text-brand-ink-faint uppercase tracking-wider font-semibold mb-2">Catatan</label>
                        <textarea id="notes" name="notes" class="input-field" rows="2" placeholder="Contoh: Tolong dicek kondisi ban...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="sticky bottom-16 md:bottom-0 z-30 -mx-4 px-4 pb-4 md:pb-0 md:static md:mx-0 md:px-0">
                    <button type="submit" class="btn-primary w-full text-base py-4 shadow-lg shadow-brand-gold/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Bayar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
