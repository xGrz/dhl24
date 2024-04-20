@props(['routeName'=> '#', 'label' => ''])

<?php
$isActive = request()->routeIs(str($routeName)->replaceEnd('index', '')->append('*'));
?>

<a href="{{route($routeName)}}"
   class="inline-flex flex-row md:flex-col items-center px-2 md:px-4 py-1 @if($isActive) bg-slate-600 text-slate-100 @else hover:bg-slate-700 hover:text-slate-300 @endif self-center grow-1">
    {{ $slot }}
    {{ $label }}
</a>
