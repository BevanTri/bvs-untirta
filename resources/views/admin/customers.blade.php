<x-admin-layout>
    <x-slot name="title">Pelanggan</x-slot>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm mb-6">{{ session('success') }}</div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="card p-5">
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" placeholder="Cari nama/email..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
                <button type="submit" class="btn-primary shrink-0 w-full md:w-auto">Cari</button>
                @if($search)
                <a href="{{ route('admin.customers') }}" class="btn-outline shrink-0 w-full md:w-auto">Reset</a>
                @endif
            </form>
        </div>

        <div class="card p-5">
            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <input type="text" name="name" placeholder="Nama" class="input-field" required>
                    <input type="email" name="email" placeholder="Email" class="input-field">
                    <button type="submit" class="btn-primary w-full md:w-auto">Tambah</button>
                </div>
            </form>
        </div>

        <div class="card p-0 overflow-hidden">
            <div class="table-wrap overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr class="border-b border-brand-border/20 bg-brand-warm">
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Nama</th>
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest hidden lg:table-cell whitespace-nowrap">Email</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Kendaraan</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Servis</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $c)
                        <tr class="border-b border-brand-border/10">
                            <td class="p-3 font-medium break-words max-w-[160px] sm:max-w-none">{{ $c->name }}</td>
                            <td class="p-3 hidden lg:table-cell text-brand-ink-muted">{{ $c->email ?? '—' }}</td>
                            <td class="p-3 text-center font-mono tabular-nums">{{ $c->vehicles_count }}</td>
                            <td class="p-3 text-center font-mono tabular-nums">{{ $c->repair_orders_count }}</td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="document.getElementById('cst-edit-{{ $c->id }}').classList.toggle('hidden')" class="btn-ghost btn-sm">Edit</button>
                                <form method="POST" action="{{ route('admin.customers.destroy', $c) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="cst-edit-{{ $c->id }}" class="hidden">
                            <td colspan="5" class="p-4 bg-brand-warm">
                                <form method="POST" action="{{ route('admin.customers.update', $c) }}">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <input type="text" name="name" value="{{ $c->name }}" class="input-field" required>
                                        <input type="email" name="email" value="{{ $c->email }}" class="input-field">
                                        <button type="submit" class="btn-primary w-full md:w-auto">Simpan</button>
                                    </div>
                                    <button type="button" onclick="document.getElementById('cst-edit-{{ $c->id }}').classList.add('hidden')" class="btn-outline mt-5 w-full md:w-auto">Batal</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($customers instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-4 border-t border-brand-border"> {{ $customers->links() }} </div>
            @endif
        </div>
    </div>
</x-admin-layout>
