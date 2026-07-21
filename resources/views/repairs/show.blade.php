<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('orders.history') }}" class="text-brand-blue hover:underline text-sm font-medium mb-4 inline-block">&larr; Kembali</a>

        <div class="card p-5 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="font-display font-bold text-2xl text-brand-ink">{{ $order->order_number }}</h1>
                    <p class="text-brand-ink-muted text-sm">{{ $order->date->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $order->status === 'selesai' ? 'bg-green-100 text-green-700' : ($order->status === 'dibatalkan' ? 'bg-red-100 text-red-600' : ($order->status === 'proses' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Kendaraan</p>
                    <p class="font-medium">{{ $order->vehicle->plate_number }}</p>
                    <p class="text-sm text-brand-ink-muted">{{ $order->vehicle->brand }} {{ $order->vehicle->model }} ({{ $order->vehicle->year ?? '-' }})</p>
                </div>
                <div>
                    <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Mekanik</p>
                    <p class="font-medium">{{ $order->mechanic->name ?? 'Belum ditentukan' }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Keluhan</p>
                <p class="text-brand-ink bg-brand-warm p-3 rounded-lg">{{ $order->complaint }}</p>
            </div>

            @if($order->action)
            <div class="mb-6">
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Tindakan</p>
                <p class="text-brand-ink bg-brand-warm p-3 rounded-lg">{{ $order->action }}</p>
            </div>
            @endif

            @if($order->items->count())
            <div class="mb-6">
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-3">Sparepart</p>
                <div class="overflow-x-auto"><table class="w-full text-sm">
                    <thead><tr class="border-b border-brand-border"><th class="p-2 text-left font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Nama</th><th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Qty</th><th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Harga</th><th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="border-b border-brand-border/50">
                            <td class="p-2 font-medium whitespace-nowrap">{{ $item->name }}</td>
                            <td class="p-2 text-right whitespace-nowrap">{{ $item->quantity }}</td>
                            <td class="p-2 text-right whitespace-nowrap">Rp{{ number_format($item->price,0,',','.') }}</td>
                            <td class="p-2 text-right font-medium whitespace-nowrap">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
            </div>
            @endif

            <div class="flex items-center justify-between border-t border-brand-border pt-4">
                <div>
                    <p class="text-sm text-brand-ink-muted">Biaya Jasa: Rp{{ number_format($order->service_fee,0,',','.') }}</p>
                    <p class="text-lg font-bold text-brand-ink font-mono">Total: Rp{{ number_format($order->total,0,',','.') }}</p>
                </div>
                <div class="flex gap-2">
                    @if($order->payment_status !== 'paid' && $order->total > 0 && $order->status !== 'dibatalkan')
                    <a href="{{ route('repairs.pay', $order) }}" class="btn-primary">Bayar</a>
                    @endif
                    @if($order->payment_status === 'paid')
                    <a href="{{ route('repairs.invoice', $order) }}" class="btn-outline !border-brand-steel/30 !text-brand-steel" target="_blank">Invoice</a>
                    @endif
                </div>
            </div>
        </div>

        @if($order->payments->count())
        <div class="card p-5">
            <h3 class="font-display font-bold text-brand-ink uppercase tracking-wide mb-3">Riwayat Pembayaran</h3>
            @foreach($order->payments as $p)
            <div class="flex items-center justify-between border-b border-brand-border/50 py-2">
                <div>
                    <p class="text-sm font-medium">{{ ucfirst($p->method) }}</p>
                    <p class="text-xs text-brand-ink-muted">{{ $p->reference_id }} &middot; {{ $p->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $p->status === 'berhasil' ? 'bg-green-100 text-green-700' : ($p->status === 'gagal' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                    {{ $p->status === 'berhasil' ? 'Lunas' : ($p->status === 'gagal' ? 'Gagal' : 'Pending') }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-app-layout>
