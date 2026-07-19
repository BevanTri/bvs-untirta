<x-app-layout>
    <x-slot name="header"><h2 class="font-display font-bold text-xl text-white tracking-tight">Pembayaran</h2></x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4">
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-4 text-sm">{{ session('error') }}</div>
            @endif
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4 mb-4 text-sm">{{ session('success') }}</div>
            @endif

            <div class="card p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-brand-steel uppercase tracking-widest font-semibold">No. Pesanan</p>
                        <p class="font-display font-bold text-xl text-brand-black mt-0.5">{{ $order->order_number }}</p>
                    </div>
                    <span class="{{ $order->payment_status === 'paid' ? 'badge-paid' : ($order->payment_status === 'failed' ? 'badge-failed' : 'badge-pending') }}">
                        {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'failed' ? 'Gagal' : 'Menunggu') }}
                    </span>
                </div>
                <div class="border-t border-brand-steel/20 pt-4">
                    <p class="text-xs text-brand-steel uppercase tracking-widest font-semibold">Total Bayar</p>
                    <p class="font-display text-4xl font-bold text-brand-black mt-1 tabular-nums">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($order->payment_status === 'paid')
            <div class="card p-8 text-center">
                <div class="text-5xl mb-4">✅</div>
                <p class="font-semibold text-lg text-green-700">Pembayaran Berhasil!</p>
                <p class="text-sm text-brand-steel mt-2">Pesanan kamu sedang diproses.</p>
                <a href="{{ route('orders.history') }}" class="btn-primary mt-6 inline-flex">Lihat Pesanan</a>
            </div>
            @elseif($order->payment_status === 'failed')
            <div class="card p-8 text-center">
                <div class="text-5xl mb-4">❌</div>
                <p class="font-semibold text-lg text-red-700">Pembayaran Gagal</p>
                <p class="text-sm text-brand-steel mt-2">Silakan coba lagi.</p>
                <a href="{{ route('orders.pay', $order) }}" class="btn-primary mt-6 inline-flex">Coba Lagi</a>
            </div>
            @else
            <div class="card p-6 text-center">
                <div class="text-5xl mb-4">⏳</div>
                <p class="text-brand-steel text-sm mb-6">Klik tombol di bawah untuk melanjutkan pembayaran melalui iPaymu.</p>
                <form action="{{ route('payment.checkout', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary w-full !py-3 font-semibold text-base">Bayar via iPaymu</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
