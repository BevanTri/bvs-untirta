<x-admin-layout>
    <x-slot name="title">Brand Partner</x-slot>

    <form method="POST" action="{{ route('admin.brands.store') }}" class="card p-4 sm:p-5 mb-6 flex gap-2 flex-wrap" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="Nama brand" class="input-field w-full sm:flex-1" required>
        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" class="input-field w-full sm:flex-1">
        <button type="submit" class="btn-primary shrink-0 min-h-[44px]">Tambah</button>
    </form>

    <div class="card">
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Nama</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Logo</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aktif</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th></tr></thead>
            <tbody>
                @foreach($brands as $b)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium">{{ $b->name }}</td>
                    <td class="p-3 text-center">
                        @if($b->logo)
                        <img src="{{ url('uploads/'.$b->logo) }}" alt="{{ $b->name }}" class="h-8 mx-auto object-contain">
                        @else
                        <span class="text-brand-steel text-xs">—</span>
                        @endif
                    </td>
                    <td class="p-3 text-center">{{ $b->is_active ? '✓' : '✗' }}</td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick="document.getElementById('brand-edit-{{ $b->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline min-h-[44px] px-2">Edit</button>
                        <form method="POST" action="{{ route('admin.brands.destroy', $b) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline min-h-[44px] px-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="brand-edit-{{ $b->id }}" class="hidden">
                    <td colspan="4" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.brands.update', $b) }}" class="grid grid-cols-1 md:grid-cols-3 gap-3" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $b->name }}" class="input-field" required>
                            <div class="flex items-center gap-3">
                                @if($b->logo)
                                <img src="{{ url('uploads/'.$b->logo) }}" class="h-8 object-contain rounded">
                                <span class="text-xs text-brand-steel">Ganti:</span>
                                @endif
                                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" class="input-field">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="btn-primary shrink-0">Simpan</button>
                                <button type="button" onclick="document.getElementById('brand-edit-{{ $b->id }}').classList.add('hidden')" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel">Batal</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>

