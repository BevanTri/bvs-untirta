<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <h1 class="font-display font-bold text-2xl text-brand-ink mb-6">Ajukan Servis</h1>

        <form method="POST" action="{{ route('repairs.store') }}" class="card p-5">
            @csrf

            <div class="mb-4">
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Jenis Service</label>
                <select name="service_id" class="input-field w-full">
                    <option value="">Pilih Service (opsional)</option>
                    @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ $selectedService && $selectedService->id == $s->id ? 'selected' : '' }}>
                        {{ $s->name }} — Rp{{ number_format($s->price,0,',','.') }}
                    </option>
                    @endforeach
                </select>
                @if($selectedService)
                <p class="text-xs text-brand-gold mt-1">Biaya service: Rp{{ number_format($selectedService->price,0,',','.') }}</p>
                @endif
            </div>

            <input type="hidden" name="name" value="{{ Auth::user()->name }}">

            <div class="border-t border-brand-border pt-4 mb-4">
                <p class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-3">Data Kendaraan</p>
                <div class="mb-3">
                    <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Pilih Kendaraan</label>
                    <select id="vehicle-select" class="input-field w-full" onchange="toggleVehicleForm()">
                        <option value="">Kendaraan Baru</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" data-plate="{{ $v->plate_number }}" data-brand="{{ $v->brand }}" data-model="{{ $v->model }}" data-year="{{ $v->year }}">{{ $v->plate_number }} — {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="new-vehicle-fields" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Plat Nomor</label>
                        <input type="text" name="plate_number" id="plate-number" class="input-field w-full" required placeholder="AB 1234 CD">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Merk</label>
                        <input type="text" name="brand" id="brand" class="input-field w-full" required placeholder="Honda / Yamaha / dll">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Model</label>
                        <input type="text" name="model" id="model" class="input-field w-full" required placeholder="Vario 125 / NMax">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Tahun (opsional)</label>
                        <input type="number" name="year" id="year" class="input-field w-full" placeholder="2020" min="1900" max="2099">
                    </div>
                </div>
                <input type="hidden" name="vehicle_id" id="vehicle-id" value="">
            </div>

            <div class="border-t border-brand-border pt-4 mb-4">
                <p class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-3">Detail Servis</p>
                <div class="mb-3">
                    <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Mekanik (opsional)</label>
                    <select name="mechanic_id" class="input-field w-full">
                        <option value="">Pilih Mekanik</option>
                        @foreach($mechanics as $m)
                        <option value="{{ $m->id }}">{{ $m->name }} {{ $m->specialist ? "($m->specialist)" : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Keluhan</label>
                    <textarea name="complaint" class="input-field w-full" rows="4" required placeholder="Jelaskan keluhan kendaraan Anda..."></textarea>
                </div>
            </div>

            <div class="border-t border-brand-border pt-4 mb-4">
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Sparepart yang Dibutuhkan (opsional)</label>
                <div id="items-container">
                    <div class="item-row flex flex-wrap gap-2 mb-2">
                        <select name="items[0][product_id]" class="input-field flex-1 min-w-[150px]" onchange="fillItem(this)">
                            <option value="">Pilih sparepart</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} — Rp{{ number_format($p->price,0,',','.') }} {{ $p->stock ? "(stok: $p->stock)" : '' }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][quantity]" placeholder="Qty" class="input-field w-24" min="1" value="1" required>
                        <input type="number" name="items[0][price]" placeholder="Harga" class="input-field w-32 price-field" step="0.01" readonly required>
                        <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-400 hover:text-red-600 shrink-0 px-2">&times;</button>
                    </div>
                </div>
                <button type="button" onclick="addItem()" class="btn-outline mt-2 !border-brand-steel/30 !text-brand-steel text-sm">+ Tambah Sparepart</button>
            </div>

            <button type="submit" class="btn-primary w-full">Ajukan Servis</button>
        </form>
    </div>

    <script>
        let itemIdx = 1;
        function addItem() {
            const html = `<div class="item-row flex flex-wrap gap-2 mb-2">
                <select name="items[${itemIdx}][product_id]" class="input-field flex-1 min-w-[150px]" onchange="fillItem(this)">
                    <option value="">Pilih sparepart</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} — Rp{{ number_format($p->price,0,',','.') }}</option>
                    @endforeach
                </select>
                <input type="number" name="items[${itemIdx}][quantity]" placeholder="Qty" class="input-field w-24" min="1" value="1" required>
                <input type="number" name="items[${itemIdx}][price]" placeholder="Harga" class="input-field w-32 price-field" step="0.01" readonly required>
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-400 hover:text-red-600 shrink-0 px-2">&times;</button>
            </div>`;
            document.getElementById('items-container').insertAdjacentHTML('beforeend', html);
            itemIdx++;
        }
        function fillItem(sel) {
            const row = sel.closest('.item-row');
            const opt = sel.options[sel.selectedIndex];
            if (opt.value && opt.dataset.price) {
                row.querySelector('.price-field').value = opt.dataset.price;
            } else {
                row.querySelector('.price-field').value = '';
            }
        }
        function toggleVehicleForm() {
            const sel = document.getElementById('vehicle-select');
            const opt = sel.options[sel.selectedIndex];
            const isNew = !opt.value;
            document.getElementById('new-vehicle-fields').style.display = isNew ? 'grid' : 'none';
            document.getElementById('vehicle-id').value = isNew ? '' : opt.value;
            if (!isNew) {
                document.getElementById('plate-number').value = opt.dataset.plate || '';
                document.getElementById('brand').value = opt.dataset.brand || '';
                document.getElementById('model').value = opt.dataset.model || '';
                document.getElementById('year').value = opt.dataset.year || '';
            } else {
                document.getElementById('plate-number').value = '';
                document.getElementById('brand').value = '';
                document.getElementById('model').value = '';
                document.getElementById('year').value = '';
            }
        }
    </script>
</x-app-layout>
