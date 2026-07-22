<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-blue/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-brand-ink uppercase tracking-wide">Pesanan Saya</h2>
                <p class="text-xs text-brand-ink-muted">Riwayat pesanan dan servis</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-10">
        <div class="max-w-4xl mx-auto px-4">
            @forelse($orders as $order)
            @php $isRepair = get_class($order) === 'App\Models\RepairOrder'; @endphp
            <a href="{{ $isRepair ? route('repairs.show', $order) : route('orders.show', $order) }}" class="card-hover p-5 mb-4 flex items-center justify-between gap-4 group active:scale-[0.99] transition-all duration-200">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-semibold text-brand-ink group-hover:text-brand-blue transition-colors">{{ $order->order_number }}</p>
                        <span class="badge {{ $isRepair ? 'badge-warning' : 'badge-info' }} text-[10px] px-2 py-0.5">{{ $isRepair ? 'Servis' : 'Produk' }}</span>
                    </div>
                    <p class="text-sm text-brand-ink-muted mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        @if($order->status === 'cancelled')
                        <span class="badge-danger text-[10px] px-2 py-0.5">Dibatalkan</span>
                        @elseif($order->payment_status === 'paid')
                        <span class="badge-success text-[10px] px-2 py-0.5">Lunas</span>
                        @elseif($order->payment_status === 'failed')
                        <span class="badge-danger text-[10px] px-2 py-0.5">Gagal</span>
                        @else
                        <span class="badge-warning text-[10px] px-2 py-0.5">Pending</span>
                        @endif
                        <span class="text-xs text-brand-ink-faint">Status: <span class="font-semibold text-brand-ink">{{ ucfirst($order->status) }}</span></span>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <p class="font-bold text-lg text-brand-gold font-mono tabular-nums">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                    <p class="text-xs text-brand-blue group-hover:underline mt-1">Detail &rarr;</p>
                </div>
            </a>
            @empty
            <x-empty-state
                icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                title="Belum ada pesanan"
                description="Pesanan dan riwayat servis akan muncul di sini."
                actionLabel="Mulai Belanja"
                actionUrl="{{ route('products') }}"
            />
            @endforelse
            @if(method_exists($orders, 'links'))
            <div class="mt-6">{{ $orders->links('vendor.pagination.tailwind') }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
