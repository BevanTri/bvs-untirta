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
                    <div class="item-row inline-flex items-center gap-2 px-3 py-1.5 bg-brand-warm border border-brand-border rounded-lg text-sm mb-1.5 mr-1.5">
                        <span class="font-medium text-brand-ink truncate max-w-[150px] sm:max-w-[200px]">{{ $item->name }}</span>
                        <input type="number" name="items[{{ $idx }}][quantity]" value="{{ $item->quantity }}" min="1" class="w-14 text-center text-xs border border-brand-border rounded-md py-0.5">
                        <span class="text-xs text-brand-ink-muted tabular-nums">Rp{{ number_format($item->price,0,',','.') }}</span>
                        <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-400 hover:text-red-600 text-lg leading-none ml-0.5">&times;</button>
                        <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                        <input type="hidden" name="items[{{ $idx }}][name]" value="{{ $item->name }}">
                        <input type="hidden" name="items[{{ $idx }}][price]" value="{{ $item->price }}">
                    </div>
                    @endforeach
                @endif
            </div>
            <button type="button" onclick="openBottomSheet()" class="btn-outline mt-2 !border-brand-steel/30 !text-brand-steel text-sm w-full justify-center">+ Pilih Sparepart</button>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">{{ isset($order) ? 'Simpan' : 'Buat Servis' }}</button>
            <a href="{{ route('admin.repair-orders') }}" class="btn-outline !border-brand-steel/30 !text-brand-steel">Batal</a>
        </div>
    </form>

    <div id="bottom-sheet-overlay" class="fixed inset-0 bg-black/50 z-50 hidden transition-opacity duration-300" onclick="closeBottomSheet()"></div>
    <div id="bottom-sheet" class="fixed bottom-0 left-0 right-0 z-50 bg-white rounded-t-2xl shadow-2xl translate-y-full transition-transform duration-300 max-h-[85vh] flex flex-col" style="will-change: transform;">
        <div class="shrink-0 px-4 pt-3 pb-2 border-b border-brand-border/50">
            <div class="flex justify-center mb-2"><div class="w-10 h-1 rounded-full bg-brand-border"></div></div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-display font-bold text-brand-ink text-lg">Pilih Sparepart</h3>
                <button type="button" onclick="closeBottomSheet()" class="text-brand-ink-muted hover:text-brand-ink p-1">&times;</button>
            </div>
            <div class="flex gap-1.5 overflow-x-auto pb-2 scrollbar-hide -mx-1 px-1" id="category-filters">
                <button type="button" class="cat-filter active shrink-0 px-3 py-1.5 text-xs font-semibold rounded-full bg-brand-navy text-white" data-cat="">Semua</button>
                @foreach($categories as $cat)
                <button type="button" class="cat-filter shrink-0 px-3 py-1.5 text-xs font-semibold rounded-full bg-brand-warm text-brand-ink-muted hover:bg-brand-border" data-cat="{{ $cat->name }}">{{ $cat->name }}</button>
                @endforeach
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-ink-faint pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="product-search" placeholder="Cari sparepart..." class="w-full pl-9 pr-3 py-2 text-sm border border-brand-border rounded-lg focus:border-brand-gold focus:ring-1 focus:ring-brand-gold/30 outline-none" oninput="filterProducts()">
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-3 sm:p-4" id="product-grid">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3" id="product-list"></div>
        </div>
    </div>

    <style>
        #bottom-sheet-overlay.show { display: block; }
        #bottom-sheet.show { transform: translateY(0); }
        .cat-filter.active { background: #0F172A; color: white; }
        .product-card:active { transform: scale(0.97); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script>
        const products = {!! json_encode($productsJson) !!};

        let selectedItems = [];
        let itemIdx = {{ (isset($order) ? $order->items->count() : 0) }};
        let activeCategory = '';

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

        document.querySelectorAll('.cat-filter').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.cat-filter').forEach(b => {
                    b.className = b.className.replace(' active', '');
                    if (!b.classList.contains('bg-brand-navy')) {
                        b.className = 'cat-filter shrink-0 px-3 py-1.5 text-xs font-semibold rounded-full bg-brand-warm text-brand-ink-muted hover:bg-brand-border';
                    }
                });
                this.className = 'cat-filter active shrink-0 px-3 py-1.5 text-xs font-semibold rounded-full bg-brand-navy text-white';
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
                <input type="number" name="items[${itemIdx}][quantity]" value="1" min="1" class="w-14 text-center text-xs border border-brand-border rounded-md py-0.5">
                <span class="text-xs text-brand-ink-muted tabular-nums">${prod.price_fmt}</span>
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-400 hover:text-red-600 text-lg leading-none ml-0.5">&times;</button>
                <input type="hidden" name="items[${itemIdx}][product_id]" value="${prod.id}">
                <input type="hidden" name="items[${itemIdx}][name]" value="${prod.name}">
                <input type="hidden" name="items[${itemIdx}][price]" value="${prod.price}">
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            itemIdx++;
            closeBottomSheet();
        }
    </script>
</x-admin-layout>
