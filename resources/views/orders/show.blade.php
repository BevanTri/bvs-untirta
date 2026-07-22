<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-brand-gold/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="font-display font-bold text-xl text-white tracking-tight">Detail Pesanan</h2>
                <p class="text-sm text-white/70 truncate">{{ $order->order_number }}</p>
            </div>
            @if($order->status === 'cancelled')
                <span class="badge badge-danger">Dibatalkan</span>
            @elseif($order->payment_status === 'paid')
                <span class="badge badge-success">Lunas</span>
            @elseif($order->payment_status === 'failed')
                <span class="badge badge-danger">Gagal</span>
            @else
                <span class="badge badge-warning">Pending</span>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-6">

            {{-- Order Info Card --}}
            <div class="card p-5 rounded-xl">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-brand-ink-faint uppercase tracking-widest font-semibold">Tanggal</p>
                        <p class="font-medium mt-1 text-brand-ink">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-ink-faint uppercase tracking-widest font-semibold">Status</p>
                        <p class="mt-1">{{ ucfirst($order->status) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-ink-faint uppercase tracking-widest font-semibold">Pelanggan</p>
                        <p class="font-medium mt-1 text-brand-ink">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-ink-faint uppercase tracking-widest font-semibold">Telepon</p>
                        <p class="font-medium mt-1 text-brand-ink">{{ $order->customer_phone }}</p>
                    </div>
                </div>
            </div>

            {{-- Timeline / Status History --}}
            @if($order->status === 'cancelled')
                <div class="card p-5 rounded-xl">
                    <h3 class="font-display font-semibold text-base text-brand-ink mb-4">Riwayat Status</h3>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-red-600">Dibatalkan</p>
                                <p class="text-xs text-brand-ink-faint">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $isPaid = $order->payment_status === 'paid';
                    $isCompleted = $order->status === 'completed';
                    $steps = [
                        ['label' => 'Pesanan Dibuat', 'done' => true],
                        ['label' => 'Diproses', 'done' => $isPaid],
                        ['label' => 'Selesai', 'done' => $isCompleted],
                    ];
                @endphp
                <div class="card p-5 rounded-xl">
                    <h3 class="font-display font-semibold text-base text-brand-ink mb-4">Riwayat Status</h3>
                    <div class="flex items-start gap-0 relative">
                        <div class="absolute top-[18px] left-0 right-0 h-0.5 bg-gray-200"></div>
                        @foreach($steps as $i => $step)
                            <div class="flex-1 flex flex-col items-center text-center relative z-10">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $step['done'] ? 'bg-brand-gold text-white shadow-sm' : 'bg-gray-100 text-brand-ink-faint' }}">
                                    @if($step['done'])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <span class="text-xs font-semibold">{{ $i + 1 }}</span>
                                    @endif
                                </div>
                                <p class="text-xs mt-2 font-medium {{ $step['done'] ? 'text-brand-ink' : 'text-brand-ink-faint' }}">{{ $step['label'] }}</p>
                                @if($i === 0)
                                    <p class="text-[10px] text-brand-ink-faint mt-0.5">{{ $order->created_at->format('d M Y') }}</p>
                                @endif
                            </div>
                        @endforeach
                        <div class="absolute top-[18px] left-0 right-0 h-0.5 bg-brand-gold transition-all duration-500" style="width: {{ $isCompleted ? '100%' : ($isPaid ? '50%' : '0%') }}"></div>
                    </div>
                </div>
            @endif

            {{-- Products List --}}
            <div class="card rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-display font-bold text-base text-brand-ink">Item Pesanan</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <div class="px-5 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-brand-gold/10 flex items-center justify-center shrink-0">
                                <span class="font-display font-bold text-sm text-brand-gold">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-brand-ink truncate">{{ $item->name }}</p>
                                <p class="text-xs text-brand-ink-faint">{{ $item->quantity }}x</p>
                            </div>
                            <p class="font-mono text-sm text-brand-ink font-medium">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="card p-5 rounded-xl">
                <div class="flex items-center justify-between py-2">
                    <p class="text-sm text-brand-ink-muted">Subtotal</p>
                    <p class="font-mono text-sm text-brand-ink">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
                <hr class="border-gray-100 my-2">
                <div class="flex items-center justify-between py-2">
                    <p class="font-display font-semibold text-brand-ink">Total</p>
                    <p class="font-mono font-bold text-lg text-brand-gold">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Action Area --}}
            @if($order->status === 'cancelled')
                <div class="card p-6 rounded-xl border-2 border-red-200 bg-red-50 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-lg text-red-700 mb-1">Pesanan Dibatalkan</p>
                    <p class="text-sm text-red-600">Pesanan ini telah dibatalkan. Pembayaran sudah tidak dapat dilakukan.</p>
                </div>
            @elseif($order->payment_status === 'paid')
                <div class="card p-6 rounded-xl text-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="font-display font-semibold text-lg text-green-700 mb-3">Pembayaran sudah lunas</p>
                    <a href="{{ route('orders.invoice', $order) }}" class="btn-outline btn-sm" target="_blank">Download Invoice (PDF)</a>
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-3">
                    @if($order->status === 'pending')
                        <button type="button"
                            onclick="var m=document.getElementById('cancel-order-{{ $order->id }}');m.style.display='flex';m.classList.remove('hidden');m.classList.add('flex')"
                            class="btn-danger btn-sm w-full sm:w-auto">
                            Batalkan Pesanan
                        </button>
                    @endif
                    <a href="{{ route('orders.pay', $order) }}" class="btn-primary w-full sm:w-auto text-center">Bayar Sekarang</a>
                </div>
            @endif

        </div>
    </div>

    <x-confirm-dialog
        name="cancel-order-{{ $order->id }}"
        title="Batalkan Pesanan?"
        message="Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan dan pesanan tidak dapat diproses kembali."
        :action="route('orders.cancel', $order)"
    />
</x-app-layout>
