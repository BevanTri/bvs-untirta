<x-admin-layout>
    <x-slot name="title">Servis Workshop</x-slot>

    @if(session('success'))
    <div class="card p-5 mb-8 border-l-4 border-brand-gold text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    <div class="card p-6 mb-8">
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('admin.repair-orders.create') }}" class="btn-primary shrink-0 self-start w-full md:w-auto min-h-[44px]">+ Buat Servis</a>
            <a href="{{ route('admin.repair-orders.export', request()->query()) }}" class="btn-outline shrink-0 self-start w-full md:w-auto min-h-[44px]">Export CSV</a>
            <form method="GET" class="flex gap-3 flex-1 flex-wrap">
                <input type="text" name="search" placeholder="Cari nomor/pelanggan..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
                <select name="status" class="input-field w-full sm:w-auto" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ ($filterStatus ?? '') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="proses" {{ ($filterStatus ?? '') === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ ($filterStatus ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ ($filterStatus ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="btn-primary shrink-0 w-full md:w-auto min-h-[44px]">Cari</button>
                @if($search || $filterStatus)
                <a href="{{ route('admin.repair-orders') }}" class="btn-outline shrink-0 w-full md:w-auto min-h-[44px]">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table class="table-base">
                <thead>
                    <tr>
                        <th class="text-left">No. Servis</th>
                        <th class="text-left">Pelanggan</th>
                        <th class="text-left hidden sm:table-cell">Kendaraan</th>
                        <th class="text-right hidden sm:table-cell">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Bayar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr>
                        <td class="font-medium font-mono">{{ $o->order_number }}</td>
                        <td>{{ $o->customer->name }}</td>
                        <td class="hidden sm:table-cell text-brand-ink-muted">{{ $o->vehicle->plate_number }} ({{ $o->vehicle->brand }})</td>
                        <td class="text-right hidden sm:table-cell font-mono tabular-nums">Rp{{ number_format($o->total,0,',','.') }}</td>
                        <td class="text-center">
                            <span class="badge {{ $o->status === 'selesai' ? 'badge-success' : ($o->status === 'dibatalkan' ? 'badge-danger' : ($o->status === 'proses' ? 'badge-info' : 'badge-warning')) }}">
                                {{ ucfirst($o->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $o->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ $o->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                            </span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <a href="{{ route('admin.repair-orders.show', $o) }}" class="btn-ghost btn-sm">Detail</a>
                            <a href="{{ route('admin.repair-orders.edit', $o) }}" class="btn-ghost btn-sm ml-1">Edit</a>
                            <form method="POST" action="{{ route('admin.repair-orders.destroy', $o) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm ml-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="p-4 border-t border-brand-border pagination-wrap">{{ $orders->links() }}</div>
        @endif
    </div>
</x-admin-layout>
