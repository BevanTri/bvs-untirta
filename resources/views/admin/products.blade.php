<x-admin-layout>
    <x-slot name="title">Produk</x-slot>

    @if(session('success'))
    <div class="card p-5 mb-8 border-l-4 border-brand-gold text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    {{-- Search & Filter --}}
    <div class="card p-6 mb-8">
        <form method="GET" action="{{ route('admin.products') }}" class="flex gap-3 flex-wrap">
            <input type="text" name="search" placeholder="Cari produk..." value="{{ $search ?? '' }}" class="input-field w-full sm:flex-1 sm:w-auto">
            <select name="category_id" class="input-field w-full sm:w-auto" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($filterCat ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            @if($search || $filterCat)
            <a href="{{ route('admin.products') }}" class="btn-outline shrink-0 min-h-[44px]">Reset</a>
            @endif
            <button type="submit" class="btn-primary shrink-0 w-full md:w-auto min-h-[44px]">Cari</button>
        </form>
    </div>

    {{-- Add Product Form --}}
    <div class="card p-6 mb-8">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <select name="category_id" class="input-field" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="name" placeholder="Nama produk" class="input-field" required>
                <input type="number" name="price" placeholder="Harga" class="input-field" step="0.01" required>
            </div>
            <div class="mb-4">
                <textarea name="description" placeholder="Deskripsi produk (opsional)" class="input-field" rows="2"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <input type="number" name="stock" placeholder="Stok (kosongkan jika tidak terbatas)" class="input-field">
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="input-field">
                <button type="submit" class="btn-primary w-full md:w-auto">Tambah Produk</button>
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="card">
        <div class="table-wrap">
            <table class="table-base">
                <thead>
                    <tr>
                        <th class="text-left">Nama</th>
                        <th class="text-left hidden lg:table-cell">Deskripsi</th>
                        <th class="text-left hidden sm:table-cell">Kategori</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right hidden sm:table-cell">Stok</th>
                        <th class="text-center">Aktif</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td class="font-medium break-words max-w-[160px] sm:max-w-none">{{ $p->name }}</td>
                        <td class="text-brand-ink-muted text-xs hidden lg:table-cell max-w-[200px] truncate">{{ $p->description ?? '—' }}</td>
                        <td class="text-brand-ink-muted hidden sm:table-cell whitespace-nowrap">{{ $p->category->name }}</td>
                        <td class="text-right font-mono tabular-nums whitespace-nowrap">Rp{{ number_format($p->price,0,',','.') }}</td>
                        <td class="text-right tabular-nums hidden sm:table-cell whitespace-nowrap">{{ $p->stock ?? '∞' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $p->is_active ? 'badge-success' : 'badge-danger' }}">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="text-center whitespace-nowrap">
                            <button onclick="document.getElementById('prod-edit-{{ $p->id }}').classList.toggle('hidden')" class="btn-ghost btn-sm">Edit</button>
                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm ml-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="prod-edit-{{ $p->id }}" class="hidden">
                        <td colspan="7" class="p-4 bg-brand-warm">
                            <form method="POST" action="{{ route('admin.products.update', $p) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                    <select name="category_id" class="input-field" required>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $p->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="name" value="{{ $p->name }}" class="input-field" required>
                                    <input type="number" name="price" value="{{ $p->price }}" class="input-field" step="0.01" required>
                                    <textarea name="description" placeholder="Deskripsi" class="input-field" rows="2">{{ $p->description }}</textarea>
                                    <input type="number" name="stock" value="{{ $p->stock }}" class="input-field" placeholder="Stok">
                                    <div class="flex items-center gap-4">
                                        @if($p->image)
                                        <img src="{{ url('uploads/'.$p->image) }}?v={{ $p->updated_at->timestamp }}" class="h-10 w-10 object-contain rounded border border-brand-border shrink-0">
                                        @endif
                                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="input-field flex-1">
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="btn-primary shrink-0 w-full md:w-auto">Simpan</button>
                                    <button type="button" onclick="document.getElementById('prod-edit-{{ $p->id }}').classList.add('hidden')" class="btn-outline shrink-0 w-full md:w-auto">Batal</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
