<x-app-layout>
    <x-slot name="header"><h2 class="font-display font-bold text-xl text-white tracking-tight">Pesanan Saya</h2></x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            @forelse($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="card-hover p-5 mb-4 flex items-center justify-between gap-4 group">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-brand-black group-hover:text-brand-amber transition-colors">{{ $order->order_number }}</p>
                    <p class="text-sm text-brand-steel">{{ $order->created_at->format('d M Y H:i') }}</p>
                    <div class="flex gap-2 mt-1">
                        <span class="text-xs text-brand-steel">Status: <span class="font-semibold text-brand-black">{{ ucfirst($order->status) }}</span></span>
                        <span class="text-brand-steel">|</span>
                        <span class="{{ $order->payment_status === 'paid' ? 'badge-paid' : ($order->payment_status === 'failed' ? 'badge-failed' : 'badge-pending') }}">
                            {{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'failed' ? 'Gagal' : 'Pending') }}
                        </span>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <p class="font-display font-bold text-lg text-brand-black tabular-nums">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                    <p class="text-xs text-brand-amber group-hover:underline">Detail →</p>
                </div>
            </a>
            @empty
            <div class="card p-12 text-center">
                <div class="text-4xl mb-4">📋</div>
                <p class="text-brand-steel">Belum ada pesanan.</p>
                <a href="{{ route('products') }}" class="btn-primary mt-4 inline-flex">Mulai Belanja</a>
            </div>
            @endforelse
            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
