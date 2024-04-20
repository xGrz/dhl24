<?php

$commonStyles = 'inline-block px-[.4rem] py-[.05rem] leading-5 ';

$classes = match ($status->getColor()) {
    'gray' => 'text-gray-50 border-gray-600 bg-gray-600',
    'danger' => 'text-red-50 border-red-600 bg-red-500',
    'warning' => 'text-amber-50 border-amber-700 bg-amber-600',
    'success' => 'text-green-50 border-green-900 bg-green-500',
    'primary', 'info' => 'text-sky-50 border-sky-800 bg-sky-700',
    default => 'text-red-500'
};

if (!isset($withoutBorders)) $classes .= ' rounded-md border ';

?>

<span class="{{$commonStyles}} {{ $classes }} @if(isset($class)){{ $class }}@endif">
    {{ $status->getLabel() }}
</span>
