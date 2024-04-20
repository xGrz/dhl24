<a href="{{$href}}"
   rel="prev"
   {{ $attributes->merge(['class' => 'relative inline-flex transition ease-in-out duration-150 hover:bg-slate-600 bg-slate-700 text-slate-400 hover:text-slate-300 select-none text-medium leading-5']) }}
   aria-label="{{ __('pagination.previous') }}"
>
    {{ $slot }}
</a>
