<x-admin-layout>
    <x-slot name="title">Mekanik</x-slot>

    @if(session('success'))
    <div class="card p-4 mb-4 border-l-4 border-brand-gold bg-brand-amber/5 text-brand-ink font-medium">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.mechanics.store') }}" class="card p-4 sm:p-5 mb-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="text" name="name" placeholder="Nama mekanik" class="input-field" required>
            <input type="text" name="specialist" placeholder="Spesialis (opsional)" class="input-field">
            <button type="submit" class="btn-primary min-h-[44px]">Tambah</button>
        </div>
    </form>

    <div class="card">
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead><tr class="border-b border-brand-steel/20 bg-brand-warm"><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Nama</th><th class="p-3 text-left font-semibold text-brand-steel text-xs uppercase tracking-widest hidden sm:table-cell whitespace-nowrap">Spesialis</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Servis</th><th class="p-3 text-center font-semibold text-brand-steel text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th></tr></thead>
            <tbody>
                @foreach($mechanics as $m)
                <tr class="border-b border-brand-steel/10">
                    <td class="p-3 font-medium">{{ $m->name }}</td>
                    <td class="p-3 hidden sm:table-cell text-brand-steel">{{ $m->specialist ?? '—' }}</td>
                    <td class="p-3 text-center">{{ $m->repair_orders_count }}</td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick="document.getElementById('mech-edit-{{ $m->id }}').classList.toggle('hidden')" class="text-brand-amber text-sm font-medium hover:underline min-h-[44px] px-2">Edit</button>
                        <form method="POST" action="{{ route('admin.mechanics.destroy', $m) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium ml-2 hover:underline min-h-[44px] px-2">Hapus</button>
                        </form>
                    </td>
                </tr>
                <tr id="mech-edit-{{ $m->id }}" class="hidden">
                    <td colspan="5" class="p-3 bg-brand-warm">
                        <form method="POST" action="{{ route('admin.mechanics.update', $m) }}">
                            @csrf @method('PATCH')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <input type="text" name="name" value="{{ $m->name }}" class="input-field" required>
                                <input type="text" name="specialist" value="{{ $m->specialist }}" class="input-field">
                                <button type="submit" class="btn-primary">Simpan</button>
                                <button type="button" onclick="document.getElementById('mech-edit-{{ $m->id }}').classList.add('hidden')" class="btn-outline !border-brand-steel/30 !text-brand-steel">Batal</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>

