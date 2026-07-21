<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-brand-border flex items-center justify-around h-16 safe-area-bottom shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
    @php
        $navItems = [
            ['route' => 'home', 'label' => 'Beranda', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'products', 'label' => 'Produk', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['route' => 'services', 'label' => 'Service', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ];
    @endphp
    @foreach($navItems as $item)
    <a href="{{ route($item['route']) }}" class="flex flex-col items-center justify-center gap-0.5 min-w-0 flex-1 h-full relative {{ request()->routeIs($item['route'] . '*') ? 'text-brand-blue' : 'text-brand-ink-muted' }} transition-colors duration-150">
        @if(request()->routeIs($item['route'] . '*'))
        <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-b"></span>
        @endif
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
        <span class="text-[10px] font-display font-semibold uppercase tracking-wider leading-none truncate max-w-full">{{ $item['label'] }}</span>
    </a>
    @endforeach
    @auth
    <a href="{{ route('cart.index') }}" class="flex flex-col items-center justify-center gap-0.5 min-w-0 flex-1 h-full relative {{ request()->routeIs('cart*') ? 'text-brand-blue' : 'text-brand-ink-muted' }} transition-colors duration-150">
        @if(request()->routeIs('cart*'))
        <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-b"></span>
        @endif
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
        <span class="text-[10px] font-display font-semibold uppercase tracking-wider leading-none truncate max-w-full">Keranjang</span>
    </a>
    @if(Auth::user()->is_admin ?? false)
    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center gap-0.5 min-w-0 flex-1 h-full relative {{ request()->routeIs('admin.*') ? 'text-brand-gold' : 'text-brand-ink-muted' }} transition-colors duration-150">
        @if(request()->routeIs('admin.*'))
        <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-gold rounded-b"></span>
        @endif
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <span class="text-[10px] font-display font-semibold uppercase tracking-wider leading-none truncate max-w-full">Admin</span>
    </a>
    @endif
    @else
    <a href="{{ route('login') }}" class="flex flex-col items-center justify-center gap-0.5 min-w-0 flex-1 h-full text-brand-ink-muted hover:text-brand-blue transition-colors duration-150">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
        <span class="text-[10px] font-display font-semibold uppercase tracking-wider leading-none truncate max-w-full">Login</span>
    </a>
    @endauth
</nav>
