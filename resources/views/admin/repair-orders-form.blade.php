<x-admin-layout>
    <x-slot name="title">{{ isset($order) ? 'Edit Servis' : 'Buat Servis' }}</x-slot>

    <form method="POST" action="{{ isset($order) ? route('admin.repair-orders.update', $order) : route('admin.repair-orders.store') }}" class="card p-5 max-w-3xl">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Pelanggan</label>
                <select name="customer_id" class="input-field w-full" required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ isset($order) && $order->customer_id === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Kendaraan</label>
                <select name="vehicle_id" class="input-field w-full" required>
                    <option value="">Pilih Kendaraan</option>
                    @foreach($vehicles as $v)
                    <option value="{{ $v->id }}" data-customer="{{ $v->customer_id }}" {{ isset($order) && $order->vehicle_id === $v->id ? 'selected' : '' }}>{{ $v->plate_number }} — {{ $v->brand }} {{ $v->model }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Mekanik</label>
                <select name="mechanic_id" class="input-field w-full">
                    <option value="">Pilih Mekanik</option>
                    @foreach($mechanics as $m)
                    <option value="{{ $m->id }}" {{ isset($order) && $order->mechanic_id === $m->id ? 'selected' : '' }}>{{ $m->name }} {{ $m->specialist ? "($m->specialist)" : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Tanggal</label>
                <input type="date" name="date" value="{{ isset($order) ? $order->date->format('Y-m-d') : date('Y-m-d') }}" class="input-field w-full" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Keluhan</label>
            <textarea name="complaint" class="input-field w-full" rows="3" required>{{ $order->complaint ?? '' }}</textarea>
        </div>

        <div class="mb-4">
            <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Tindakan</label>
            <textarea name="action" class="input-field w-full" rows="3">{{ $order->action ?? '' }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Biaya Jasa</label>
                <input type="number" name="service_fee" value="{{ $order->service_fee ?? 0 }}" class="input-field w-full" min="0" step="0.01" required>
            </div>
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Status</label>
                <select name="status" class="input-field w-full" required>
                    <option value="menunggu" {{ isset($order) && $order->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="proses" {{ isset($order) && $order->status === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ isset($order) && $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ isset($order) && $order->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Sparepart Dipakai</label>
            <div id="items-container">
                @if(isset($order) && $order->items->count())
                    @foreach($order->items as $idx => $item)
                    <div class="item-row flex flex-wrap gap-2 mb-2">
                        <select name="items[{{ $idx }}][product_id]" class="input-field w-full sm:flex-1 sm:w-auto">
                            <option value="">Pilih sparepart</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price }}" {{ $item->product_id === $p->id ? 'selected' : '' }}>{{ $p->name }} — Rp{{ number_format($p->price,0,',','.') }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="items[{{ $idx }}][name]" value="{{ $item->name }}" placeholder="Nama" class="input-field flex-1 min-w-[120px]" required>
                        <input type="number" name="items[{{ $idx }}][quantity]" value="{{ $item->quantity }}" placeholder="Qty" class="input-field w-24" min="1" required>
                        <input type="number" name="items[{{ $idx }}][price]" value="{{ $item->price }}" placeholder="Harga" class="input-field w-32" step="0.01" required>
                        <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-400 hover:text-red-600 shrink-0 px-2">&times;</button>
                    </div>
                    @endforeach
                @endif
            </div>
            <button type="button" onclick="addItem()" class="btn-outline mt-2 !border-brand-steel/30 !text-brand-steel text-sm">+ Tambah Sparepart</button>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">{{ isset($order) ? 'Simpan' : 'Buat Servis' }}</button>
            <a href="{{ route('admin.repair-orders') }}" class="btn-outline !border-brand-steel/30 !text-brand-steel">Batal</a>
        </div>
    </form>

    <script>
        let itemIdx = {{ (isset($order) ? $order->items->count() : 0) }};
        function addItem() {
            const html = `<div class="item-row flex flex-wrap gap-2 mb-2">
                <select name="items[${itemIdx}][product_id]" class="input-field w-full sm:flex-1 sm:w-auto" onchange="fillItem(this)">
                    <option value="">Pilih sparepart</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} — Rp{{ number_format($p->price,0,',','.') }}</option>
                    @endforeach
                </select>
                <input type="text" name="items[${itemIdx}][name]" placeholder="Nama" class="input-field flex-1 min-w-[120px]" required>
                <input type="number" name="items[${itemIdx}][quantity]" placeholder="Qty" class="input-field w-24" min="1" value="1" required>
                <input type="number" name="items[${itemIdx}][price]" placeholder="Harga" class="input-field w-32" step="0.01" required>
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-400 hover:text-red-600 shrink-0 px-2">&times;</button>
            </div>`;
            document.getElementById('items-container').insertAdjacentHTML('beforeend', html);
            itemIdx++;
        }
        function fillItem(sel) {
            const row = sel.closest('.item-row');
            const opt = sel.options[sel.selectedIndex];
            if (opt.value && opt.dataset.price) {
                row.querySelector('[name$="[name]"]').value = opt.text.split(' —')[0];
                row.querySelector('[name$="[price]"]').value = opt.dataset.price;
            }
        }
    </script>
</x-admin-layout>
