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
                    <h3 class="font-display font-bold text-lg text-brand-ink uppercase tracking-wide mb-4">Data Pelanggan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="customer_name" value="Nama" class="text-brand-ink-faint text-xs uppercase tracking-wider font-display" />
                            <x-text-input id="customer_name" name="customer_name" required class="w-full input-field" value="{{ old('customer_name', Auth::user()->name) }}" />
                            <x-input-error :messages="$errors->get('customer_name')" />
                        </div>
                        <div>
                            <x-input-label for="customer_phone" value="No. Telepon" class="text-brand-ink-faint text-xs uppercase tracking-wider font-display" />
                            <x-text-input id="customer_phone" name="customer_phone" required class="w-full input-field" value="{{ old('customer_phone') }}" />
                            <x-input-error :messages="$errors->get('customer_phone')" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <x-input-label for="notes" value="Catatan" class="text-brand-ink-faint text-xs uppercase tracking-wider font-display" />
                        <textarea id="notes" name="notes" class="input-field" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full !py-3 font-semibold">Bayar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>