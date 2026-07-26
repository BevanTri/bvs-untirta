<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-gold/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-brand-ink dark:text-zinc-100 uppercase tracking-wide">Pembayaran</h2>
                <p class="text-xs text-brand-ink-muted">Selesaikan pembayaran pesanan anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-10">
        <div class="max-w-2xl mx-auto px-4">
            {{-- Order Summary --}}
            <div class="card p-5 mb-6">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-brand-border dark:border-brand-navy-3">
                    <div>
                        <p class="text-xs text-brand-ink-faint dark:text-zinc-500 uppercase tracking-wider font-semibold">Invoice</p>
                        <p class="font-semibold text-brand-ink dark:text-zinc-100">{{ $order->order_number }}</p>
                    </div>
                    <span class="badge-warning text-xs">Menunggu Pembayaran</span>
                </div>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-brand-ink dark:text-zinc-100">{{ $item->name }} <span class="text-brand-ink-faint dark:text-zinc-500">&times;{{ $item->quantity }}</span></span>
                        <span class="font-mono text-brand-ink dark:text-zinc-100">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-brand-border dark:border-brand-navy-3 flex items-center justify-between">
                    <span class="font-display font-semibold text-brand-ink dark:text-zinc-100 uppercase tracking-wide text-sm">Total</span>
                    <span class="font-display font-bold text-2xl text-brand-gold font-mono">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('payment.checkout', $order) }}" method="POST" class="card p-5 mb-6">
                @csrf
                <button type="submit" class="btn-primary w-full text-base py-4 shadow-lg shadow-brand-gold/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Konfirmasi Pembayaran
                </button>
                <p class="text-center text-xs text-brand-ink-faint dark:text-zinc-500 mt-4">Dengan melanjutkan, anda menyetujui ketentuan yang berlaku.</p>
            </form>
        </div>
    </div>
</x-app-layout>
