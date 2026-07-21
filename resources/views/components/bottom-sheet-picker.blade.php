@props([
    'name' => '',
    'label' => '',
    'id' => null,
    'options' => [],
    'selected' => '',
    'placeholder' => 'Pilih...',
    'required' => false,
    'onselect' => '',
])

@php
    $pickerId = $id ?? 'picker-' . preg_replace('/[^a-z0-9_-]/i', '', $name) . '-' . \Illuminate\Support\Str::random(4);
    $selectedOption = collect($options)->firstWhere('value', $selected);
    $selectedLabel = $selectedOption['label'] ?? '';
@endphp

@once
<style>
.picker-overlay { transition: opacity 250ms ease; }
.picker-sheet { transition: transform 300ms cubic-bezier(0.32, 0.72, 0, 1); }
.picker-overlay.show { display: block; opacity: 1; }
.picker-overlay:not(.show) { opacity: 0; }
.picker-sheet.show { transform: translateY(0); }
.picker-option:active { background: #F3F4F6; }
.picker-search::placeholder { color: #9CA3AF; }
</style>
<script>
window.Picker = window.Picker || (function() {
    function open(id) {
        document.getElementById('picker-overlay-'+id).classList.add('show');
        document.getElementById('picker-sheet-'+id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function close(id) {
        document.getElementById('picker-overlay-'+id).classList.remove('show');
        document.getElementById('picker-sheet-'+id).classList.remove('show');
        document.body.style.overflow = '';
    }
    function select(id, btn) {
        var value = btn.dataset.value;
        var label = btn.dataset.label;
        var container = document.querySelector('[data-picker="'+id+'"]');
        container.querySelector('.picker-value').textContent = label;
        container.querySelector('.picker-value').classList.remove('text-brand-ink-faint');
        var hidden = container.querySelector('.picker-hidden');
        hidden.value = value;
        Array.from(btn.attributes).forEach(function(a) {
            if (a.name.startsWith('data-') && a.name !== 'data-value' && a.name !== 'data-label') {
                hidden.setAttribute(a.name, a.value);
            }
        });
        container.querySelectorAll('.picker-option').forEach(function(o) {
            o.classList.toggle('selected', o.dataset.value === value);
            o.querySelector('.picker-check').classList.toggle('hidden', o.dataset.value !== value);
        });
        var cb = container.dataset.onselect;
        if (cb && window[cb]) window[cb]();
        close(id);
    }
    function filter(id, query) {
        var q = query.toLowerCase();
        document.querySelectorAll('[data-picker="'+id+'"] .picker-option').forEach(function(o) {
            o.style.display = o.dataset.label.toLowerCase().includes(q) ? '' : 'none';
        });
    }
    document.addEventListener('click', function(e) {
        var overlay = e.target.closest('.picker-overlay');
        if (overlay) close(overlay.dataset.picker);
    });
    // init: copy data attrs from pre-selected options to hidden inputs
    document.querySelectorAll('.picker-option.selected').forEach(function(btn) {
        var cont = btn.closest('[data-picker]');
        if (!cont) return;
        var hidden = cont.querySelector('.picker-hidden');
        Array.from(btn.attributes).forEach(function(a) {
            if (a.name.startsWith('data-') && a.name !== 'data-value' && a.name !== 'data-label') {
                hidden.setAttribute(a.name, a.value);
            }
        });
    });
    // form validation for required pickers
    document.addEventListener('submit', function(e) {
        var form = e.target;
        form.querySelectorAll('.bottom-sheet-picker .picker-hidden[required]').forEach(function(h) {
            if (!h.value) {
                e.preventDefault();
                h.closest('.bottom-sheet-picker').querySelector('.picker-trigger').focus();
                h.closest('.bottom-sheet-picker').querySelector('.picker-trigger').style.borderColor = '#EF4444';
                setTimeout(function() {
                    h.closest('.bottom-sheet-picker').querySelector('.picker-trigger').style.borderColor = '';
                }, 2000);
            }
        });
    });
    return { open: open, close: close, select: select, filter: filter };
})();
</script>
@endonce

<div class="bottom-sheet-picker" data-picker="{{ $pickerId }}" data-onselect="{{ $onselect }}">
    @if($label)
    <label class="text-xs font-semibold text-brand-steel uppercase tracking-widest mb-1 block">{{ $label }}</label>
    @endif

    <button type="button" class="picker-trigger input-field w-full flex items-center justify-between gap-2" onclick="Picker.open('{{ $pickerId }}')">
        <span class="picker-value truncate {{ $selectedLabel ? '' : 'text-brand-ink-faint' }}">{{ $selectedLabel ?: $placeholder }}</span>
        <svg class="w-4 h-4 shrink-0 text-brand-ink-faint" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <input type="hidden" name="{{ $name }}" value="{{ $selected }}" class="picker-hidden" {{ $required ? 'required' : '' }}>

    <div id="picker-overlay-{{ $pickerId }}" class="picker-overlay fixed inset-0 bg-black/50 z-50 hidden" data-picker="{{ $pickerId }}"></div>

    <div id="picker-sheet-{{ $pickerId }}" class="picker-sheet fixed bottom-0 left-0 right-0 z-50 bg-white rounded-t-2xl shadow-2xl translate-y-full max-h-[75dvh] flex flex-col" data-picker="{{ $pickerId }}">
        <div class="shrink-0 px-4 pt-3 pb-2 border-b border-brand-border/50">
            <div class="flex justify-center mb-2">
                <div class="w-10 h-1 rounded-full bg-brand-border"></div>
            </div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-display font-semibold text-brand-ink">{{ $label ?: $placeholder }}</h3>
                <button type="button" onclick="Picker.close('{{ $pickerId }}')" class="text-brand-ink-muted hover:text-brand-ink p-1 text-xl leading-none">&times;</button>
            </div>
            @if(count($options) > 5)
            <div class="relative mt-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-ink-faint pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" class="picker-search w-full pl-9 pr-3 py-2 text-sm border border-brand-border rounded-lg focus:border-brand-gold focus:ring-1 focus:ring-brand-gold/30 outline-none" placeholder="Cari..." oninput="Picker.filter('{{ $pickerId }}', this.value)">
            </div>
            @endif
        </div>
        <div class="flex-1 overflow-y-auto px-2 pb-[calc(80px+env(safe-area-inset-bottom,0px))]">
            @foreach($options as $opt)
            <button type="button" class="picker-option flex items-center justify-between w-full px-3 py-3 text-sm text-left rounded-xl hover:bg-brand-warm transition-colors duration-150 {{ $selected == $opt['value'] ? 'selected bg-brand-warm' : '' }}"
                data-value="{{ $opt['value'] }}" data-label="{{ $opt['label'] }}"
                @foreach($opt as $k => $v) @if(!in_array($k, ['value','label'])) {{ $k }}="{{ $v }}" @endif @endforeach
                onclick="Picker.select('{{ $pickerId }}', this)">
                <span class="font-medium text-brand-ink">{{ $opt['label'] }}</span>
                <svg class="picker-check w-5 h-5 text-brand-gold {{ $selected == $opt['value'] ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </button>
            @endforeach
        </div>
    </div>
</div>
