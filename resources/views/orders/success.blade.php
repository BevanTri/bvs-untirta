<x-app-layout>
    <x-slot name="header"><h2 class="font-display font-semibold text-xl text-brand-ink">Pembayaran Berhasil</h2></x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="card p-5">
                <div class="text-green-500 text-6xl mb-4">✓</div>
                <h3 class="font-display text-2xl font-bold text-brand-ink">Pembayaran Berhasil!</h3>
                <p class="text-brand-ink-muted mt-2">Pesanan {{ $order->order_number }} sedang diproses.</p>
                <a href="{{ route('orders.show', $order) }}" class="btn-primary mt-6 w-full md:w-auto inline-block">Lihat Detail Pesanan</a>
            </div>
        </div>
    </div>
</x-app-layout>
