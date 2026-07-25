@props(['id' => '', 'name' => ''])

<div x-data="{ show: false }" class="relative">
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        :type="show ? 'text' : 'password'"
        {{ $attributes->merge(['class' => 'block mt-1 w-full border border-brand-border bg-white px-4 py-3 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 text-brand-ink placeholder-brand-ink-faint rounded-xl transition-all duration-200', 'style' => 'padding-right: 3rem; min-height: 48px']) }}
    />
    <button type="button"
        @click="show = !show"
        class="absolute right-3 bottom-0 top-0 my-auto flex items-center text-zinc-400 hover:text-zinc-600 transition-colors cursor-pointer focus:outline-none"
        tabindex="-1"
        :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'"
    >
        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
        </svg>
    </button>
</div>
