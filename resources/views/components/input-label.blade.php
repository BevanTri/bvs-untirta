@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-brand-ink dark:text-zinc-100']) }}>
    {{ $value ?? $slot }}
</label>