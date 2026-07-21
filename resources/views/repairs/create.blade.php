<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <h1 class="font-display font-bold text-2xl text-brand-ink mb-6">Ajukan Servis</h1>

        <form method="POST" action="{{ route('repairs.store') }}" class="card p-5">
            @csrf

            @php
                $serviceOptions = array_merge(
                    [['value' => '0', 'label' => 'Pilih Service (opsional)', 'data-price' => '0']],
                    $services->map(fn($s) => [
                        'value' => (string)$s->id,
                        'label' => $s->name.' — Rp'.number_format($s->price,0,',','.'),
                        'data-price' => (string)$s->price,
                    ])->toArray()
                );
                $vehicleOptions = array_merge(
                    [['value' => '', 'label' => 'Kendaraan Baru']],
                    $vehicles->map(fn($v) => [
                        'value' => (string)$v->id,
                        'label' => $v->plate_number.' — '.$v->brand.' '.$v->model,
                        'data-plate' => $v->plate_number,
                        'data-brand' => $v->brand,
                        'data-model' => $v->model,
                        'data-year' => (string)$v->year,
                    ])->toArray()
                );
                $mechanicOptions = array_merge(
                    [['value' => '', 'label' => 'Pilih Mekanik']],
                    $mechanics->map(fn($m) => [
                        'value' => (string)$m->id,
                        'label' => $m->name.($m->specialist ? ' ('.$m->specialist.')' : ''),
                    ])->toArray()
                );
            @endphp

            <div class="mb-4">
                <x-bottom-sheet-picker name="service_id" label="Jenis Service" placeholder="Pilih Service (opsional)" onselect="updateSummary" :selected="$selectedService ? (string)$selectedService->id : '0'" :options="$serviceOptions" />
            </div>

            <input type="hidden" name="name" value="{{ Auth::user()->name }}">

            <div class="border-t border-brand-border pt-4 mb-4">
                <p class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-3">Data Kendaraan</p>
                <div class="mb-3">
                    <x-bottom-sheet-picker name="vehicle_id" label="Pilih Kendaraan" placeholder="Kendaraan Baru" onselect="toggleVehicleForm" :selected="''" :options="$vehicleOptions" />
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
            </div>

            <div class="border-t border-brand-border pt-4 mb-4">
                <p class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-3">Detail Servis</p>
                <div class="mb-3">
                    <x-bottom-sheet-picker name="mechanic_id" label="Mekanik (opsional)" placeholder="Pilih Mekanik" :selected="''" :options="$mechanicOptions" />
                </div>
                <div>
                    <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Keluhan</label>
                    <textarea name="complaint" class="input-field w-full" rows="4" required placeholder="Jelaskan keluhan kendaraan Anda..."></textarea>
                </div>
            </div>

            <div class="border-t border-brand-border pt-4 mb-4">
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Sparepart yang Dibutuhkan (opsional)</label>
                <div id="items-container"></div>
                <input type="hidden" name="items_json" id="items-json" value="[]">
                <button type="button" onclick="openBottomSheet()" class="btn-outline !border-brand-steel/30 !text-brand-steel text-sm mt-2 w-full justify-center">+ Pilih Sparepart</button>
            </div>

            <div id="payment-summary" class="border-t border-brand-border pt-4 mb-4">
                <p class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-3">Ringkasan Pembayaran</p>
                <div class="bg-brand-warm rounded-xl p-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-brand-ink-muted">Biaya Jasa</span>
                        <span class="font-semibold text-brand-ink tabular-nums" id="summary-service">Rp0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-brand-ink-muted">Total Sparepart</span>
                        <span class="font-semibold text-brand-ink tabular-nums" id="summary-parts">Rp0</span>
                    </div>
                    <div class="border-t border-brand-border pt-2 flex justify-between text-base">
                        <span class="font-bold text-brand-ink">Total Pembayaran</span>
                        <span class="font-bold text-brand-gold tabular-nums" id="summary-total">Rp0</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full">Ajukan Servis</button>
        </form>
    </div>

    <div id="bottom-sheet-overlay" class="fixed inset-0 bg-black/50 z-50 hidden transition-opacity duration-300" onclick="closeBottomSheet()"></div>
    <div id="bottom-sheet" class="fixed bottom-0 left-0 right-0 z-50 bg-white rounded-t-2xl shadow-2xl translate-y-full transition-transform duration-300 max-h-[90vh] flex flex-col" style="will-change: transform;">
        <div class="shrink-0 px-4 pt-3 pb-2 border-b border-brand-border/50">
            <div class="flex justify-center mb-2"><div class="w-10 h-1 rounded-full bg-brand-border"></div></div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-display font-bold text-brand-ink text-lg">Pilih Sparepart</h3>
                <button type="button" onclick="closeBottomSheet()" class="text-brand-ink-muted hover:text-brand-ink p-1">&times;</button>
            </div>
            <div class="flex gap-1.5 overflow-x-auto pb-2 scrollbar-hide -mx-1 px-1" id="category-filters">
                <button type="button" class="cat-filter active shrink-0 px-3 py-1.5 text-xs font-semibold rounded-full border" data-cat="">Semua</button>
                @foreach($categories as $cat)
                <button type="button" class="cat-filter shrink-0 px-3 py-1.5 text-xs font-semibold rounded-full border" data-cat="{{ $cat->name }}">{{ $cat->name }}</button>
                @endforeach
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-ink-faint pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="product-search" placeholder="Cari sparepart..." class="w-full pl-9 pr-3 py-2 text-sm border border-brand-border rounded-lg focus:border-brand-gold focus:ring-1 focus:ring-brand-gold/30 outline-none" oninput="filterProducts()">
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-3 sm:p-4 pb-[calc(64px+env(safe-area-inset-bottom,0px))]" id="product-grid">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3" id="product-list"></div>
        </div>
    </div>

    <style>
        #bottom-sheet-overlay.show { display: block; }
        #bottom-sheet.show { transform: translateY(0); }
        .cat-filter { background: #F3F4F6; color: #6B7280; border-color: #E5E7EB; }
        .cat-filter.active { background: #0F172A; color: white; border-color: #0F172A; }
        .cat-filter:not(.active):hover { background: #E5E7EB; }
        .product-card:active { transform: scale(0.97); }
        #items-container .selected-tag { display: inline-flex; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script>
        const products = {!! json_encode($productsJson) !!};

        let selectedItems = [];
        let itemCounter = 0;

        function openBottomSheet() {
            document.getElementById('bottom-sheet-overlay').classList.add('show');
            document.getElementById('bottom-sheet').classList.add('show');
            document.body.style.overflow = 'hidden';
            renderProducts();
        }

        function closeBottomSheet() {
            document.getElementById('bottom-sheet-overlay').classList.remove('show');
            document.getElementById('bottom-sheet').classList.remove('show');
            document.body.style.overflow = '';
        }

        function filterProducts() {
            renderProducts();
        }

        let activeCategory = '';

        document.querySelectorAll('.cat-filter').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.cat-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeCategory = this.dataset.cat;
                renderProducts();
            });
        });

        function renderProducts() {
            const search = document.getElementById('product-search').value.toLowerCase();
            const filtered = products.filter(p => {
                if (activeCategory && p.category !== activeCategory) return false;
                if (search && !p.name.toLowerCase().includes(search)) return false;
                return true;
            });

            const list = document.getElementById('product-list');
            if (filtered.length === 0) {
                list.innerHTML = '<div class="col-span-full text-center py-8 text-sm text-brand-ink-muted">Tidak ada produk</div>';
                return;
            }

            list.innerHTML = filtered.map(p => {
                const selected = selectedItems.some(s => s.id === p.id);
                const imgSrc = p.image || 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="%23ddd"><rect width="80" height="80"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="10" fill="%23999">No Image</text></svg>';
                return `<div class="product-card rounded-xl border ${selected ? 'border-brand-gold ring-2 ring-brand-gold/20' : 'border-brand-border'} bg-white overflow-hidden cursor-pointer transition-all hover:shadow-md active:scale-95" onclick="selectProduct(${p.id})">
                    <div class="aspect-square bg-brand-warm flex items-center justify-center p-2">
                        <img src="${imgSrc}" alt="${p.name}" class="w-full h-full object-contain" loading="lazy" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2280%22 fill=%22%23ddd%22><rect width=%2280%22 height=%2280%22/><text x=%2250%%22 y=%2250%%22 text-anchor=%22middle%22 dy=%22.3em%22 font-size=%2210%22 fill=%22%23999%22>No Image</text></svg>'">
                    </div>
                    <div class="p-2 sm:p-2.5">
                        <p class="text-[11px] sm:text-xs font-semibold text-brand-ink leading-tight line-clamp-2 min-h-[2em]">${p.name}</p>
                        <p class="text-[11px] sm:text-xs font-bold text-brand-gold mt-0.5">${p.price_fmt}</p>
                        <p class="text-[10px] text-brand-ink-faint">Stok: ${p.stock ?? '~'}</p>
                    </div>
                </div>`;
            }).join('');
        }

        function selectProduct(id) {
            const prod = products.find(p => p.id === id);
            if (!prod) return;
            const idx = selectedItems.findIndex(s => s.id === id);
            if (idx > -1) {
                selectedItems.splice(idx, 1);
            } else {
                selectedItems.push({ ...prod, qty: 1 });
            }
            renderProducts();
            renderSelectedItems();
        }

        function renderSelectedItems() {
            const container = document.getElementById('items-container');
            const jsonInput = document.getElementById('items-json');
            if (selectedItems.length === 0) {
                container.innerHTML = '';
                jsonInput.value = '[]';
                return;
            }
            container.innerHTML = selectedItems.map((item, i) => {
                return `<div class="selected-tag inline-flex items-center gap-2 px-3 py-1.5 bg-brand-warm border border-brand-border rounded-lg text-sm mb-1.5 mr-1.5">
                    <span class="font-medium text-brand-ink truncate max-w-[150px] sm:max-w-[200px]">${item.name}</span>
                    <input type="number" value="${item.qty}" min="1" class="w-14 text-center text-xs border border-brand-border rounded-md py-0.5" onchange="updateQty(${i}, this.value)" onfocus="this.select()">
                    <span class="text-xs text-brand-ink-muted tabular-nums">${item.price_fmt}</span>
                    <button type="button" onclick="removeItem(${i})" class="text-red-400 hover:text-red-600 text-lg leading-none ml-0.5">&times;</button>
                    <input type="hidden" name="items[${i}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">
                    <input type="hidden" name="items[${i}][price]" value="${item.price}">
                    <input type="hidden" name="items[${i}][name]" value="${item.name}">
                </div>`;
            }).join('');
            jsonInput.value = JSON.stringify(selectedItems.map(s => ({ id: s.id, qty: s.qty })));
            itemCounter = selectedItems.length;
            updateSummary();
        }

        function updateQty(idx, val) {
            selectedItems[idx].qty = Math.max(1, parseInt(val) || 1);
            renderSelectedItems();
        }

        function removeItem(idx) {
            selectedItems.splice(idx, 1);
            renderSelectedItems();
            renderProducts();
        }

        function toggleVehicleForm() {
            const hidden = document.querySelector('[name="vehicle_id"]');
            const isNew = !hidden.value;
            document.getElementById('new-vehicle-fields').style.display = isNew ? 'grid' : 'none';
            if (!isNew) {
                document.getElementById('plate-number').value = hidden.dataset.plate || '';
                document.getElementById('brand').value = hidden.dataset.brand || '';
                document.getElementById('model').value = hidden.dataset.model || '';
                document.getElementById('year').value = hidden.dataset.year || '';
            } else {
                document.getElementById('plate-number').value = '';
                document.getElementById('brand').value = '';
                document.getElementById('model').value = '';
                document.getElementById('year').value = '';
            }
        }

        toggleVehicleForm();
        updateSummary();

        function updateSummary() {
            const serviceFee = parseInt(document.querySelector('[name="service_id"]')?.dataset?.price) || 0;

            const partsTotal = selectedItems.reduce((sum, item) => sum + (item.price * item.qty), 0);

            const total = serviceFee + partsTotal;

            const fmt = n => 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            document.getElementById('summary-service').textContent = fmt(serviceFee);
            document.getElementById('summary-parts').textContent = fmt(partsTotal);
            document.getElementById('summary-total').textContent = fmt(total);
        }
    </script>
</x-app-layout>
