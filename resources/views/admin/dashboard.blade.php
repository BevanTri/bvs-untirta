<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <div class="card p-3 sm:p-4 border-l-4 border-brand-gold">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Total Pesanan</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-brand-ink mt-1">{{ $totalOrders }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Menunggu</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-yellow-600 mt-1">{{ $pendingOrders }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-blue-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Diproses</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-blue-600 mt-1">{{ $processingOrders }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-green-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Selesai</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-green-600 mt-1">{{ $completedOrders }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-red-400">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Batal</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-red-500 mt-1">{{ $cancelledOrders }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-emerald-600">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Lunas</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-emerald-700 mt-1">{{ $paidOrders }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-violet-500 sm:col-span-2">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Pendapatan</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-violet-700 mt-1 font-mono">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <div class="card p-3 sm:p-4 border-l-4 border-cyan-600">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Pelanggan</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-cyan-700 mt-1">{{ $totalCustomers }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-orange-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Kendaraan</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-orange-600 mt-1">{{ $totalVehicles }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-sky-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Servis</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-sky-600 mt-1">{{ $totalRepairOrders }}</p>
        </div>
        <div class="card p-3 sm:p-4 border-l-4 border-rose-500">
            <p class="text-xs text-brand-ink-faint uppercase tracking-[0.15em] font-display font-semibold">Servis Hari Ini</p>
            <p class="font-display text-2xl sm:text-3xl font-bold text-rose-600 mt-1">{{ $todayRepairs }}</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card p-5 mb-8">
        <h3 class="font-display font-bold text-lg text-brand-ink uppercase tracking-wide mb-4">Grafik Transaksi (7 Hari Terakhir)</h3>
        <div class="flex items-end gap-2" style="height:128px">
            @php $chartMax = max($chartMax, 1); @endphp
            @foreach($chartData as $day)
            @php $barH = max(round($day['total'] / $chartMax * 120), 4); @endphp
            <div class="flex-1 flex flex-col items-center gap-0.5 self-stretch justify-end">
                <span class="text-[10px] font-semibold text-brand-ink-faint leading-tight">{{ $day['count'] }} trx</span>
                <div class="w-full bg-brand-gold rounded-t transition-all" style="height: {{ $barH }}px" title="{{ $day['count'] }} transaksi - Rp{{ number_format($day['total'], 0, ',', '.') }}"></div>
                <span class="text-xs text-brand-ink-faint">{{ $day['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-8 overflow-hidden">
        <div class="p-5 border-b border-brand-border flex items-center justify-between">
            <h3 class="font-display font-bold text-lg text-brand-ink uppercase tracking-wide">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders') }}" class="text-xs text-brand-blue font-medium hover:underline font-display uppercase tracking-wide">Lihat Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-brand-border bg-brand-warm">
                        <th class="text-left p-3 font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">INV</th>
                        <th class="text-left p-3 font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">Pelanggan</th>
                        <th class="text-center p-3 font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display hidden sm:table-cell">Status</th>
                        <th class="text-center p-3 font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display hidden sm:table-cell">Bayar</th>
                        <th class="text-right p-3 font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $o)
                    <tr class="border-b border-brand-border/50 hover:bg-brand-warm/50 transition-colors">
                        <td class="p-3 font-medium text-brand-ink font-mono">{{ $o->order_number }}</td>
                        <td class="p-3 text-brand-ink-muted">{{ $o->customer_name }}</td>
                        <td class="p-3 text-center hidden sm:table-cell">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->status === 'completed' ? 'bg-green-100 text-green-700' : ($o->status === 'cancelled' ? 'bg-red-100 text-red-600' : ($o->status === 'processing' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ $o->status === 'completed' ? 'Selesai' : ($o->status === 'cancelled' ? 'Batal' : ($o->status === 'processing' ? 'Proses' : 'Pending')) }}
                            </span>
                        </td>
                        <td class="p-3 text-center hidden sm:table-cell">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($o->payment_status === 'failed' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $o->payment_status === 'paid' ? 'Lunas' : ($o->payment_status === 'failed' ? 'Gagal' : 'Pending') }}
                            </span>
                        </td>
                        <td class="p-3 text-right font-medium tabular-nums text-brand-ink font-mono">Rp{{ number_format($o->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-brand-ink-faint">Data Master</span>
                <div class="h-px flex-1 bg-brand-border"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                <a href="{{ route('admin.customers') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Pelanggan</div>
                </a>
                <a href="{{ route('admin.vehicles') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Kendaraan</div>
                </a>
                <a href="{{ route('admin.mechanics') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Mekanik</div>
                </a>
                <a href="{{ route('admin.categories') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Kategori</div>
                </a>
                <a href="{{ route('admin.products') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Produk</div>
                </a>
                <a href="{{ route('admin.brands') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Brand</div>
                </a>
            </div>
        </div>
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="text-[10px] font-display font-semibold uppercase tracking-[0.15em] text-brand-ink-faint">Transaksi</span>
                <div class="h-px flex-1 bg-brand-border"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                <a href="{{ route('admin.repair-orders') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Servis</div>
                </a>
                <a href="{{ route('admin.orders') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Pesanan</div>
                </a>
                <a href="{{ route('admin.reports') }}" class="card-hover p-5 text-center group">
                    <svg class="w-6 h-6 mx-auto mb-2 text-brand-ink-faint group-hover:text-brand-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <div class="font-display font-semibold text-sm text-brand-ink-muted group-hover:text-brand-ink transition-colors uppercase tracking-wide">Laporan</div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>