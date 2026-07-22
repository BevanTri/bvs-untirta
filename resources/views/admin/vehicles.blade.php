<x-admin-layout>
    <x-slot name="title">Kendaraan</x-slot>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm mb-6">{{ session('success') }}</div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="card p-5">
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" placeholder="Cari plat/merk/model..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
                <button type="submit" class="btn-primary shrink-0 w-full md:w-auto">Cari</button>
                @if($search)
                <a href="{{ route('admin.vehicles') }}" class="btn-outline shrink-0 w-full md:w-auto">Reset</a>
                @endif
            </form>
        </div>

        <div class="card p-5">
            <form method="POST" action="{{ route('admin.vehicles.store') }}">
                @csrf
                <div class="mb-4">
                    <select name="customer_id" class="input-field w-full" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <input type="text" name="plate_number" placeholder="Nomor Polisi" class="input-field w-full" required>
                    <input type="text" name="brand" placeholder="Merk" class="input-field w-full" required>
                </div>
                <div class="mb-4">
                    <input type="text" name="model" placeholder="Tipe" class="input-field w-full" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn-primary" style="width:260px">Tambah Kendaraan</button>
                </div>
            </form>
        </div>

        <div class="card p-0 overflow-hidden">
            <div class="table-wrap overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr class="border-b border-brand-border/20 bg-brand-warm">
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Plat</th>
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Merk</th>
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest hidden sm:table-cell whitespace-nowrap">Tipe</th>
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest hidden sm:table-cell whitespace-nowrap">Pelanggan</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicles as $v)
                        <tr class="border-b border-brand-border/10">
                            <td class="p-3 font-medium font-mono tabular-nums">{{ $v->plate_number }}</td>
                            <td class="p-3">{{ $v->brand }}</td>
                            <td class="p-3 hidden sm:table-cell text-brand-ink-muted">{{ $v->model }}</td>
                            <td class="p-3 hidden sm:table-cell">{{ $v->customer->name }}</td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="document.getElementById('veh-edit-{{ $v->id }}').classList.toggle('hidden')" class="btn-ghost btn-sm">Edit</button>
                                <form method="POST" action="{{ route('admin.vehicles.destroy', $v) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="veh-edit-{{ $v->id }}" class="hidden">
                            <td colspan="5" class="p-4 bg-brand-warm">
                                <form method="POST" action="{{ route('admin.vehicles.update', $v) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <select name="customer_id" class="input-field w-full" required>
                                            @foreach($customers as $c)
                                            <option value="{{ $c->id }}" {{ $v->customer_id === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                        <input type="text" name="plate_number" value="{{ $v->plate_number }}" class="input-field w-full" required>
                                        <input type="text" name="brand" value="{{ $v->brand }}" class="input-field w-full" required>
                                    </div>
                                    <div class="mb-4">
                                        <input type="text" name="model" value="{{ $v->model }}" class="input-field w-full" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn-primary" style="width:200px">Simpan</button>
                                        <button type="button" onclick="document.getElementById('veh-edit-{{ $v->id }}').classList.add('hidden')" class="btn-outline" style="width:200px">Batal</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($vehicles instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-4 border-t border-brand-border"> {{ $vehicles->links() }} </div>
            @endif
        </div>
    </div>
</x-admin-layout>
