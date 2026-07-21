<x-admin-layout>
    <x-slot name="title">Service</x-slot>

    <form method="POST" action="{{ route('admin.services.store') }}" class="card p-4 sm:p-5 mb-6 flex gap-2 flex-wrap">
        @csrf
        <input type="text" name="name" placeholder="Nama service" class="input-field w-full sm:flex-1 sm:w-auto" required>
        <input type="number" name="price" placeholder="Harga" class="input-field w-full sm:w-32" step="0.01" required>
        <button type="submit" class="btn-primary shrink-0 min-h-[44px]">Tambah</button>
    </form>

    <div class="card">
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Nama</th><th class="p-3 text-right font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Harga</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aktif</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th></tr></thead>
            <tbody>
                @foreach($services as $s)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium">{{ $s->name }}</td>
                    <td class="p-3 text-right font-medium tabular-nums">Rp{{ number_format($s->price,0,',','.') }}</td>
                    <td class="p-3 text-center">{{ $s->is_active ? '✓' : '✗' }}</td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick="document.getElementById('svc-edit-{{ $s->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline min-h-[44px] px-2">Edit</button>
                        <form method="POST" action="{{ route('admin.services.destroy', $s) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline min-h-[44px] px-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="svc-edit-{{ $s->id }}" class="hidden">
                    <td colspan="4" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.services.update', $s) }}" class="flex gap-2 flex-wrap">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $s->name }}" class="input-field w-full sm:flex-1 sm:w-auto" required>
                            <input type="number" name="price" value="{{ $s->price }}" class="input-field w-full sm:w-32" step="0.01" required>
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

