@once
<style>
.product-sheet-overlay { transition: opacity 250ms ease; opacity: 0; pointer-events: none; z-index: 70; display: none; }
.product-sheet-overlay.show { opacity: 1; pointer-events: auto; display: block; }
.product-sheet-panel { position: fixed; bottom: 0; left: 0; right: 0; max-height: 90dvh; transform: translateY(100%); transition: transform 300ms cubic-bezier(0.32, 0.72, 0, 1); border-radius: 1.5rem 1.5rem 0 0; }
.product-sheet-panel.show { transform: translateY(0); }
@media (min-width: 768px) {
  .product-sheet-overlay.show { display: flex; align-items: center; justify-content: center; }
  .product-sheet-panel { position: relative !important; bottom: auto !important; left: auto !important; right: auto !important; transform: none !important; width: 100%; max-width: 480px; max-height: 80vh; margin: 0 16px; border-radius: 1.5rem; opacity: 0; transition: opacity 250ms ease; }
  .product-sheet-panel.show { opacity: 1; }
}
</style>
<script>
window.ProductSheet = {
  imageMarkup: function(p) {
    if (!p.image) return '<div class="w-full h-full flex flex-col items-center justify-center bg-brand-warm rounded-xl text-brand-ink-faint"><svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span class="text-xs font-medium">Foto tidak tersedia</span></div>';
    return '<img src="' + p.image + '" alt="' + (p.name||'Produk') + '" class="w-full h-full object-cover" onerror="this.onerror=null;this.parentElement.innerHTML=\'<div class=\\\'w-full h-full flex flex-col items-center justify-center bg-brand-warm rounded-xl text-brand-ink-faint\\\'><svg class=\\\'w-8 h-8 mb-1\\\' fill=\\\'none\\\' stroke=\\\'currentColor\\\' viewBox=\\\'0 0 24 24\\\'><path stroke-linecap=\\\'round\\\' stroke-linejoin=\\\'round\\\' stroke-width=\\\'1.5\\\' d=\\\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\\\'/><\\\/svg><span class=\\\'text-xs font-medium\\\'>Foto tidak tersedia<\\\/span><\\\/div>\'">';
  },
  addToCart: function() {
    document.getElementById('ps-form').submit();
  },
  buyNow: function() {
    var form = document.getElementById('ps-form');
    var fd = new FormData(form);
    fd.append('buy_now', '1');
    fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function(r) { return r.json(); })
      .then(function(d) { if (d.redirect) window.location.href = d.redirect; })
      .catch(function() { window.location.href = '/checkout'; });
  },
  open: function(id) {
    var p = window._productData && window._productData[id];
    if (!p) return;
    var sheet = document.getElementById('product-sheet');
    var overlay = document.getElementById('product-sheet-overlay');
    document.getElementById('ps-image').innerHTML = ProductSheet.imageMarkup(p);
    document.getElementById('ps-name').textContent = p.name;
    document.getElementById('ps-price').textContent = 'Rp' + Number(p.price).toLocaleString('id-ID');
    document.getElementById('ps-stock').textContent = p.stock != null ? 'Stok: ' + p.stock : 'Stok: Tidak terbatas';
    document.getElementById('ps-product-id').value = p.id;
    document.getElementById('ps-quantity').value = p.cartQty > 0 ? p.cartQty : 1;
    document.getElementById('ps-quantity').max = p.stock != null ? p.stock : 99999;
    var actionBtn = document.getElementById('ps-action-btn');
    if (p.cartQty > 0) {
      actionBtn.textContent = 'Update Keranjang';
      document.getElementById('ps-cart-hint').classList.remove('hidden');
      document.getElementById('ps-cart-hint-qty').textContent = p.cartQty;
    } else {
      actionBtn.textContent = 'Tambah ke Keranjang';
      document.getElementById('ps-cart-hint').classList.add('hidden');
    }
    window._psUnitPrice = p.price;
    ProductSheet.updateSubtotal();
    overlay.style.display = ''; if (overlay.offsetHeight) {} overlay.classList.add('show');
    sheet.classList.add('show');
    document.body.dataset.scrollY = window.scrollY;
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.width = '100%';
    document.body.style.top = '-' + window.scrollY + 'px';
  },
  close: function() {
    var sy = parseFloat(document.body.dataset.scrollY || '0');
    document.getElementById('product-sheet-overlay').classList.remove('show');
    document.getElementById('product-sheet').classList.remove('show');
    document.body.style.overflow = '';
    document.body.style.position = '';
    document.body.style.width = '';
    document.body.style.top = '';
    window.scrollTo(0, sy);
    delete document.body.dataset.scrollY;
    var ov = document.getElementById('product-sheet-overlay');
    setTimeout(function(){ if(!ov.classList.contains('show')) ov.style.display = 'none'; }, 300);
  },
  qtyChange: function(delta) {
    var inp = document.getElementById('ps-quantity');
    var v = parseInt(inp.value) || 1;
    var max = parseInt(inp.max) || 99999;
    v = Math.max(1, Math.min(max, v + delta));
    inp.value = v;
    ProductSheet.updateSubtotal();
  },
  updateSubtotal: function() {
    var qty = parseInt(document.getElementById('ps-quantity').value) || 1;
    var total = (window._psUnitPrice || 0) * qty;
    document.getElementById('ps-subtotal').textContent = 'Rp' + total.toLocaleString('id-ID');
  }
};
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('product-sheet-overlay')) ProductSheet.close();
});
</script>
@endonce

<div id="product-sheet-overlay" class="product-sheet-overlay fixed inset-0 bg-black/50" onclick="ProductSheet.close()">
  <div id="product-sheet" class="product-sheet-panel bg-white shadow-2xl flex flex-col" onclick="event.stopPropagation()">
    <div class="shrink-0 px-4 pt-3 pb-2 border-b border-brand-border/50">
      <div class="flex justify-center mb-2 md:hidden"><div class="w-10 h-1 rounded-full bg-brand-border"></div></div>
      <div class="flex items-center justify-between">
        <h3 class="font-display font-semibold text-brand-ink">Detail Produk</h3>
        <button type="button" onclick="ProductSheet.close()" class="text-brand-ink-muted hover:text-brand-ink p-1 text-xl leading-none">&times;</button>
      </div>
    </div>
    <div class="flex-1 overflow-y-auto p-4" style="overscroll-behavior:contain">
      <form method="POST" action="{{ route('cart.add') }}" id="ps-form">
        @csrf
        <input type="hidden" name="type" value="product" id="ps-type">
        <input type="hidden" name="id" value="" id="ps-product-id">
        <div class="flex gap-4 mb-4">
          <div id="ps-image" class="w-24 h-24 shrink-0 rounded-xl overflow-hidden bg-brand-warm flex items-center justify-center"></div>
          <div class="flex-1 min-w-0">
            <p id="ps-name" class="font-medium text-brand-ink text-sm"></p>
            <p id="ps-price" class="font-bold text-brand-gold text-lg font-mono mt-1"></p>
            <p id="ps-stock" class="text-xs text-brand-ink-muted font-mono mt-0.5"></p>
          </div>
        </div>

        <div id="ps-cart-hint" class="hidden bg-brand-warm rounded-xl p-3 mb-4 text-sm text-brand-ink-muted">
          Produk ini sudah ada di keranjang. Jumlah saat ini: <strong id="ps-cart-hint-qty" class="text-brand-ink"></strong>
        </div>

        <div class="mb-4">
          <p class="text-xs text-brand-ink-faint uppercase tracking-wider font-semibold mb-2">Jumlah</p>
          <div class="flex items-center gap-3">
            <button type="button" onclick="ProductSheet.qtyChange(-1)" class="w-10 h-10 border-2 border-brand-border rounded-xl flex items-center justify-center text-lg font-bold text-brand-ink-muted hover:border-brand-gold hover:text-brand-gold transition-colors">&minus;</button>
            <input type="number" id="ps-quantity" name="quantity" value="1" min="1" class="w-16 input-field text-center font-mono text-base font-bold" oninput="ProductSheet.updateSubtotal()">
            <button type="button" onclick="ProductSheet.qtyChange(1)" class="w-10 h-10 border-2 border-brand-border rounded-xl flex items-center justify-center text-lg font-bold text-brand-ink-muted hover:border-brand-gold hover:text-brand-gold transition-colors">+</button>
          </div>
        </div>

        <div class="border-t border-brand-border pt-4 mb-4">
          <div class="flex items-center justify-between">
            <p class="text-sm text-brand-ink-muted">Subtotal</p>
            <p id="ps-subtotal" class="font-display font-bold text-xl text-brand-gold font-mono"></p>
          </div>
        </div>

        <div class="flex flex-col gap-2">
          <button type="button" id="ps-action-btn" class="btn-primary w-full justify-center" onclick="ProductSheet.addToCart()">Tambah ke Keranjang</button>
          <button type="button" id="ps-buy-btn" class="btn-outline w-full justify-center border-brand-gold text-brand-gold hover:bg-brand-gold hover:text-white" onclick="ProductSheet.buyNow()">Beli Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>
