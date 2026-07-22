<x-admin-layout>
    <x-slot name="title">Pesanan</x-slot>

    @if(session('success'))
    <div class="card p-5 mb-8 border-l-4 border-emerald-500 text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    <div class="card p-6 mb-8">
        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" class="flex gap-3 flex-1 flex-wrap">
                <input type="text" name="search" placeholder="Cari INV, nama..." value="{{ $search }}" class="input-field w-full sm:flex-1 sm:w-auto">
                <button type="submit" class="btn-primary shrink-0 w-full md:w-auto min-h-[44px]">Cari</button>
                @if($search)
                <a href="{{ route('admin.orders') }}" class="btn-outline shrink-0 w-full md:w-auto min-h-[44px]">Reset</a>
                @endif
            </form>
            <a href="{{ route('admin.orders.export') }}{{ $search ? '?search=' . urlencode($search) : '' }}" class="btn-outline shrink-0 self-start w-full md:w-auto min-h-[44px]">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table-base">
                <thead>
                    <tr>
                        <th class="text-left">INV</th>
                        <th class="text-left">Pelanggan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center hidden sm:table-cell">Pembayaran</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr>
                        <td class="font-medium text-brand-ink font-mono">{{ $o->order_number }}</td>
                        <td class="text-brand-ink-muted">{{ $o->customer_name }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.orders.update', $o) }}" class="inline" onchange="this.submit()">
                                @csrf
                                <select name="status" class="text-xs font-semibold rounded-xl px-2 py-1 border-0 cursor-pointer appearance-none {{ $o->status === 'completed' ? 'badge badge-success' : ($o->status === 'cancelled' ? 'badge badge-danger' : 'badge badge-warning') }}">
                                    <option value="pending" {{ $o->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $o->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $o->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $o->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                </select>
                                <input type="hidden" name="payment_status" value="{{ $o->payment_status }}">
                            </form>
                        </td>
                        <td class="text-center hidden sm:table-cell">
                            <form method="POST" action="{{ route('admin.orders.update', $o) }}" class="inline" onchange="this.submit()">
                                @csrf
                                <select name="payment_status" class="text-xs font-semibold rounded-xl px-2 py-1 border-0 cursor-pointer appearance-none {{ $o->payment_status === 'paid' ? 'badge badge-success' : ($o->payment_status === 'failed' ? 'badge badge-danger' : 'badge badge-warning') }}">
                                    <option value="pending" {{ $o->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $o->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="failed" {{ $o->payment_status === 'failed' ? 'selected' : '' }}>Gagal</option>
                                </select>
                                <input type="hidden" name="status" value="{{ $o->status }}">
                            </form>
                        </td>
                        <td class="text-right font-mono tabular-nums text-brand-ink">Rp{{ number_format($o->total,0,',','.') }}</td>
                        <td class="text-center"><a href="{{ route('admin.orders.invoice', $o) }}" class="btn-ghost btn-xs" target="_blank">PDF</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5 pagination-wrap">{{ $orders->links() }}</div>
</x-admin-layout>
