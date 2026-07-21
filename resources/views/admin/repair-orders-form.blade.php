<x-admin-layout>
    <x-slot name="title">{{ isset($order) ? 'Edit Servis' : 'Buat Servis' }}</x-slot>

    <form method="POST" action="{{ isset($order) ? route('admin.repair-orders.update', $order) : route('admin.repair-orders.store') }}" class="card p-5 max-w-3xl">
        @csrf

        @php
            $customerOptions = array_merge(
                [['value' => '', 'label' => 'Pilih Pelanggan']],
                $customers->map(fn($c) => ['value' => (string)$c->id, 'label' => $c->name])->toArray()
            );
            $vehicleOptions = array_merge(
                [['value' => '', 'label' => 'Pilih Kendaraan']],
                $vehicles->map(fn($v) => [
                    'value' => (string)$v->id,
                    'label' => $v->plate_number.' — '.$v->brand.' '.$v->model,
                    'data-customer' => (string)$v->customer_id,
                ])->toArray()
            );
            $mechanicOptions = array_merge(
                [['value' => '', 'label' => 'Pilih Mekanik']],
                $mechanics->map(fn($m) => [
                    'value' => (string)$m->id,
                    'label' => $m->name.($m->specialist ? ' ('.$m->specialist.')' : ''),
                ])->toArray()
            );
            $statusOptions = [
                ['value' => 'menunggu', 'label' => 'Menunggu'],
                ['value' => 'proses', 'label' => 'Proses'],
                ['value' => 'selesai', 'label' => 'Selesai'],
                ['value' => 'dibatalkan', 'label' => 'Dibatalkan'],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
            <div>
                <x-bottom-sheet-picker name="customer_id" label="Pelanggan" placeholder="Pilih Pelanggan" required :selected="isset($order) ? (string)$order->customer_id : ''" :options="$customerOptions" />
            </div>
            <div>
                <x-bottom-sheet-picker name="vehicle_id" label="Kendaraan" placeholder="Pilih Kendaraan" required :selected="isset($order) ? (string)$order->vehicle_id : ''" :options="$vehicleOptions" />
            </div>
            <div>
                <x-bottom-sheet-picker name="mechanic_id" label="Mekanik" placeholder="Pilih Mekanik" :selected="isset($order) ? (string)$order->mechanic_id : ''" :options="$mechanicOptions" />
            </div>
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Tanggal</label>
                <input type="date" name="date" value="{{ isset($order) ? $order->date->format('Y-m-d') : date('Y-m-d') }}" class="input-field w-full" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Keluhan</label>
            <textarea name="complaint" class="input-field w-full" rows="3" required>{{ $order->complaint ?? '' }}</textarea>
        </div>

        <div class="mb-3">
            <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Tindakan</label>
            <textarea name="action" class="input-field w-full" rows="3">{{ $order->action ?? '' }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
            <div>
                <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Biaya Jasa</label>
                <input type="number" name="service_fee" id="service-fee" value="{{ $order->service_fee ?? 0 }}" class="input-field w-full" min="0" step="0.01" required oninput="updateSummary()">
            </div>
            <div>
                <x-bottom-sheet-picker name="status" label="Status" placeholder="Pilih Status" required :selected="isset($order) ? $order->status : 'menunggu'" :options="$statusOptions" />
            </div>
        </div>

        <div class="mb-3">
            <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">Sparepart Dipakai</label>
            <div id="items-container">
                @if(isset($order) && $order->items->count())
                    @foreach($order->items as $idx => $item)
                    <div class="item-row inline-flex items-center gap-2 px-3 py-1.5 bg-brand-warm border border-brand-border rounded-lg text-sm mb-1.5 mr-1.5">
                        <span class="font-medium text-brand-ink truncate max-w-[150px] sm:max-w-[200px]">{{ $item->name }}</span>
                        <input type="number" name="items[{{ $idx }}][quantity]" value="{{ $item->quantity }}" min="1" class="w-14 text-center text-xs border border-brand-border rounded-md py-0.5" oninput="updateSummary()">
                        <span class="text-xs text-brand-ink-muted tabular-nums">Rp{{ number_format($item->price,0,',','.') }}</span>
                        <button type="button" onclick="this.closest('.item-row').remove(); updateSummary();" class="text-red-400 hover:text-red-600 text-lg leading-none ml-0.5">&times;</button>
                        <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                        <input type="hidden" name="items[{{ $idx }}][name]" value="{{ $item->name }}">
                        <input type="hidden" name="items[{{ $idx }}][price]" value="{{ $item->price }}">
                    </div>
                    @endforeach
                @endif
            </div>
            <button type="button" onclick="openBottomSheet()" class="btn-outline mt-2 !border-brand-steel/30 !text-brand-steel text-sm w-full justify-center">+ Pilih Sparepart</button>
        </div>

        <div id="payment-summary" class="border-t border-brand-border pt-3 mb-3">
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

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">{{ isset($order) ? 'Simpan' : 'Buat Servis' }}</button>
            <a href="{{ route('admin.repair-orders') }}" class="btn-outline !border-brand-steel/30 !text-brand-steel">Batal</a>
        </div>
    </form>

    <div id="bottom-sheet-overlay" class="fixed inset-0 bg-black/50 z-50" onclick="if(event.target===this)closeBottomSheet()">
    <div id="bottom-sheet" class="bg-white rounded-t-2xl shadow-2xl flex flex-col" onclick="event.stopPropagation();">
        <div class="shrink-0 px-4 pt-3 pb-2 border-b border-brand-border/50">
            <div class="flex justify-center mb-2 md:hidden"><div class="w-10 h-1 rounded-full bg-brand-border"></div></div>
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
        <div class="flex-1 overflow-y-auto p-3 sm:p-4 pb-[calc(80px+env(safe-area-inset-bottom,0px))]" id="product-grid">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3" id="product-list"></div>
        </div>
    </div>
    </div>

    <style>
        #bottom-sheet-overlay { transition: opacity 250ms ease; opacity: 0; pointer-events: none; }
        #bottom-sheet-overlay.show { opacity: 1; pointer-events: auto; }
        #bottom-sheet { position: fixed; bottom: 0; left: 0; right: 0; max-height: 90dvh; transform: translateY(100%); transition: transform 300ms cubic-bezier(0.32, 0.72, 0, 1); }
        #bottom-sheet.show { transform: translateY(0); }
        .cat-filter { background: #F3F4F6; color: #6B7280; border-color: #E5E7EB; }
        .cat-filter.active { background: #0F172A; color: white; border-color: #0F172A; }
        .cat-filter:not(.active):hover { background: #E5E7EB; }
        .product-card:active { transform: scale(0.97); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        @media (min-width: 768px) {
            #bottom-sheet-overlay { display: flex; align-items: center; justify-content: center; }
            #bottom-sheet { position: relative !important; bottom: auto !important; left: auto !important; right: auto !important; transform: none !important; width: 100%; max-width: 560px; max-height: 80vh; margin: 0 16px; border-radius: 16px; opacity: 0; transition: opacity 250ms ease; }
            #bottom-sheet.show { opacity: 1; }
        }
    </style>

    <script>
        const products = {!! json_encode($productsJson) !!};

        let selectedItems = [];
        let itemIdx = {{ (isset($order) ? $order->items->count() : 0) }};
        let activeCategory = '';

        function updateSummary() {
            const fee = parseInt(document.getElementById('service-fee').value) || 0;
            let parts = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qtyInput = row.querySelector('input[name$="[quantity]"]');
                const priceInput = row.querySelector('input[name$="[price]"]');
                const qty = parseInt(qtyInput?.value) || 0;
                const price = parseInt(priceInput?.value) || 0;
                parts += qty * price;
            });
            const total = fee + parts;
            const fmt = n => 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            document.getElementById('summary-service').textContent = fmt(fee);
            document.getElementById('summary-parts').textContent = fmt(parts);
            document.getElementById('summary-total').textContent = fmt(total);
        }

        function openBottomSheet() {
            document.getElementById('bottom-sheet-overlay').classList.add('show');
            document.getElementById('bottom-sheet').classList.add('show');
            document.body.dataset.scrollY = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            document.body.style.top = '-' + window.scrollY + 'px';
            renderProducts();
        }

        function closeBottomSheet() {
            var sy = parseFloat(document.body.dataset.scrollY || '0');
            document.getElementById('bottom-sheet-overlay').classList.remove('show');
            document.getElementById('bottom-sheet').classList.remove('show');
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.width = '';
            document.body.style.top = '';
            window.scrollTo(0, sy);
            delete document.body.dataset.scrollY;
        }

        function filterProducts() {
            renderProducts();
        }

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
                const imgSrc = p.image || 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="%23ddd"><rect width="80" height="80"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="10" fill="%23999">No Image</text></svg>';
                return `<div class="product-card rounded-xl border border-brand-border bg-white overflow-hidden cursor-pointer transition-all hover:shadow-md active:scale-95" onclick="selectProduct(${p.id})">
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

            const container = document.getElementById('items-container');
            const html = `<div class="item-row inline-flex items-center gap-2 px-3 py-1.5 bg-brand-warm border border-brand-border rounded-lg text-sm mb-1.5 mr-1.5">
                <span class="font-medium text-brand-ink truncate max-w-[150px] sm:max-w-[200px]">${prod.name}</span>
                <input type="number" name="items[${itemIdx}][quantity]" value="1" min="1" class="w-14 text-center text-xs border border-brand-border rounded-md py-0.5" oninput="updateSummary()">
                <span class="text-xs text-brand-ink-muted tabular-nums">${prod.price_fmt}</span>
                <button type="button" onclick="this.closest('.item-row').remove(); updateSummary();" class="text-red-400 hover:text-red-600 text-lg leading-none ml-0.5">&times;</button>
                <input type="hidden" name="items[${itemIdx}][product_id]" value="${prod.id}">
                <input type="hidden" name="items[${itemIdx}][name]" value="${prod.name}">
                <input type="hidden" name="items[${itemIdx}][price]" value="${prod.price}">
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            itemIdx++;
            closeBottomSheet();
            updateSummary();
        }

        updateSummary();
    </script>
</x-admin-layout>
