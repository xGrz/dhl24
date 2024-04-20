@props(['title' => 'Title', 'watchErrors' => []])

<?php

$shouldBeOpen = false;
if ($watchErrors) {
    foreach ($watchErrors as $errorName) {
        if ($errors->has($errorName)) $shouldBeOpen = true;
    }
}
$shouldBeOpen = $shouldBeOpen ? 'true' : 'false';

?>

<div x-data="{ modelOpen: {{$shouldBeOpen}} }">

    {{ $trigger }}

    <div x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
            <x-payu::modal.backdrop/>
            <x-payu::modal.container>
                <div class="flex items-center justify-between space-x-4">
                    <h1 class="text-xl font-medium text-gray-800 ">{{$title}}</h1>
                    <button @click="modelOpen = false"
                            class="text-gray-600 focus:outline-none hover:text-red-700 hover:bg-red-200 p-1 rounded-full">
                        <x-payu::icons.close/>
                    </button>
                </div>

                {{ $modalContent }}
            </x-payu::modal.container>
        </div>
    </div>
</div>

