<x-admin-layout>
    <x-slot name="title">Kategori</x-slot>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="card p-5 mb-6 flex gap-2">
        @csrf
        <input type="text" name="name" placeholder="Nama kategori" class="input-field" required>
        <button type="submit" class="btn-primary shrink-0">Tambah</button>
    </form>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest">Nama</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Aktif</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Aksi</th></tr></thead>
            <tbody>
                @foreach($categories as $c)
                <tr class="border-b border-brand-steel/10" id="cat-row-{{ $c->id }}">
                    <td class="p-3 font-medium">{{ $c->name }}</td>
                    <td class="p-3 text-center">{{ $c->is_active ? '✓' : '✗' }}</td>
                    <td class="p-3 text-center">
                        <button onclick="document.getElementById('cat-edit-{{ $c->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline">Edit</button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="cat-edit-{{ $c->id }}" class="hidden">
                    <td colspan="3" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.categories.update', $c) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $c->name }}" class="input-field" required>
                            <button type="submit" class="btn-primary shrink-0">Simpan</button>
                            <button type="button" onclick="document.getElementById('cat-edit-{{ $c->id }}').classList.add('hidden')" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel">Batal</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
