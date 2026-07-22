<x-admin-layout>
    <x-slot name="title">Kategori</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="card p-5">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2 flex-wrap">
                @csrf
                <input type="text" name="name" placeholder="Nama kategori" class="input-field w-full sm:flex-1 sm:w-auto" required>
                <button type="submit" class="btn-primary shrink-0 w-full md:w-auto">Tambah</button>
            </form>
        </div>

        <div class="card p-0 overflow-hidden">
            <div class="table-wrap overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr class="border-b border-brand-border/20 bg-brand-warm">
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Nama</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Aktif</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $c)
                        <tr class="border-b border-brand-border/10" id="cat-row-{{ $c->id }}">
                            <td class="p-3 font-medium">{{ $c->name }}</td>
                            <td class="p-3 text-center">
                                @if($c->is_active)
                                <span class="badge badge-success">Aktif</span>
                                @else
                                <span class="badge badge-neutral">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="document.getElementById('cat-edit-{{ $c->id }}').classList.toggle('hidden')" class="btn-ghost btn-sm">Edit</button>
                                <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="cat-edit-{{ $c->id }}" class="hidden">
                            <td colspan="3" class="p-4 bg-brand-warm">
                                <form method="POST" action="{{ route('admin.categories.update', $c) }}" class="flex gap-2">
                                    @csrf @method('PATCH')
                                    <input type="text" name="name" value="{{ $c->name }}" class="input-field" required>
                                    <button type="submit" class="btn-primary shrink-0 w-full md:w-auto">Simpan</button>
                                    <button type="button" onclick="document.getElementById('cat-edit-{{ $c->id }}').classList.add('hidden')" class="btn-outline shrink-0 w-full md:w-auto">Batal</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
