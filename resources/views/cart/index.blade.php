<x-app-layout>
    <x-slot name="header"><div class="flex items-center gap-3"><h2 class="font-display font-bold text-xl text-brand-ink uppercase tracking-wide">Keranjang</h2><span class="text-brand-gold/70 text-sm font-display font-semibold tracking-wide font-mono">{{ $items->count() }} item</span></div></x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4">
            @if(session('success'))
                <div class="border border-green-500/30 bg-green-50 text-green-700 p-4 mb-4 text-sm font-mono rounded-xl">{{ session('success') }}</div>
            @endif

            @if($items->isEmpty())
                <div class="card p-12 text-center">
                    <div class="text-5xl mb-4 font-mono text-brand-ink-faint">[ ]</div>
                    <p class="text-brand-ink-muted mb-4 font-display uppercase tracking-wide">Keranjang kosong</p>
                    <a href="{{ route('products') }}" class="btn-primary inline-flex">Mulai Belanja</a>
                </div>
            @else
            @foreach($items as $item)
            <form id="delete-{{ $item->id }}" action="{{ route('cart.destroy', $item) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
            @endforeach
            <form method="POST" action="{{ route('cart.checkout') }}">
                @csrf
                <div class="card overflow-hidden mb-6">
                    @foreach($items as $item)
                    <div class="flex items-center gap-2 p-3 {{ !$loop->last ? 'border-b border-brand-border' : '' }}" data-price="{{ $item->unit_price }}">
                        <label class="flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="selected[]" value="{{ $item->id }}" checked class="cart-check w-4 h-4 text-brand-gold rounded" data-id="{{ $item->id }}">
                        </label>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-brand-ink truncate text-sm sm:text-base">{{ $item->name }}</p>
                            <p class="text-xs text-brand-ink-faint font-mono">
                                @if($item->itemable_type === 'App\Models\Product') PRODUK @else SERVICE @endif
                            </p>
                            <p class="text-xs sm:text-sm font-bold text-brand-gold mt-1 tabular-nums font-mono">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <input type="number" name="qty[{{ $item->id }}]" value="{{ $item->quantity }}" min="0" class="cart-qty w-14 sm:w-20 input-field text-center font-mono text-sm" data-id="{{ $item->id }}">
                            <p class="cart-line font-display font-bold text-brand-gold text-xs sm:text-sm w-20 sm:w-24 text-right tabular-nums font-mono" data-id="{{ $item->id }}">Rp{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</p>
                            <a href="#" onclick="event.preventDefault(); if(confirm('Hapus item ini?')) document.getElementById('delete-{{ $item->id }}').submit();" class="text-red-500 hover:text-red-700 text-lg font-bold leading-none px-1">&times;</a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="card p-6 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="font-display font-semibold text-brand-ink text-lg uppercase tracking-wide">Total</span>
                        <span id="cart-total" class="font-display font-bold text-2xl text-brand-gold tabular-nums font-mono">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <script>
                function recalc() {
                    let t = 0;
                    document.querySelectorAll('.cart-check').forEach(cb => {
                        if (!cb.checked) return;
                        const id = cb.dataset.id;
                        const row = cb.closest('[data-price]');
                        const price = parseFloat(row.dataset.price);
                        const qty = parseInt(document.querySelector('.cart-qty[data-id="' + id + '"]')?.value) || 0;
                        const line = price * qty;
                        t += line;
                        document.querySelector('.cart-line[data-id="' + id + '"]').textContent = 'Rp' + line.toLocaleString('id-ID');
                    });
                    document.getElementById('cart-total').textContent = 'Rp' + t.toLocaleString('id-ID');
                }
                document.querySelectorAll('.cart-check, .cart-qty').forEach(el => el.addEventListener('input', recalc));
                </script>

                <div class="card p-6">
                    <div class="mb-4">
                        <x-input-label for="notes" value="Catatan" class="text-brand-ink-faint text-xs uppercase tracking-wider font-display" />
                        <textarea id="notes" name="notes" class="input-field" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    <input type="hidden" name="customer_name" value="{{ Auth::user()->name }}">
                    <button type="submit" class="btn-primary w-full !py-3 font-semibold">Checkout & Bayar</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>