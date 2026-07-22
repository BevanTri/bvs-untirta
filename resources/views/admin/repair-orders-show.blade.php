<x-admin-layout>
    <x-slot name="title">Detail Servis</x-slot>

    <x-card class="max-w-2xl mb-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="font-display font-bold text-2xl text-brand-ink">{{ $order->order_number }}</h2>
                <p class="text-brand-ink-muted text-sm">{{ $order->date->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $order->status === 'selesai' ? 'bg-green-100 text-green-700' : ($order->status === 'dibatalkan' ? 'bg-red-100 text-red-600' : ($order->status === 'proses' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} ml-2">
                    {{ $order->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Pelanggan</p>
                <p class="font-medium">{{ $order->customer->name }}</p>
            </div>
            <div>
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Kendaraan</p>
                <p class="font-medium">{{ $order->vehicle->plate_number }}</p>
                <p class="text-sm text-brand-ink-muted">{{ $order->vehicle->brand }} {{ $order->vehicle->model }} ({{ $order->vehicle->year ?? '-' }})</p>
            </div>
            <div>
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Mekanik</p>
                <p class="font-medium">{{ $order->mechanic->name ?? '—' }}</p>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Keluhan</p>
            <p class="text-brand-ink bg-brand-warm p-3 rounded-xl">{{ $order->complaint }}</p>
        </div>

        @if($order->action)
        <div class="mb-6">
            <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Tindakan</p>
            <p class="text-brand-ink bg-brand-warm p-3 rounded-xl">{{ $order->action }}</p>
        </div>
        @endif

        @if($order->items->count())
        <div class="mb-6">
            <p class="font-display font-bold text-brand-ink uppercase tracking-wide mb-3 text-sm">Sparepart Dipakai</p>
            <div class="overflow-x-auto rounded-xl border border-brand-border"><table class="w-full text-sm">
                <thead><tr class="border-b border-brand-border bg-brand-warm"><th class="p-2 text-left font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Nama</th><th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Qty</th><th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Harga</th><th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Subtotal</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-brand-border/50">
                        <td class="p-2 font-medium">{{ $item->name }}</td>
                        <td class="p-2 text-right">{{ $item->quantity }}</td>
                        <td class="p-2 text-right">Rp{{ number_format($item->price,0,',','.') }}</td>
                        <td class="p-2 text-right font-medium">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
        @endif

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-t border-brand-border pt-5">
            <div>
                <p class="text-sm text-brand-ink-muted">Biaya Jasa: Rp{{ number_format($order->service_fee,0,',','.') }}</p>
                <p class="text-lg font-bold text-brand-ink font-mono">Total: Rp{{ number_format($order->total,0,',','.') }}</p>
            </div>
            <div class="flex gap-2">
                @if($order->status === 'menunggu')
                <form method="POST" action="{{ route('admin.repair-orders.update', $order) }}" class="inline">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                    <input type="hidden" name="vehicle_id" value="{{ $order->vehicle_id }}">
                    <input type="hidden" name="date" value="{{ $order->date->format('Y-m-d') }}">
                    <input type="hidden" name="complaint" value="{{ $order->complaint }}">
                    <input type="hidden" name="service_fee" value="{{ $order->service_fee }}">
                    <input type="hidden" name="mechanic_id" value="{{ $order->mechanic_id }}">
                    <input type="hidden" name="status" value="proses">
                    <button type="submit" class="btn-primary text-sm">Terima Proses</button>
                </form>
                @endif
                @if($order->status === 'proses')
                <form method="POST" action="{{ route('admin.repair-orders.update', $order) }}" class="inline">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                    <input type="hidden" name="vehicle_id" value="{{ $order->vehicle_id }}">
                    <input type="hidden" name="date" value="{{ $order->date->format('Y-m-d') }}">
                    <input type="hidden" name="complaint" value="{{ $order->complaint }}">
                    <input type="hidden" name="service_fee" value="{{ $order->service_fee }}">
                    <input type="hidden" name="mechanic_id" value="{{ $order->mechanic_id }}">
                    <input type="hidden" name="status" value="selesai">
                    <button type="submit" class="btn-primary text-sm">Selesai</button>
                </form>
                @endif
            </div>
        </div>
    </x-card>

    @if($order->payments->count())
    <x-card class="max-w-2xl">
        <h3 class="font-display font-bold text-brand-ink uppercase tracking-wide mb-3">Pembayaran</h3>
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
    </x-card>
    @endif
</x-admin-layout>
