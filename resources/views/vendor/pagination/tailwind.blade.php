@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4">
        <p class="text-xs sm:text-sm text-brand-ink-muted order-last sm:order-first">
            @if ($paginator->firstItem())
                Menampilkan <span class="font-semibold text-brand-ink">{{ $paginator->firstItem() }}</span>
                sampai <span class="font-semibold text-brand-ink">{{ $paginator->lastItem() }}</span>
                dari <span class="font-semibold text-brand-ink">{{ $paginator->total() }}</span> data
            @else
                {{ $paginator->count() }} data
            @endif
        </p>

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg text-brand-ink-faint bg-brand-warm border border-brand-border cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg text-brand-ink-muted bg-white border border-brand-border hover:border-brand-gold hover:text-brand-gold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 text-xs text-brand-ink-muted">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg text-xs font-bold bg-brand-gold text-white shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg text-xs font-medium text-brand-ink-muted bg-white border border-brand-border hover:border-brand-gold hover:text-brand-gold transition-colors" aria-label="Ke halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg text-brand-ink-muted bg-white border border-brand-border hover:border-brand-gold hover:text-brand-gold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg text-brand-ink-faint bg-brand-warm border border-brand-border cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
