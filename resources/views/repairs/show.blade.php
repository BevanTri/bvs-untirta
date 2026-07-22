<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <a href="{{ route('orders.history') }}" class="text-brand-blue hover:underline text-sm font-medium mb-4 inline-block">&larr; Kembali</a>

        <x-card class="mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="font-display font-bold text-2xl text-brand-ink">{{ $order->order_number }}</h1>
                    <p class="text-brand-ink-muted text-sm">{{ $order->date->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="badge {{ $order->status === 'selesai' ? 'badge-success' : ($order->status === 'dibatalkan' ? 'badge-danger' : ($order->status === 'proses' ? 'badge-info' : 'badge-warning')) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Kendaraan</p>
                    <p class="font-medium text-brand-ink">{{ $order->vehicle->plate_number }}</p>
                    <p class="text-sm text-brand-ink-muted">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</p>
                </div>
                <div>
                    <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Mekanik</p>
                    <p class="font-medium text-brand-ink">{{ $order->mechanic->name ?? 'Belum ditentukan' }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Keluhan</p>
                <p class="text-brand-ink bg-brand-warm p-4 rounded-xl">{{ $order->complaint }}</p>
            </div>

            @if($order->action)
            <div class="mb-6">
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Tindakan</p>
                <p class="text-brand-ink bg-brand-warm p-4 rounded-xl">{{ $order->action }}</p>
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
                            <td class="p-2 font-medium text-brand-ink whitespace-nowrap">{{ $item->name }}</td>
                            <td class="p-2 text-right whitespace-nowrap text-brand-ink">{{ $item->quantity }}</td>
                            <td class="p-2 text-right whitespace-nowrap font-mono tabular-nums text-brand-ink">Rp{{ number_format($item->price,0,',','.') }}</td>
                            <td class="p-2 text-right font-medium whitespace-nowrap font-mono tabular-nums text-brand-ink">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
            </div>
            @endif

            <div class="flex items-center justify-between border-t border-brand-border pt-4">
                <div>
                    <p class="text-sm text-brand-ink-muted">Biaya Jasa: <span class="font-mono tabular-nums">Rp{{ number_format($order->service_fee,0,',','.') }}</span></p>
                    <p class="text-lg font-bold text-brand-ink font-mono tabular-nums">Total: Rp{{ number_format($order->total,0,',','.') }}</p>
                </div>
                <div class="flex flex-col md:flex-row gap-3">
                    @if($order->payment_status === 'paid')
                    <a href="{{ route('repairs.invoice', $order) }}" class="btn-outline w-full md:w-auto" target="_blank">Invoice</a>
                    @elseif($order->status === 'dibatalkan')
                    <span class="text-sm text-red-600 font-medium">Pesanan telah dibatalkan</span>
                    @elseif($order->status === 'selesai')
                    <span class="text-sm text-brand-ink-muted font-medium">Pesanan selesai</span>
                    @else
                    <button type="button" onclick="var m=document.getElementById('cancel-repair-{{ $order->id }}');m.style.display='flex';m.classList.remove('hidden');m.classList.add('flex')" class="btn-danger w-full md:w-auto">Batalkan Pesanan</button>
                    <a href="{{ route('repairs.pay', $order) }}" class="btn-primary w-full md:w-auto">Bayar</a>
                    @endif
                </div>
            </div>
        </x-card>

        <x-confirm-dialog
            name="cancel-repair-{{ $order->id }}"
            title="Batalkan Pesanan?"
            message="Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan dan pesanan tidak dapat diproses kembali."
            :action="route('repairs.cancel', $order)"
        />

        @if($order->payments->count())
        <x-card>
            <h3 class="font-display font-bold text-brand-ink uppercase tracking-wide mb-4">Riwayat Pembayaran</h3>
            @foreach($order->payments as $p)
            <div class="flex items-center justify-between border-b border-brand-border/50 py-3">
                <div>
                    <p class="text-sm font-medium text-brand-ink">{{ ucfirst($p->method) }}</p>
                    <p class="text-xs text-brand-ink-muted">{{ $p->reference_id }} &middot; {{ $p->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="badge {{ $p->status === 'berhasil' ? 'badge-success' : ($p->status === 'gagal' ? 'badge-danger' : 'badge-warning') }}">
                    {{ $p->status === 'berhasil' ? 'Lunas' : ($p->status === 'gagal' ? 'Gagal' : 'Pending') }}
                </span>
            </div>
            @endforeach
        </x-card>
        @endif
    </div>
</x-app-layout>
