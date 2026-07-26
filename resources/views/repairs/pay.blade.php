<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-gold/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-brand-ink dark:text-zinc-100 uppercase tracking-wide">Pembayaran</h2>
                <p class="text-xs text-brand-ink-muted">Selesaikan pembayaran servis anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-10">
        <div class="max-w-2xl mx-auto px-4 space-y-6">
            @if(session('error'))
            <div class="card p-4 border-l-4 border-red-400">
                <p class="text-red-700 text-sm">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Riwayat Status --}}
            @php
                $isPaid = $order->payment_status === 'paid';
                $isSelesai = $order->status === 'selesai';
                $isProses = $order->status === 'proses';
                $steps = [
                    ['label' => 'Servis Dibuat', 'done' => true],
                    ['label' => 'Diproses', 'done' => $isProses || $isSelesai],
                    ['label' => 'Selesai', 'done' => $isSelesai && $isPaid],
                ];
                $progressWidth = $isSelesai && $isPaid ? '100%' : ($isProses || ($isSelesai && !$isPaid) ? '66%' : ($steps[1]['done'] ? '66%' : '33%'));
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
                    <div class="absolute top-[18px] left-0 right-0 h-0.5 bg-brand-gold transition-all duration-500" style="width: {{ $progressWidth }}"></div>
                </div>
            </div>

            {{-- Keluhan --}}
            <div class="card rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-brand-navy-3">
                    <h3 class="font-display font-bold text-base text-brand-ink dark:text-zinc-100">Keluhan</h3>
                </div>
                <div class="px-5 py-4">
                    <p class="text-sm text-brand-ink dark:text-zinc-100">{{ $order->complaint }}</p>
                </div>
            </div>

            {{-- Sparepart --}}
            <div class="card p-5">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-brand-border dark:border-brand-navy-3">
                    <div>
                        <p class="text-xs text-brand-ink-faint dark:text-zinc-500 uppercase tracking-wider font-semibold">Invoice</p>
                        <p class="font-semibold text-brand-ink dark:text-zinc-100">{{ $order->order_number }}</p>
                    </div>
                    <span class="badge-warning text-xs">Menunggu Pembayaran</span>
                </div>

                @if($order->items->count())
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-brand-ink dark:text-zinc-100">{{ $item->name }} <span class="text-brand-ink-faint dark:text-zinc-500">&times;{{ $item->quantity }}</span></span>
                        <span class="font-mono text-brand-ink dark:text-zinc-100">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="mt-4 pt-4 border-t border-brand-border dark:border-brand-navy-3 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-brand-ink-muted dark:text-zinc-400">Biaya Jasa</span>
                        <span class="font-mono text-brand-ink dark:text-zinc-100">Rp{{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @if($order->items->count())
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-brand-ink-muted dark:text-zinc-400">Total Sparepart</span>
                        <span class="font-mono text-brand-ink dark:text-zinc-100">Rp{{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-brand-border dark:border-brand-navy-3 flex items-center justify-between">
                    <span class="font-display font-semibold text-brand-ink dark:text-zinc-100 uppercase tracking-wide text-sm">Total</span>
                    <span class="font-display font-bold text-2xl text-brand-gold font-mono">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Payment Form --}}
            <form method="POST" action="{{ route('payment.repair', $order) }}" class="card p-5">
                @csrf
                <button type="submit" class="btn-primary w-full text-base py-4 shadow-lg shadow-brand-gold/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Konfirmasi Pembayaran
                </button>
                <p class="text-center text-xs text-brand-ink-faint dark:text-zinc-500 mt-4">Dengan melanjutkan, anda menyetujui ketentuan yang berlaku.</p>
            </form>
        </div>
    </div>
</x-app-layout>