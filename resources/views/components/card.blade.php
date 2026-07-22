@props([
    'padding' => 'p-5',
    'class' => '',
])

<div {{ $attributes->merge(['class' => "card $padding $class"]) }}>
    {{ $slot }}
</div>
