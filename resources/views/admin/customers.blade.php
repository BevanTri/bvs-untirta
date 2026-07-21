<x-admin-layout>
    <x-slot name="title">Pelanggan</x-slot>

    @if(session('success'))
    <div class="card p-4 mb-4 border-l-4 border-brand-gold bg-brand-amber/5 text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    <form method="GET" class="card p-4 mb-6 flex gap-2 flex-wrap">
        <input type="text" name="search" placeholder="Cari nama/email..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
        <button type="submit" class="btn-primary shrink-0 min-h-[44px]">Cari</button>
        @if($search)
        <a href="{{ route('admin.customers') }}" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel min-h-[44px]">Reset</a>
        @endif
    </form>

    <form method="POST" action="{{ route('admin.customers.store') }}" class="card p-4 sm:p-5 mb-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="name" placeholder="Nama" class="input-field" required>
            <input type="email" name="email" placeholder="Email" class="input-field">
            <button type="submit" class="btn-primary min-h-[44px]">Tambah</button>
        </div>
        <textarea name="address" placeholder="Alamat (opsional)" class="input-field mt-3" rows="2"></textarea>
    </form>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Nama</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest hidden lg:table-cell whitespace-nowrap">Email</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Kendaraan</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Servis</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th></tr></thead>
            <tbody>
                @foreach($customers as $c)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium break-words max-w-[160px] sm:max-w-none">{{ $c->name }}</td>
                    <td class="p-3 hidden lg:table-cell text-brand-steel">{{ $c->email ?? '—' }}</td>
                    <td class="p-3 text-center">{{ $c->vehicles_count }}</td>
                    <td class="p-3 text-center">{{ $c->repair_orders_count }}</td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick="document.getElementById('cst-edit-{{ $c->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline min-h-[44px] px-2">Edit</button>
                        <form method="POST" action="{{ route('admin.customers.destroy', $c) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline min-h-[44px] px-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="cst-edit-{{ $c->id }}" class="hidden">
                    <td colspan="5" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.customers.update', $c) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <input type="text" name="name" value="{{ $c->name }}" class="input-field" required>
                                <input type="email" name="email" value="{{ $c->email }}" class="input-field">
                                <button type="submit" class="btn-primary">Simpan</button>
                            </div>
                            <textarea name="address" class="input-field mt-3" rows="2">{{ $c->address }}</textarea>
                            <button type="button" onclick="document.getElementById('cst-edit-{{ $c->id }}').classList.add('hidden')" class="btn-outline mt-2 !border-brand-steel/30 !text-brand-steel">Batal</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($customers instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="p-4 border-t border-brand-border"> {{ $customers->links() }} </div>
        @endif
    </div>
</x-admin-layout>
