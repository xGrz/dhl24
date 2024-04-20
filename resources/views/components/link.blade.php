@props([
    'class' => '',
    'target' => false,
    'href' => '#',
    'color' => 'primary'
])

<?php
$colorDefinition = match ($color) {
    'danger' => 'text-red-700 hover:text-red-900',
    'gray' => 'text-gray-400 hover:text-gray-500',
    'info' => 'text-sky-700 hover:text-sky-900',
    'success' => 'text-green-700 hover:text-green-900',
    'warning' => 'text-amber-600 hover:text-amber-800',
    default => 'text-sky-500 hover:text-sky-600',
};
?>

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => $colorDefinition])}}
    @if ($target) target="{{$target}}" @endif
>
    {{ $slot }}
</a>
