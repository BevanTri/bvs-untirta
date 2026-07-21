<x-admin-layout>
    <x-slot name="title">Kendaraan</x-slot>

    @if(session('success'))
    <div class="card p-4 mb-4 border-l-4 border-brand-gold bg-brand-amber/5 text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    <form method="GET" class="card p-4 mb-6 flex gap-2 flex-wrap">
        <input type="text" name="search" placeholder="Cari plat/merk/model..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
        <button type="submit" class="btn-primary shrink-0 min-h-[44px]">Cari</button>
        @if($search)
        <a href="{{ route('admin.vehicles') }}" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel min-h-[44px]">Reset</a>
        @endif
    </form>

    <form method="POST" action="{{ route('admin.vehicles.store') }}" class="card p-4 sm:p-5 mb-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
            <select name="customer_id" class="input-field" required>
                <option value="">Pilih Pelanggan</option>
                @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
            <input type="text" name="plate_number" placeholder="Nomor Polisi" class="input-field" required>
            <input type="text" name="brand" placeholder="Merk" class="input-field" required>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="text" name="model" placeholder="Tipe" class="input-field" required>
            <input type="number" name="year" placeholder="Tahun" class="input-field" min="1900" max="2099">
            <input type="text" name="color" placeholder="Warna" class="input-field">
            <button type="submit" class="btn-primary min-h-[44px]">Tambah</button>
        </div>
    </form>

    <div class="card">
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest">Plat</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest">Merk</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest hidden sm:table-cell">Tipe</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest hidden sm:table-cell">Pelanggan</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Warna</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Aksi</th></tr></thead>
            <tbody>
                @foreach($vehicles as $v)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium">{{ $v->plate_number }}</td>
                    <td class="p-3">{{ $v->brand }}</td>
                    <td class="p-3 hidden sm:table-cell text-brand-steel">{{ $v->model }}</td>
                    <td class="p-3 hidden sm:table-cell">{{ $v->customer->name }}</td>
                    <td class="p-3 text-center">{{ $v->color ?? '—' }}</td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick="document.getElementById('veh-edit-{{ $v->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline min-h-[44px] px-2">Edit</button>
                        <form method="POST" action="{{ route('admin.vehicles.destroy', $v) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline min-h-[44px] px-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="veh-edit-{{ $v->id }}" class="hidden">
                    <td colspan="6" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.vehicles.update', $v) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                <select name="customer_id" class="input-field" required>
                                    @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ $v->customer_id === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="plate_number" value="{{ $v->plate_number }}" class="input-field" required>
                                <input type="text" name="brand" value="{{ $v->brand }}" class="input-field" required>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <input type="text" name="model" value="{{ $v->model }}" class="input-field" required>
                                <input type="number" name="year" value="{{ $v->year }}" class="input-field">
                                <input type="text" name="color" value="{{ $v->color }}" class="input-field">
                                <button type="submit" class="btn-primary">Simpan</button>
                                <button type="button" onclick="document.getElementById('veh-edit-{{ $v->id }}').classList.add('hidden')" class="btn-outline !border-brand-steel/30 !text-brand-steel">Batal</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($vehicles instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="p-4 border-t border-brand-border"> {{ $vehicles->links() }} </div>
        @endif
    </div>
</x-admin-layout>

