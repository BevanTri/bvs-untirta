<x-admin-layout>
    <x-slot name="title">Mekanik</x-slot>

    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400 rounded-xl p-4 text-sm mb-6">{{ session('success') }}</div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="card p-5">
            <form method="POST" action="{{ route('admin.mechanics.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <input type="text" name="name" placeholder="Nama mekanik" class="input-field" required>
                    <input type="text" name="specialist" placeholder="Spesialis (opsional)" class="input-field">
                    <button type="submit" class="btn-primary w-full md:w-auto">Tambah</button>
                </div>
            </form>
        </div>

        <div class="card p-0 overflow-hidden">
            <div class="table-wrap overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr class="border-b border-brand-border/20 dark:border-brand-navy-3/50 bg-brand-warm dark:bg-brand-navy-3/30">
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Nama</th>
                            <th class="p-3 text-left font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Spesialis</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Servis</th>
                            <th class="p-3 text-center font-semibold text-brand-ink-muted text-xs uppercase tracking-widest whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mechanics as $m)
                        <tr class="border-b border-brand-border/10 dark:border-brand-navy-3/50">
                            <td class="p-3 text-left font-medium">{{ $m->name }}</td>
                            <td class="p-3 text-left text-brand-ink-muted whitespace-nowrap">{{ $m->specialist ?? '—' }}</td>
                            <td class="p-3 text-center font-mono tabular-nums">{{ $m->repair_orders_count }}</td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="document.getElementById('mech-edit-{{ $m->id }}').classList.toggle('hidden')" class="btn-ghost btn-sm">Edit</button>
                                <form method="POST" action="{{ route('admin.mechanics.destroy', $m) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <tr id="mech-edit-{{ $m->id }}" class="hidden">
                            <td colspan="5" class="p-4 bg-brand-warm dark:bg-brand-navy-3/30">
                                <form method="POST" action="{{ route('admin.mechanics.update', $m) }}">
                                    @csrf @method('PATCH')
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <input type="text" name="name" value="{{ $m->name }}" class="input-field" required>
                                        <input type="text" name="specialist" value="{{ $m->specialist }}" class="input-field">
                                        <button type="submit" class="btn-primary w-full md:w-auto">Simpan</button>
                                        <button type="button" onclick="document.getElementById('mech-edit-{{ $m->id }}').classList.add('hidden')" class="btn-outline w-full md:w-auto">Batal</button>
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
