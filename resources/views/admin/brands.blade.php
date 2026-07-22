<x-admin-layout>
    <x-slot name="title">Brand Partner</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="card p-5">
            <form method="POST" action="{{ route('admin.brands.store') }}" class="flex gap-2 flex-wrap" enctype="multipart/form-data">
                @csrf
                <input type="text" name="name" placeholder="Nama brand" class="input-field w-full sm:flex-1" required>
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" class="input-field w-full sm:flex-1">
                <button type="submit" class="btn-primary shrink-0 w-full md:w-auto">Tambah</button>
            </form>
        </div>

        <div class="card p-0 overflow-hidden">
            <div class="table-wrap overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr class="border-b border-brand-border/20 bg-brand-warm">
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Nama</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Logo</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Aktif</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brands as $b)
                        <tr class="border-b border-brand-border/10">
                            <td class="p-3 font-medium">{{ $b->name }}</td>
                            <td class="p-3 text-center">
                                @if($b->logo)
                                <img src="{{ url('uploads/'.$b->logo) }}" alt="{{ $b->name }}" class="h-8 mx-auto object-contain rounded">
                                @else
                                <span class="text-brand-ink-muted text-xs">—</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if($b->is_active)
                                <span class="badge badge-success">Aktif</span>
                                @else
                                <span class="badge badge-neutral">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="document.getElementById('brand-edit-{{ $b->id }}').classList.toggle('hidden')" class="btn-ghost btn-sm">Edit</button>
                                <form method="POST" action="{{ route('admin.brands.destroy', $b) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="brand-edit-{{ $b->id }}" class="hidden">
                            <td colspan="4" class="p-4 bg-brand-warm">
                                <form method="POST" action="{{ route('admin.brands.update', $b) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3" enctype="multipart/form-data">
                                    @csrf @method('PATCH')
                                    <input type="text" name="name" value="{{ $b->name }}" class="input-field" required>
                                    <div class="flex items-center gap-3">
                                        @if($b->logo)
                                        <img src="{{ url('uploads/'.$b->logo) }}" class="h-8 object-contain rounded">
                                        <span class="text-xs text-brand-ink-muted">Ganti:</span>
                                        @endif
                                        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" class="input-field">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="btn-primary shrink-0 w-full md:w-auto">Simpan</button>
                                        <button type="button" onclick="document.getElementById('brand-edit-{{ $b->id }}').classList.add('hidden')" class="btn-outline shrink-0 w-full md:w-auto">Batal</button>
                                    </div>
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
