@props([
    'class' => '',
    'target' => false,
    'href' => '#',
    'size'=> 'normal',
    'color' => 'primary'
])

<?php
$px = match ($size) {
    'small' => 'px-[.5rem]',
    'large' => 'px-[.7rem]',
    default => 'px-2',
};
$py = match ($size) {
    'small' => 'py-[.05rem]',
    'large' => 'py-[.5rem]',
    default => 'py-[.2rem]',
};
$colorDefinition = match ($color) {
    'danger' => 'bg-red-700 hover:bg-red-800 text-white',
    'gray' => 'bg-gray-400 hover:bg-gray-500 text-white',
    'info' => 'bg-sky-500 hover:bg-sky-600 text-white',
    'success' => 'bg-green-700 hover:bg-green-800 text-white',
    'warning' => 'bg-amber-600 hover:bg-amber-700 text-white',
    default => 'bg-sky-700 hover:bg-sky-800 text-white',
};

?>

<a
    {{ $attributes->merge([
        'class' => join(' ', [$px, $py, $colorDefinition, 'inline-block mr-[.2rem] rounded shadow leading-normal']),
        'href' => $href
    ])}}
        @if ($target) target="{{$target}}" @endif
>
    {{ $slot }}
</a>
