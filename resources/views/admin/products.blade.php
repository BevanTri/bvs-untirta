<x-admin-layout>
    <x-slot name="title">Produk</x-slot>

    @if(session('success'))
    <div class="card p-4 mb-4 border-l-4 border-brand-gold bg-brand-amber/5 text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('admin.products') }}" class="card p-4 mb-6 flex gap-2 flex-wrap">
        <input type="text" name="search" placeholder="Cari produk..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
        <select name="category_id" class="input-field w-full sm:w-auto" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ ($filterCat ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @if($search || $filterCat)
        <a href="{{ route('admin.products') }}" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel min-h-[44px]">Reset</a>
        @endif
        <button type="submit" class="btn-primary shrink-0 min-h-[44px]">Cari</button>
    </form>

    <form method="POST" action="{{ route('admin.products.store') }}" class="card p-5 mb-6" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
            <select name="category_id" class="input-field" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <input type="text" name="name" placeholder="Nama produk" class="input-field" required>
            <input type="number" name="price" placeholder="Harga" class="input-field" step="0.01" required>
        </div>
        <div class="mb-3">
            <textarea name="description" placeholder="Deskripsi produk (opsional)" class="input-field" rows="2"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="number" name="stock" placeholder="Stok (kosongkan jika tidak terbatas)" class="input-field">
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="input-field">
            <button type="submit" class="btn-primary">Tambah Produk</button>
        </div>
    </form>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Nama</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest hidden lg:table-cell whitespace-nowrap">Deskripsi</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest hidden sm:table-cell whitespace-nowrap">Kategori</th><th class="p-3 text-right font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Harga</th><th class="p-3 text-right font-semibold text-brand-steel text-xs uppercase tracking-widest hidden sm:table-cell whitespace-nowrap">Stok</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aktif</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th></tr></thead>
            <tbody>
                @foreach($products as $p)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium break-words max-w-[160px] sm:max-w-none">{{ $p->name }}</td>
                    <td class="p-3 text-brand-steel text-xs hidden lg:table-cell max-w-[200px] truncate">{{ $p->description ?? '—' }}</td>
                    <td class="p-3 text-brand-steel hidden sm:table-cell whitespace-nowrap">{{ $p->category->name }}</td>
                    <td class="p-3 text-right font-medium tabular-nums whitespace-nowrap">Rp{{ number_format($p->price,0,',','.') }}</td>
                    <td class="p-3 text-right tabular-nums hidden sm:table-cell whitespace-nowrap">{{ $p->stock ?? '∞' }}</td>
                    <td class="p-3 text-center">{{ $p->is_active ? '✓' : '✗' }}</td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick="document.getElementById('prod-edit-{{ $p->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline min-h-[44px] px-2">Edit</button>
                        <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline min-h-[44px] px-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="prod-edit-{{ $p->id }}" class="hidden">
                    <td colspan="7" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.products.update', $p) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                <select name="category_id" class="input-field" required>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $p->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="name" value="{{ $p->name }}" class="input-field" required>
                                <input type="number" name="price" value="{{ $p->price }}" class="input-field" step="0.01" required>
                                <textarea name="description" placeholder="Deskripsi" class="input-field" rows="2">{{ $p->description }}</textarea>
                                <input type="number" name="stock" value="{{ $p->stock }}" class="input-field" placeholder="Stok">
                                <div class="flex items-center gap-3">
                                    @if($p->image)
                                    <img src="{{ url('uploads/'.$p->image) }}?v={{ $p->updated_at->timestamp }}" class="h-10 w-10 object-contain rounded border border-brand-steel/30 shrink-0">
                                    @endif
                                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="input-field flex-1">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="btn-primary shrink-0">Simpan</button>
                                <button type="button" onclick="document.getElementById('prod-edit-{{ $p->id }}').classList.add('hidden')" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel">Batal</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
