@if ($paginator->hasPages())
    <nav class="hidden sm:flex-1 sm:flex items-center justify-center">
        <div class="flex items-center justify-between">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <x-payu::pagination.items.disabled class="p-1 mr-[1px]">
                    <x-payu::icons.left-arrow />
                </x-payu::pagination.items.disabled>
            @else
                <x-payu::pagination.items.active href="{{ $paginator->previousPageUrl() }}" class="p-1 mr-[1px] rounded-l-md">
                    <x-payu::icons.left-arrow />
                </x-payu::pagination.items.active>
            @endif


            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <x-payu::pagination.items.disabled class="px-2 py-1">
                        {{ $element }}
                    </x-payu::pagination.items.disabled>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <x-payu::pagination.items.disabled class="px-2 py-1 mr-[1px] !text-slate-300 !bg-slate-500">
                                {{ $page }}
                            </x-payu::pagination.items.disabled>
                        @else
                            <x-payu::pagination.items.active href="{{$url}}" class="px-2 py-1 mr-[1px]">
                                {{ $page }}
                            </x-payu::pagination.items.active>
                        @endif
                    @endforeach
                @endif
            @endforeach


            @if ($paginator->hasMorePages())
                <x-payu::pagination.items.active href="{{ $paginator->nextPageUrl() }}" class="p-1 mr-[1px] rounded-r-md">
                    <x-payu::icons.right-arrow />
                </x-payu::pagination.items.active>
            @else
                <x-payu::pagination.items.disabled class="p-1 mr-[1px]">
                    <x-payu::icons.right-arrow />
                </x-payu::pagination.items.disabled>
            @endif


        </div>

    </nav>

@endif
