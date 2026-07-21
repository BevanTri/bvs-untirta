<x-app-layout>
    <x-slot name="header"><div class="flex items-center gap-3"><h2 class="font-display font-bold text-xl text-brand-ink uppercase tracking-wide">Checkout</h2></div></x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4">
            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf

                <div class="card overflow-hidden mb-6">
                    <div class="divide-y divide-brand-border">
                        @foreach($items as $item)
                        <div class="flex items-center gap-3 p-4">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-brand-ink truncate">{{ $item->name }}</p>
                                <p class="text-xs text-brand-ink-faint font-mono">
                                    @if($item->itemable_type === 'App\Models\Product') PRODUK @else SERVICE @endif
                                </p>
                            </div>
                            <p class="text-sm text-brand-ink-muted font-mono">{{ $item->quantity }}x</p>
                            <p class="font-display font-bold text-brand-gold text-sm tabular-nums font-mono">Rp{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center px-4 py-3 bg-brand-gold/5 border-t border-brand-border">
                        <span class="font-display font-semibold text-brand-ink uppercase tracking-wide">Total</span>
                        <span class="font-display font-bold text-xl text-brand-gold tabular-nums font-mono">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="mb-4">
                        <x-input-label for="notes" value="Catatan" class="text-brand-ink-faint text-xs uppercase tracking-wider font-display" />
                        <textarea id="notes" name="notes" class="input-field" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    <input type="hidden" name="customer_name" value="{{ Auth::user()->name }}">
                    <button type="submit" class="btn-primary w-full !py-3 font-semibold">Bayar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>