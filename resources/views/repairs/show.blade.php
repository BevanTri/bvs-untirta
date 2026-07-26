<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-brand-gold/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="font-display font-bold text-xl text-brand-ink dark:text-white tracking-tight">Detail Servis</h2>
                <p class="text-sm text-brand-ink-muted dark:text-white/70 truncate">{{ $order->order_number }}</p>
            </div>
            @if($order->status === 'dibatalkan')
                <span class="badge badge-danger">Dibatalkan</span>
            @elseif($order->payment_status === 'paid')
                <span class="badge badge-success">Lunas</span>
            @else
                <span class="badge badge-warning">{{ ucfirst($order->status) }}</span>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-6">
            <a href="{{ route('orders.history') }}" class="text-brand-blue dark:text-brand-blue-light hover:underline text-sm font-medium inline-block">&larr; Kembali</a>

            {{-- Info Card --}}
            <div class="card p-5 rounded-xl">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-brand-ink-faint dark:text-zinc-500 uppercase tracking-widest font-semibold">Tanggal</p>
                        <p class="font-medium mt-1 text-brand-ink dark:text-zinc-100">{{ $order->date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-ink-faint dark:text-zinc-500 uppercase tracking-widest font-semibold">Status</p>
                        <p class="mt-1">{{ ucfirst($order->status) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-ink-faint dark:text-zinc-500 uppercase tracking-widest font-semibold">Kendaraan</p>
                        <p class="font-medium mt-1 text-brand-ink dark:text-zinc-100">{{ $order->vehicle->plate_number }}</p>
                        <p class="text-xs text-brand-ink-muted dark:text-zinc-400">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-ink-faint dark:text-zinc-500 uppercase tracking-widest font-semibold">Mekanik</p>
                        <p class="font-medium mt-1 text-brand-ink dark:text-zinc-100">{{ $order->mechanic->name ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>
            </div>

            {{-- Timeline / Status History --}}
            @if($order->status === 'dibatalkan')
                <div class="card p-5 rounded-xl">
                    <h3 class="font-display font-semibold text-base text-brand-ink dark:text-zinc-100 mb-4">Riwayat Status</h3>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-red-600">Dibatalkan</p>
                                <p class="text-xs text-brand-ink-faint dark:text-zinc-500">{{ $order->date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $isPaid = $order->payment_status === 'paid';
                    $isSelesai = $order->status === 'selesai';
                    $isProses = $order->status === 'proses';
                    $steps = [
                        ['label' => 'Servis Dibuat', 'done' => true],
                        ['label' => 'Diproses', 'done' => $isProses || $isSelesai],
                        ['label' => 'Selesai', 'done' => $isSelesai && $isPaid],
                    ];
                @endphp
                <div class="card p-5 rounded-xl">
                    <h3 class="font-display font-semibold text-base text-brand-ink dark:text-zinc-100 mb-4">Riwayat Status</h3>
                    <div class="flex items-start gap-0 relative">
                        <div class="absolute top-[18px] left-0 right-0 h-0.5 bg-gray-200"></div>
                        @foreach($steps as $i => $step)
                            <div class="flex-1 flex flex-col items-center text-center relative z-10">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $step['done'] ? 'bg-brand-gold text-white shadow-sm' : 'bg-gray-100 text-brand-ink-faint dark:text-zinc-500' }}">
                                    @if($step['done'])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span class="text-xs font-semibold">{{ $i + 1 }}</span>
                                    @endif
                                </div>
                                <p class="text-xs mt-2 font-medium {{ $step['done'] ? 'text-brand-ink dark:text-zinc-100' : 'text-brand-ink-faint dark:text-zinc-500' }}">{{ $step['label'] }}</p>
                                @if($i === 0)
                                    <p class="text-[10px] text-brand-ink-faint dark:text-zinc-500 mt-0.5">{{ $order->date->format('d M Y') }}</p>
                                @endif
                            </div>
                        @endforeach
                        @php
                            $progressWidth = $isSelesai && $isPaid ? '100%' : ($isProses || ($isSelesai && !$isPaid) ? '66%' : ($steps[1]['done'] ? '66%' : '33%'));
                        @endphp
                        <div class="absolute top-[18px] left-0 right-0 h-0.5 bg-brand-gold transition-all duration-500" style="width: {{ $progressWidth }}"></div>
                    </div>
                </div>
            @endif

            {{-- Keluhan --}}
            <div class="card rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-brand-navy-3">
                    <h3 class="font-display font-bold text-base text-brand-ink dark:text-zinc-100">Keluhan</h3>
                </div>
                <div class="px-5 py-4">
                    <p class="text-sm text-brand-ink dark:text-zinc-100">{{ $order->complaint }}</p>
                </div>
            </div>

            {{-- Tindakan --}}
            @if($order->action)
            <div class="card rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-brand-navy-3">
                    <h3 class="font-display font-bold text-base text-brand-ink dark:text-zinc-100">Tindakan</h3>
                </div>
                <div class="px-5 py-4">
                    <p class="text-sm text-brand-ink dark:text-zinc-100">{{ $order->action }}</p>
                </div>
            </div>
            @endif

            {{-- Sparepart --}}
            @if($order->items->count())
            <div class="card rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-brand-navy-3">
                    <h3 class="font-display font-bold text-base text-brand-ink dark:text-zinc-100">Sparepart</h3>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-brand-navy-3/50">
                    @foreach($order->items as $item)
                        <div class="px-5 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-brand-gold/10 flex items-center justify-center shrink-0">
                                <span class="font-display font-bold text-sm text-brand-gold">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-brand-ink dark:text-zinc-100 truncate">{{ $item->name }}</p>
                                <p class="text-xs text-brand-ink-faint dark:text-zinc-500">{{ $item->quantity }}x &times; Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-mono text-sm text-brand-ink dark:text-zinc-100 font-medium">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Payment Summary --}}
            <div class="card p-5 rounded-xl">
                <div class="flex items-center justify-between py-2">
                    <p class="text-sm text-brand-ink-muted dark:text-zinc-400">Biaya Jasa</p>
                    <p class="font-mono text-sm text-brand-ink dark:text-zinc-100">Rp{{ number_format($order->service_fee, 0, ',', '.') }}</p>
                </div>
                @if($order->items->count())
                <div class="flex items-center justify-between py-2">
                    <p class="text-sm text-brand-ink-muted dark:text-zinc-400">Total Sparepart</p>
                    <p class="font-mono text-sm text-brand-ink dark:text-zinc-100">Rp{{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</p>
                </div>
                @endif
                <hr class="border-gray-100 dark:border-brand-navy-3 my-2">
                <div class="flex items-center justify-between py-2">
                    <p class="font-display font-semibold text-brand-ink dark:text-zinc-100">Total</p>
                    <p class="font-mono font-bold text-lg text-brand-gold">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Action Area --}}
            @if($order->payment_status === 'paid')
                <div class="card p-6 rounded-xl text-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-lg text-green-700 mb-3">Pembayaran sudah lunas</p>
                    <a href="{{ route('repairs.invoice', $order) }}" class="btn-outline btn-sm" target="_blank">Download Invoice (PDF)</a>
                </div>
            @elseif($order->status === 'dibatalkan')
                <div class="card p-6 rounded-xl border-2 border-red-200 bg-red-50 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-lg text-red-700 mb-1">Pesanan Dibatalkan</p>
                    <p class="text-sm text-red-600">Pesanan ini telah dibatalkan. Pembayaran sudah tidak dapat dilakukan.</p>
                </div>
            @elseif($order->status === 'selesai')
                <div class="card p-5 rounded-xl text-center">
                    <div class="w-12 h-12 rounded-full bg-brand-gold/10 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-base text-brand-ink dark:text-zinc-100">Servis telah selesai</p>
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button"
                        onclick="var m=document.getElementById('cancel-repair-{{ $order->id }}');m.style.display='flex';m.classList.remove('hidden');m.classList.add('flex')"
                        class="btn-danger btn-sm w-full sm:w-auto">
                        Batalkan Pesanan
                    </button>
                    <a href="{{ route('repairs.pay', $order) }}" class="btn-primary w-full sm:w-auto text-center">Bayar Sekarang</a>
                </div>
            @endif

        </div>
    </div>

    <x-confirm-dialog
        name="cancel-repair-{{ $order->id }}"
        title="Batalkan Pesanan?"
        message="Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan dan pesanan tidak dapat diproses kembali."
        :action="route('repairs.cancel', $order)"
    />

    @if($order->payments->count())
    <div class="max-w-4xl mx-auto px-4 pb-8">
        <div class="card p-5 rounded-xl">
            <h3 class="font-display font-bold text-brand-ink dark:text-zinc-100 uppercase tracking-wide mb-4">Riwayat Pembayaran</h3>
            @foreach($order->payments as $p)
            <div class="flex items-center justify-between border-b border-brand-border/50 dark:border-brand-navy-3/50 py-3 last:border-b-0">
                <div>
                    <p class="text-sm font-medium text-brand-ink dark:text-zinc-100">{{ ucfirst($p->method) }}</p>
                    <p class="text-xs text-brand-ink-muted dark:text-zinc-400">{{ $p->reference_id }} &middot; {{ $p->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="badge {{ $p->status === 'berhasil' ? 'badge-success' : ($p->status === 'gagal' ? 'badge-danger' : 'badge-warning') }}">
                    {{ $p->status === 'berhasil' ? 'Lunas' : ($p->status === 'gagal' ? 'Gagal' : 'Pending') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</x-app-layout>