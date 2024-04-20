@if ($paginator->hasPages())
    <div>
        <x-payu::pagination.types.mobile :$paginator />
        <x-payu::pagination.types.desktop :$paginator :$elements />
    </div>
@endif
