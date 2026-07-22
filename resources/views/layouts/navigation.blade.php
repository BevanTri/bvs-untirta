    <nav class="bg-white/90 backdrop-blur-md border-b border-brand-border/80 sticky top-0 z-40 supports-backdrop-blur:bg-white/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                    <img src="{{ asset('images/logo-untirta.webp') }}" alt="Untirta" class="h-7 w-auto">
                    <span class="font-display font-bold text-brand-navy text-lg tracking-[0.05em] uppercase hidden xs:inline">BVS Untirta</span>
                </a>
                <div class="hidden sm:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('home') ? 'text-brand-blue' : 'text-brand-ink-muted hover:text-brand-blue' }}">Beranda</a>
                    <a href="{{ route('products') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('products*') ? 'text-brand-blue' : 'text-brand-ink-muted hover:text-brand-blue' }}">Produk</a>
                    <a href="{{ route('services') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('services*') ? 'text-brand-blue' : 'text-brand-ink-muted hover:text-brand-blue' }}">Service</a>
                    @if(Auth::user()?->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium transition-colors tracking-wide uppercase font-display {{ request()->routeIs('admin.*') ? 'text-brand-gold' : 'text-brand-ink-muted hover:text-brand-gold' }}">Admin</a>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('orders.history') }}" id="nav-orders-link" class="text-brand-ink-muted hover:text-brand-blue text-sm transition-colors uppercase font-display tracking-wide touch-target flex items-center">Pesanan</a>
                    <a href="{{ route('cart.index') }}" class="hidden sm:inline-flex text-brand-ink-muted hover:text-brand-blue transition-colors touch-target items-center justify-center" title="Keranjang">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </a>
                    <div class="relative">
                        <button onclick="toggleUserMenu()" type="button" class="flex items-center text-brand-ink-muted hover:text-brand-navy transition-colors cursor-pointer touch-target">
                            <span class="w-7 h-7 rounded-full bg-brand-blue/10 flex items-center justify-center text-xs font-bold text-brand-blue shrink-0">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-level-4 border border-brand-border z-[70] overflow-hidden">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-brand-ink hover:bg-brand-warm transition-colors">Profile</a>
                            <div class="border-t border-brand-border"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-brand-ink hover:bg-brand-warm transition-colors">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <script>
    function toggleUserMenu() {
        document.getElementById('user-menu').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        var m = document.getElementById('user-menu');
        var btn = document.querySelector('[onclick="toggleUserMenu()"]');
        if (m && !m.classList.contains('hidden') && !m.contains(e.target) && !btn.contains(e.target)) {
            m.classList.add('hidden');
        }
    });
    </script>
