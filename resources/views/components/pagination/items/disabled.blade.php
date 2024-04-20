<span
    aria-disabled="true"
    aria-label="{{ __('pagination.previous') }}"
>
    <span
        aria-hidden="true"
        {{ $attributes->merge(['class' => 'relative inline-flex transition ease-in-out duration-150 bg-transparent text-gray-600 select-none text-medium leading-5'])}}
    >
        {{ $slot }}
    </span>
</span>
