<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 mb-8">
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-brand-gold/10 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Total Pesanan</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-brand-ink">{{ $totalOrders }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Menunggu</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-amber-600">{{ $pendingOrders }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Diproses</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-blue-600">{{ $processingOrders }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Selesai</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-emerald-600">{{ $completedOrders }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Batal</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-red-500">{{ $cancelledOrders }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Lunas</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-emerald-600">{{ $paidOrders }}</p>
        </div>
        <div class="card p-5 sm:col-span-2">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Pendapatan</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-violet-700 font-mono">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 mb-8">
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-cyan-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Pelanggan</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-cyan-700">{{ $totalCustomers }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Kendaraan</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-orange-600">{{ $totalVehicles }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Servis</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-sky-600">{{ $totalRepairOrders }}</p>
        </div>
        <div class="card p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-[11px] font-display font-semibold uppercase tracking-wider text-brand-ink-faint">Servis Hari Ini</span>
            </div>
            <p class="font-display text-2xl sm:text-3xl font-bold text-rose-600">{{ $todayRepairs }}</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card p-6 mb-8">
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

    {{-- Recent Orders --}}
    <div class="card mb-8">
        <div class="flex items-center justify-between p-6 border-b border-brand-border">
            <h3 class="font-display font-bold text-lg text-brand-ink uppercase tracking-wide">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders') }}" class="text-xs text-brand-blue font-medium hover:underline font-display uppercase tracking-wide">Lihat Semua &rarr;</a>
        </div>
        <div class="table-wrap">
            <table class="table-base">
                <thead>
                    <tr>
                        <th class="text-left">INV</th>
                        <th class="text-left">Pelanggan</th>
                        <th class="text-center hidden sm:table-cell">Status</th>
                        <th class="text-center hidden sm:table-cell">Bayar</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $o)
                    <tr>
                        <td class="font-medium text-brand-ink font-mono">{{ $o->order_number }}</td>
                        <td class="text-brand-ink-muted">{{ $o->customer_name }}</td>
                        <td class="text-center hidden sm:table-cell">
                            <span class="badge {{ $o->status === 'completed' ? 'badge-success' : ($o->status === 'cancelled' ? 'badge-danger' : ($o->status === 'processing' ? 'badge-info' : 'badge-warning')) }}">
                                {{ $o->status === 'completed' ? 'Selesai' : ($o->status === 'cancelled' ? 'Batal' : ($o->status === 'processing' ? 'Proses' : 'Pending')) }}
                            </span>
                        </td>
                        <td class="text-center hidden sm:table-cell">
                            <span class="badge {{ $o->payment_status === 'paid' ? 'badge-success' : ($o->payment_status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                                {{ $o->payment_status === 'paid' ? 'Lunas' : ($o->payment_status === 'failed' ? 'Gagal' : 'Pending') }}
                            </span>
                        </td>
                        <td class="text-right font-mono tabular-nums text-brand-ink">Rp{{ number_format($o->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Links --}}
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