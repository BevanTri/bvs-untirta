<x-admin-layout>
    <x-slot name="title">Servis Workshop</x-slot>

    @if(session('success'))
    <div class="card p-4 mb-4 border-l-4 border-brand-gold bg-brand-amber/5 text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    <div class="card p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('admin.repair-orders.create') }}" class="btn-primary shrink-0 self-start min-h-[44px]">+ Buat Servis</a>
            <a href="{{ route('admin.repair-orders.export', request()->query()) }}" class="btn-outline shrink-0 self-start min-h-[44px]">Export CSV</a>
            <form method="GET" class="flex gap-2 flex-1 flex-wrap">
                <input type="text" name="search" placeholder="Cari nomor/pelanggan..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
                <select name="status" class="input-field w-full sm:w-auto" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ ($filterStatus ?? '') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="proses" {{ ($filterStatus ?? '') === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ ($filterStatus ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ ($filterStatus ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="btn-primary shrink-0 min-h-[44px]">Cari</button>
                @if($search || $filterStatus)
                <a href="{{ route('admin.repair-orders') }}" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel min-h-[44px]">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest">No. Servis</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest">Pelanggan</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest hidden sm:table-cell">Kendaraan</th><th class="p-3 text-right font-semibold text-brand-steel text-xs uppercase tracking-widest hidden sm:table-cell">Total</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Status</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Bayar</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Aksi</th></tr></thead>
            <tbody>
                @foreach($orders as $o)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium font-mono">{{ $o->order_number }}</td>
                    <td class="p-3">{{ $o->customer->name }}</td>
                    <td class="p-3 hidden sm:table-cell text-brand-steel">{{ $o->vehicle->plate_number }} ({{ $o->vehicle->brand }})</td>
                    <td class="p-3 text-right hidden sm:table-cell font-medium tabular-nums">Rp{{ number_format($o->total,0,',','.') }}</td>
                    <td class="p-3 text-center">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->status === 'selesai' ? 'bg-green-100 text-green-700' : ($o->status === 'dibatalkan' ? 'bg-red-100 text-red-600' : ($o->status === 'proses' ? 'bg-blue-100 text-blue-600' : 'bg-yellow-100 text-yellow-700')) }}">
                            {{ ucfirst($o->status) }}
                        </span>
                    </td>
                    <td class="p-3 text-center">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $o->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                        </span>
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('admin.repair-orders.show', $o) }}" class="text-brand-blue text-sm font-medium hover:underline">Detail</a>
                        <a href="{{ route('admin.repair-orders.edit', $o) }}" class="text-brand-amber text-sm font-medium ml-2 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.repair-orders.destroy', $o) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="p-4 border-t border-brand-border"> {{ $orders->links() }} </div>
        @endif
    </div>
</x-admin-layout>

