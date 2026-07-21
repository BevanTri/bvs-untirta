<x-admin-layout>
    <x-slot name="title">Pesanan</x-slot>

    @if(session('success'))
    <div class="card p-4 mb-4 border-l-4 border-green-500 bg-green-50 text-green-800 font-medium">{{ session('success') }}</div>
    @endif

    <div class="card p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-2">
            <form method="GET" class="flex gap-2 flex-1 flex-wrap">
                <input type="text" name="search" placeholder="Cari INV, nama..." value="{{ $search }}" class="input-field w-full sm:flex-1 sm:w-auto">
                <button type="submit" class="btn-primary shrink-0 min-h-[44px]">Cari</button>
                @if($search)
                <a href="{{ route('admin.orders') }}" class="btn-outline shrink-0 min-h-[44px]">Reset</a>
                @endif
            </form>
            <a href="{{ route('admin.orders.export') }}{{ $search ? '?search=' . urlencode($search) : '' }}" class="btn-outline shrink-0 self-start min-h-[44px]">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-brand-border bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">INV</th><th class="p-3 text-left font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">Pelanggan</th><th class="p-3 text-center font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">Status</th><th class="p-3 text-center font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display hidden sm:table-cell">Pembayaran</th><th class="p-3 text-right font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">Total</th><th class="p-3 text-center font-semibold text-brand-ink-faint text-xs uppercase tracking-widest font-display">Invoice</th></tr></thead>
            <tbody>
                @foreach($orders as $o)
                <tr class="border-b border-brand-border/50 hover:bg-brand-warm/50 transition-colors">
                    <td class="p-3 font-medium text-brand-ink font-mono">{{ $o->order_number }}</td>
                    <td class="p-3 text-brand-ink-muted">{{ $o->customer_name }}</td>
                    <td class="p-3 text-center">
                        <form method="POST" action="{{ route('admin.orders.update', $o) }}" class="inline" onchange="this.submit()">
                            @csrf
                            <select name="status" class="text-xs font-semibold rounded-full px-2 py-0.5 border-0 cursor-pointer appearance-none {{ $o->status === 'completed' ? 'badge-paid' : ($o->status === 'cancelled' ? 'badge-failed' : 'badge-pending') }}">
                                <option value="pending" {{ $o->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $o->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $o->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $o->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                            </select>
                            <input type="hidden" name="payment_status" value="{{ $o->payment_status }}">
                        </form>
                    </td>
                    <td class="p-3 text-center hidden sm:table-cell">
                        <form method="POST" action="{{ route('admin.orders.update', $o) }}" class="inline" onchange="this.submit()">
                            @csrf
                            <select name="payment_status" class="text-xs font-semibold rounded-full px-2 py-0.5 border-0 cursor-pointer appearance-none {{ $o->payment_status === 'paid' ? 'badge-paid' : ($o->payment_status === 'failed' ? 'badge-failed' : 'badge-pending') }}">
                                <option value="pending" {{ $o->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $o->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                                <option value="failed" {{ $o->payment_status === 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                            <input type="hidden" name="status" value="{{ $o->status }}">
                        </form>
                    </td>
                    <td class="p-3 text-right font-medium tabular-nums text-brand-ink font-mono">Rp{{ number_format($o->total,0,',','.') }}</td>
                    <td class="p-3 text-center"><a href="{{ route('admin.orders.invoice', $o) }}" class="text-xs text-brand-blue hover:underline font-display uppercase tracking-wide" target="_blank">PDF</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</x-admin-layout>

