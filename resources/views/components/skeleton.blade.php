@props([
    'count' => 1,
    'class' => 'h-4 w-full',
    'width' => null,
    'height' => null,
])

@for($i = 0; $i < $count; $i++)
<div class="skeleton {{ $class }}" @if($width) style="width:{{ $width }}"@endif @if($height) style="height:{{ $height }}"@endif></div>
@endfor
