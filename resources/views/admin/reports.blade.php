<x-admin-layout>
    <x-slot name="title">Laporan</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="card p-5">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-semibold text-brand-ink-muted uppercase tracking-widest">Periode:</span>
                <div class="flex gap-1 flex-wrap">
                    @foreach(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $key => $label)
                    <a href="{{ route('admin.reports', ['period' => $key]) }}" class="{{ $period === $key ? 'btn-secondary btn-xs' : 'btn-ghost btn-xs' }}">{{ $label }}</a>
                    @endforeach
                </div>
                <a href="{{ route('admin.reports.export', ['period' => $period]) }}" class="btn-outline btn-xs ml-auto">Export CSV</a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card p-5 border-l-4 border-brand-gold">
                <p class="text-[11px] text-brand-ink-faint dark:text-zinc-500 uppercase tracking-[0.15em] font-display font-semibold">Pendapatan</p>
                <p class="font-display text-lg sm:text-2xl font-bold text-brand-ink dark:text-zinc-100 mt-0.5 font-mono tabular-nums">Rp{{ number_format($totalRevenue,0,',','.') }}</p>
            </div>
            <div class="card p-5 border-l-4 border-brand-blue">
                <p class="text-[11px] text-brand-ink-faint dark:text-zinc-500 uppercase tracking-[0.15em] font-display font-semibold">Pesanan Produk</p>
                <p class="font-display text-lg sm:text-2xl font-bold text-brand-blue dark:text-brand-blue-light mt-0.5 font-mono tabular-nums">{{ $orderCount }} <span class="text-xs font-normal text-brand-ink-muted">Rp{{ number_format($orderRevenue,0,',','.') }}</span></p>
            </div>
            <div class="card p-5 border-l-4 border-sky-500">
                <p class="text-[11px] text-brand-ink-faint dark:text-zinc-500 uppercase tracking-[0.15em] font-display font-semibold">Servis Workshop</p>
                <p class="font-display text-lg sm:text-2xl font-bold text-sky-600 dark:text-sky-400 mt-0.5 font-mono tabular-nums">{{ $repairCount }} <span class="text-xs font-normal text-brand-ink-muted">Rp{{ number_format($repairRevenue,0,',','.') }}</span></p>
            </div>
            <div class="card p-5 border-l-4 border-emerald-600">
                <p class="text-[11px] text-brand-ink-faint dark:text-zinc-500 uppercase tracking-[0.15em] font-display font-semibold">Total Transaksi</p>
                <p class="font-display text-lg sm:text-2xl font-bold text-emerald-700 dark:text-emerald-400 mt-0.5 font-mono tabular-nums">{{ $orderCount + $repairCount }}</p>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="card p-0 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-brand-border dark:border-brand-navy-3 bg-brand-warm/30 dark:bg-brand-navy-3/20">
                    <h3 class="font-display font-bold text-brand-ink dark:text-zinc-100 text-sm tracking-wide">Pesanan Produk</h3>
                    <span class="text-xs text-brand-ink-muted dark:text-zinc-400 font-medium">{{ $orderCount }} transaksi</span>
                </div>
                @if($orders->isEmpty())
                <div class="p-6">
                    <x-empty-state message="Tidak ada pesanan produk di periode ini" />
                </div>
                @else
                <div class="table-wrap overflow-x-auto">
                    <table class="table-base">
                        <thead>
                            <tr class="border-b bg-brand-warm/70 dark:bg-brand-navy-3/40 text-brand-ink-faint dark:text-zinc-500 text-xs uppercase tracking-widest">
                                <th class="px-5 py-3 text-left font-semibold">Invoice</th>
                                <th class="px-5 py-3 text-left font-semibold">Customer</th>
                                <th class="px-5 py-3 text-right font-semibold">Total</th>
                                <th class="px-5 py-3 text-center font-semibold">Status</th>
                                <th class="px-5 py-3 text-center font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $o)
                            <tr class="border-b border-brand-border/30 dark:border-brand-navy-3/50 hover:bg-brand-warm/40 dark:hover:bg-brand-navy-3/20 transition-colors">
                                <td class="px-5 py-3 text-left font-mono text-xs whitespace-nowrap">{{ $o->order_number }}</td>
                                <td class="px-5 py-3 text-left whitespace-nowrap">{{ $o->user->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-right font-medium font-mono tabular-nums whitespace-nowrap">Rp{{ number_format($o->total,0,',','.') }}</td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @php $sc = $o->status === 'completed' ? 'badge-success' : ($o->status === 'cancelled' ? 'badge-danger' : 'badge-warning') @endphp
                                    <span class="badge {{ $sc }}">{{ $o->status === 'completed' ? 'Selesai' : ($o->status === 'cancelled' ? 'Batal' : ($o->status === 'processing' ? 'Proses' : 'Pending')) }}</span>
                                </td>
                                <td class="px-5 py-3 text-center text-brand-ink-muted dark:text-zinc-400 text-xs whitespace-nowrap">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div class="relative">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-brand-border dark:border-brand-navy-3"></div></div>
                <div class="relative flex justify-center"><span class="bg-brand-warm dark:bg-brand-navy px-4 text-xs font-semibold text-brand-ink-muted dark:text-zinc-400 uppercase tracking-widest">Servis Workshop</span></div>
            </div>

            <div class="card p-0 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-brand-border dark:border-brand-navy-3 bg-brand-warm/30 dark:bg-brand-navy-3/20">
                    <h3 class="font-display font-bold text-brand-ink dark:text-zinc-100 text-sm tracking-wide">Servis Workshop</h3>
                    <span class="text-xs text-brand-ink-muted dark:text-zinc-400 font-medium">{{ $repairCount }} transaksi</span>
                </div>
                @if($repairs->isEmpty())
                <div class="p-6">
                    <x-empty-state message="Tidak ada servis di periode ini" />
                </div>
                @else
                <div class="table-wrap overflow-x-auto">
                    <table class="table-base">
                        <thead>
                            <tr class="border-b bg-brand-warm/70 dark:bg-brand-navy-3/40 text-brand-ink-faint dark:text-zinc-500 text-xs uppercase tracking-widest">
                                <th class="px-5 py-3 text-left font-semibold">No. Servis</th>
                                <th class="px-5 py-3 text-left font-semibold">Pelanggan</th>
                                <th class="px-5 py-3 text-left font-semibold">Kendaraan</th>
                                <th class="px-5 py-3 text-right font-semibold">Total</th>
                                <th class="px-5 py-3 text-center font-semibold">Status</th>
                                <th class="px-5 py-3 text-center font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($repairs as $r)
                            <tr class="border-b border-brand-border/30 dark:border-brand-navy-3/50 hover:bg-brand-warm/40 dark:hover:bg-brand-navy-3/20 transition-colors">
                                <td class="px-5 py-3 text-left font-mono text-xs whitespace-nowrap">{{ $r->order_number }}</td>
                                <td class="px-5 py-3 text-left whitespace-nowrap">{{ $r->customer->name }}</td>
                                <td class="px-5 py-3 text-left text-brand-ink-muted dark:text-zinc-400 text-xs whitespace-nowrap">{{ $r->vehicle->plate_number }} ({{ $r->vehicle->brand }})</td>
                                <td class="px-5 py-3 text-right font-medium font-mono tabular-nums whitespace-nowrap">Rp{{ number_format($r->total,0,',','.') }}</td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @php $sc = $r->status === 'selesai' ? 'badge-success' : ($r->status === 'dibatalkan' ? 'badge-danger' : ($r->status === 'proses' ? 'badge-info' : 'badge-warning')) @endphp
                                    <span class="badge {{ $sc }}">{{ ucfirst($r->status) }}</span>
                                </td>
                                <td class="px-5 py-3 text-center text-brand-ink-muted dark:text-zinc-400 text-xs whitespace-nowrap">{{ $r->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
