<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="text-right mb-4">
            <button onclick="window.print()" class="btn-primary">Cetak</button>
        </div>

        <div class="card p-8" id="invoice">
            <div class="flex items-center justify-between mb-8 border-b border-brand-border pb-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-10 w-auto">
                    <div>
                        <h1 class="font-display font-bold text-xl text-brand-navy">BVS Bengkel Untirta</h1>
                        <p class="text-xs text-brand-ink-muted">Bengkel Mobil & Motor</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-mono font-bold text-lg">{{ $order->order_number }}</p>
                    <p class="text-sm text-brand-ink-muted">{{ $order->date->format('d M Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Pelanggan</p>
                    <p class="font-medium">{{ $order->customer->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Kendaraan</p>
                    <p class="font-medium">{{ $order->vehicle->plate_number }}</p>
                    <p class="text-sm text-brand-ink-muted">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</p>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Keluhan</p>
                <p class="text-sm">{{ $order->complaint }}</p>
            </div>

            @if($order->action)
            <div class="mb-6">
                <p class="text-xs text-brand-ink-faint uppercase tracking-wide font-semibold mb-1">Tindakan</p>
                <p class="text-sm">{{ $order->action }}</p>
            </div>
            @endif

            @if($order->status !== 'selesai')
            <div class="mb-6">
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $order->status === 'proses' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700' }}">
                    Status: {{ ucfirst($order->status) }}
                </span>
            </div>
            @endif

            @if($order->items->count())
            <div class="overflow-x-auto"><table class="w-full text-sm mb-6">
                <thead>
                    <tr class="border-b border-brand-border">
                        <th class="p-2 text-left font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Sparepart</th>
                        <th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Qty</th>
                        <th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Harga</th>
                        <th class="p-2 text-right font-semibold text-brand-ink-faint text-xs uppercase whitespace-nowrap">Subtotal</th>
                    </tr>
                </thead>
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
            @endif

            <div class="border-t border-brand-border pt-4 text-right">
                <p class="text-sm text-brand-ink-muted">Biaya Jasa: Rp{{ number_format($order->service_fee,0,',','.') }}</p>
                <p class="text-xl font-bold text-brand-ink font-mono mt-1">Total: Rp{{ number_format($order->total,0,',','.') }}</p>
                @if($order->payment_status === 'paid')
                <p class="text-xs text-green-600 font-semibold mt-2">✓ Lunas</p>
                @endif
            </div>

            <div class="mt-8 pt-4 border-t border-brand-border text-center">
                <p class="text-xs text-brand-ink-faint">Terima kasih telah menggunakan layanan BVS Bengkel Untirta</p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body { background: white; }
            .btn-primary, nav, footer, #mobile-sidebar, .md\\:hidden { display: none !important; }
            #invoice { box-shadow: none; border: none; padding: 0; }
        }
    </style>
</x-app-layout>
