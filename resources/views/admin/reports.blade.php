<x-admin-layout>
    <x-slot name="title">Laporan</x-slot>

    <div class="card p-3 sm:p-4 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-brand-steel uppercase tracking-widest">Periode:</span>
            <div class="flex gap-1 flex-wrap">
                @foreach(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $key => $label)
                <a href="{{ route('admin.reports', ['period' => $key]) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $period === $key ? 'bg-brand-navy text-white' : 'bg-brand-warm text-brand-ink-muted hover:bg-brand-border' }}">{{ $label }}</a>
                @endforeach
            </div>
            <a href="{{ route('admin.reports.export', ['period' => $period]) }}" class="btn-outline !py-1.5 !px-3 !text-xs shrink-0 min-h-[36px]">Export CSV</a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="card p-3 sm:p-4 border-l-4 border-brand-gold">
            <p class="text-[11px] text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Pendapatan</p>
            <p class="font-display text-lg sm:text-2xl font-bold text-brand-ink mt-0.5">Rp{{ number_format($totalRevenue,0,',','.') }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-blue-500">
            <p class="text-[11px] text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Pesanan Produk</p>
            <p class="font-display text-lg sm:text-2xl font-bold text-blue-600 mt-0.5">{{ $orderCount }} <span class="text-xs font-normal text-brand-ink-muted">Rp{{ number_format($orderRevenue,0,',','.') }}</span></p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-sky-500">
            <p class="text-[11px] text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Servis Workshop</p>
            <p class="font-display text-lg sm:text-2xl font-bold text-sky-600 mt-0.5">{{ $repairCount }} <span class="text-xs font-normal text-brand-ink-muted">Rp{{ number_format($repairRevenue,0,',','.') }}</span></p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-emerald-600">
            <p class="text-[11px] text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Total Transaksi</p>
            <p class="font-display text-lg sm:text-2xl font-bold text-emerald-700 mt-0.5">{{ $orderCount + $repairCount }}</p>
        </div>
    </div>

    <div class="flex flex-col gap-16">
        <div class="card">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-brand-border bg-brand-warm/30">
                <h3 class="font-display font-bold text-brand-ink uppercase text-sm tracking-wide">Pesanan Produk</h3>
                <span class="text-xs text-brand-ink-muted font-medium">{{ $orderCount }} transaksi</span>
            </div>
            @if($orders->isEmpty())
            <div class="py-12 text-center text-sm text-brand-ink-muted">Tidak ada pesanan produk di periode ini</div>
            @else
            <div class="overflow-x-auto"><table class="w-full text-sm">
                <thead><tr class="border-b bg-brand-warm/70 text-brand-ink-faint text-xs uppercase tracking-widest"><th class="px-3 sm:px-5 py-3 text-left font-semibold">Invoice</th><th class="px-3 sm:px-5 py-3 text-left font-semibold hidden sm:table-cell">Customer</th><th class="px-3 sm:px-5 py-3 text-right font-semibold">Total</th><th class="px-3 sm:px-5 py-3 text-center font-semibold">Status</th><th class="px-3 sm:px-5 py-3 text-center font-semibold hidden md:table-cell">Tanggal</th></tr></thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr class="border-b border-brand-border/30 hover:bg-brand-warm/40 transition-colors">
                        <td class="px-3 sm:px-5 py-3 font-mono text-xs whitespace-nowrap">{{ $o->order_number }}</td>
                        <td class="px-3 sm:px-5 py-3 hidden sm:table-cell whitespace-nowrap">{{ $o->user->name ?? '-' }}</td>
                        <td class="px-3 sm:px-5 py-3 text-right font-medium tabular-nums whitespace-nowrap">Rp{{ number_format($o->total,0,',','.') }}</td>
                        <td class="px-3 sm:px-5 py-3 text-center whitespace-nowrap">
                            @php $sc = $o->status === 'completed' ? 'bg-green-100 text-green-700' : ($o->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') @endphp
                            <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $sc }}">{{ $o->status === 'completed' ? 'Selesai' : ($o->status === 'cancelled' ? 'Batal' : ($o->status === 'processing' ? 'Proses' : 'Pending')) }}</span>
                        </td>
                        <td class="px-3 sm:px-5 py-3 text-center text-brand-ink-muted text-xs hidden md:table-cell whitespace-nowrap">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <div class="relative">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-brand-border"></div></div>
            <div class="relative flex justify-center"><span class="bg-brand-warm px-4 text-xs font-semibold text-brand-ink-muted uppercase tracking-widest">Servis Workshop</span></div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-brand-border bg-brand-warm/30">
                <h3 class="font-display font-bold text-brand-ink uppercase text-sm tracking-wide">Servis Workshop</h3>
                <span class="text-xs text-brand-ink-muted font-medium">{{ $repairCount }} transaksi</span>
            </div>
            @if($repairs->isEmpty())
            <div class="py-12 text-center text-sm text-brand-ink-muted">Tidak ada servis di periode ini</div>
            @else
            <div class="overflow-x-auto"><table class="w-full text-sm">
                <thead><tr class="border-b bg-brand-warm/70 text-brand-ink-faint text-xs uppercase tracking-widest"><th class="px-3 sm:px-5 py-3 text-left font-semibold">No. Servis</th><th class="px-3 sm:px-5 py-3 text-left font-semibold">Pelanggan</th><th class="px-3 sm:px-5 py-3 text-left font-semibold hidden sm:table-cell">Kendaraan</th><th class="px-3 sm:px-5 py-3 text-right font-semibold">Total</th><th class="px-3 sm:px-5 py-3 text-center font-semibold">Status</th><th class="px-3 sm:px-5 py-3 text-center font-semibold hidden md:table-cell">Tanggal</th></tr></thead>
                <tbody>
                    @foreach($repairs as $r)
                    <tr class="border-b border-brand-border/30 hover:bg-brand-warm/40 transition-colors">
                        <td class="px-3 sm:px-5 py-3 font-mono text-xs whitespace-nowrap">{{ $r->order_number }}</td>
                        <td class="px-3 sm:px-5 py-3 whitespace-nowrap">{{ $r->customer->name }}</td>
                        <td class="px-3 sm:px-5 py-3 hidden sm:table-cell text-brand-ink-muted text-xs whitespace-nowrap">{{ $r->vehicle->plate_number }} ({{ $r->vehicle->brand }})</td>
                        <td class="px-3 sm:px-5 py-3 text-right font-medium tabular-nums whitespace-nowrap">Rp{{ number_format($r->total,0,',','.') }}</td>
                        <td class="px-3 sm:px-5 py-3 text-center whitespace-nowrap">
                            @php $sc = $r->status === 'selesai' ? 'bg-green-100 text-green-700' : ($r->status === 'dibatalkan' ? 'bg-red-100 text-red-600' : ($r->status === 'proses' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) @endphp
                            <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $sc }}">{{ ucfirst($r->status) }}</span>
                        </td>
                        <td class="px-3 sm:px-5 py-3 text-center text-brand-ink-muted text-xs hidden md:table-cell whitespace-nowrap">{{ $r->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</x-admin-layout>

