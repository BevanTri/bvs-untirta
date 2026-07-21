<x-admin-layout>
    <x-slot name="title">Laporan</x-slot>

    <div class="card p-4 mb-6">
        <form method="GET" class="flex gap-2 flex-wrap items-center">
            <span class="text-xs font-semibold text-brand-steel uppercase tracking-widest w-full sm:w-auto mb-1 sm:mb-0">Periode:</span>
            @foreach(['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $key => $label)
            <a href="{{ route('admin.reports', ['period' => $key]) }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $period === $key ? 'bg-brand-navy text-white' : 'bg-brand-warm text-brand-ink-muted hover:bg-brand-border' }}">{{ $label }}</a>
            @endforeach
        </form>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="card p-4 border-l-4 border-brand-gold">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Pendapatan</p>
            <p class="font-display text-2xl font-bold text-brand-ink mt-1">Rp{{ number_format($totalRevenue,0,',','.') }}</p>
        </div>
        <div class="card p-4 border-l-4 border-blue-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Pesanan Produk</p>
            <p class="font-display text-2xl font-bold text-blue-600 mt-1">{{ $orderCount }} (Rp{{ number_format($orderRevenue,0,',','.') }})</p>
        </div>
        <div class="card p-4 border-l-4 border-sky-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Servis Workshop</p>
            <p class="font-display text-2xl font-bold text-sky-600 mt-1">{{ $repairCount }} (Rp{{ number_format($repairRevenue,0,',','.') }})</p>
        </div>
        <div class="card p-4 border-l-4 border-emerald-600">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Total Transaksi</p>
            <p class="font-display text-2xl font-bold text-emerald-700 mt-1">{{ $orderCount + $repairCount }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <div class="p-4 border-b border-brand-border"><h3 class="font-display font-bold text-brand-ink uppercase tracking-wide">Pesanan Produk</h3></div>
            @if($orders->isEmpty())
            <p class="p-4 text-sm text-brand-ink-muted">Tidak ada data</p>
            @else
            <div class="overflow-x-auto"><table class="w-full text-sm">
                <thead><tr class="border-b bg-brand-warm text-brand-ink-faint text-xs uppercase tracking-widest"><th class="p-2 text-left font-semibold whitespace-nowrap">INV</th><th class="p-2 text-right font-semibold whitespace-nowrap">Total</th><th class="p-2 text-center font-semibold whitespace-nowrap">Status</th></tr></thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr class="border-b border-brand-border/50">
                        <td class="p-2 font-mono whitespace-nowrap">{{ $o->order_number }}</td>
                        <td class="p-2 text-right font-medium whitespace-nowrap">Rp{{ number_format($o->total,0,',','.') }}</td>
                        <td class="p-2 text-center">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->status === 'completed' ? 'bg-green-100 text-green-700' : ($o->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($o->status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <div class="card">
            <div class="p-4 border-b border-brand-border"><h3 class="font-display font-bold text-brand-ink uppercase tracking-wide">Servis Workshop</h3></div>
            @if($repairs->isEmpty())
            <p class="p-4 text-sm text-brand-ink-muted">Tidak ada data</p>
            @else
            <div class="overflow-x-auto"><table class="w-full text-sm">
                <thead><tr class="border-b bg-brand-warm text-brand-ink-faint text-xs uppercase tracking-widest"><th class="p-2 text-left font-semibold whitespace-nowrap">No.</th><th class="p-2 text-left font-semibold hidden sm:table-cell whitespace-nowrap">Pelanggan</th><th class="p-2 text-right font-semibold whitespace-nowrap">Total</th><th class="p-2 text-center font-semibold whitespace-nowrap">Status</th></tr></thead>
                <tbody>
                    @foreach($repairs as $r)
                    <tr class="border-b border-brand-border/50">
                        <td class="p-2 font-mono whitespace-nowrap">{{ $r->order_number }}</td>
                        <td class="p-2 hidden sm:table-cell whitespace-nowrap">{{ $r->customer->name }}</td>
                        <td class="p-2 text-right font-medium whitespace-nowrap">Rp{{ number_format($r->total,0,',','.') }}</td>
                        <td class="p-2 text-center">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $r->status === 'selesai' ? 'bg-green-100 text-green-700' : ($r->status === 'dibatalkan' ? 'bg-red-100 text-red-600' : ($r->status === 'proses' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) }}">{{ ucfirst($r->status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</x-admin-layout>

