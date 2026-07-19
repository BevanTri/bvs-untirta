<x-admin-layout>
    <x-slot name="title">Service</x-slot>

    <form method="POST" action="{{ route('admin.services.store') }}" class="card p-5 mb-6 flex gap-2">
        @csrf
        <input type="text" name="name" placeholder="Nama service" class="input-field" required>
        <input type="number" name="price" placeholder="Harga" class="input-field w-32" step="0.01" required>
        <button type="submit" class="btn-primary shrink-0">Tambah</button>
    </form>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest">Nama</th><th class="p-3 text-right font-semibold text-brand-steel text-xs uppercase tracking-widest">Harga</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Aktif</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest">Aksi</th></tr></thead>
            <tbody>
                @foreach($services as $s)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium">{{ $s->name }}</td>
                    <td class="p-3 text-right font-medium tabular-nums">Rp{{ number_format($s->price,0,',','.') }}</td>
                    <td class="p-3 text-center">{{ $s->is_active ? '✓' : '✗' }}</td>
                    <td class="p-3 text-center">
                        <button onclick="document.getElementById('svc-edit-{{ $s->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline">Edit</button>
                        <form method="POST" action="{{ route('admin.services.destroy', $s) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="svc-edit-{{ $s->id }}" class="hidden">
                    <td colspan="4" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.services.update', $s) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $s->name }}" class="input-field" required>
                            <input type="number" name="price" value="{{ $s->price }}" class="input-field w-32" step="0.01" required>
                            <button type="submit" class="btn-primary shrink-0">Simpan</button>
                            <button type="button" onclick="document.getElementById('svc-edit-{{ $s->id }}').classList.add('hidden')" class="btn-outline shrink-0 !border-brand-steel/30 !text-brand-steel">Batal</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
