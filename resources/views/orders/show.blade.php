<x-app-layout>
    <x-slot name="header"><div class="flex items-center gap-3"><h2 class="font-display font-bold text-xl text-white tracking-tight">{{ $order->order_number }}</h2><span class="{{ $order->payment_status === 'paid' ? 'badge-paid' : ($order->payment_status === 'failed' ? 'badge-failed' : 'badge-pending') }}">{{ $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'failed' ? 'Gagal' : 'Pending') }}</span></div></x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="card p-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-brand-steel uppercase tracking-widest font-semibold">Tanggal</p>
                        <p class="font-medium mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-steel uppercase tracking-widest font-semibold">Status</p>
                        <p class="font-medium mt-0.5">{{ ucfirst($order->status) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-steel uppercase tracking-widest font-semibold">Pelanggan</p>
                        <p class="font-medium mt-0.5">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-steel uppercase tracking-widest font-semibold">Telepon</p>
                        <p class="font-medium mt-0.5">{{ $order->customer_phone }}</p>
                    </div>
                </div>
            </div>

            <div class="card overflow-hidden mb-6">
                <div class="p-5 border-b border-brand-steel/10">
                    <h3 class="font-display font-bold text-lg text-brand-black">Item Pesanan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b border-brand-steel/10 bg-brand-warm"><th class="text-left p-3 font-semibold text-brand-steel text-xs uppercase tracking-widest">Item</th><th class="text-center p-3 font-semibold text-brand-steel text-xs uppercase tracking-widest">Qty</th><th class="text-right p-3 font-semibold text-brand-steel text-xs uppercase tracking-widest">Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-b border-brand-steel/10">
                                <td class="p-3 font-medium">{{ $item->name }}</td>
                                <td class="p-3 text-center">{{ $item->quantity }}</td>
                                <td class="p-3 text-right font-medium tabular-nums">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="2" class="p-3 text-right font-semibold">Total</td><td class="p-3 text-right font-display font-bold text-lg text-brand-amber tabular-nums">Rp{{ number_format($order->total, 0, ',', '.') }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($order->payment_status !== 'paid')
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.pay', $order) }}" class="btn-primary !py-3 font-semibold text-center flex-1 min-w-[150px]">Bayar Sekarang</a>
                <form method="POST" action="{{ route('orders.check-payment', $order) }}">
                    @csrf
                    <button type="submit" class="btn-outline !py-3 font-semibold whitespace-nowrap">Cek Pembayaran</button>
                </form>
                <a href="{{ route('orders.invoice', $order) }}" class="btn-outline !py-3 text-sm" target="_blank">Invoice (PDF)</a>
            </div>
            @else
            <div class="card p-6 text-center">
                <div class="text-4xl mb-3">✅</div>
                <p class="font-semibold text-green-700 mb-3">Pembayaran sudah lunas</p>
                <a href="{{ route('orders.invoice', $order) }}" class="btn-outline !py-2 text-sm" target="_blank">Download Invoice (PDF)</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
