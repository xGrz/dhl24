@props(['message' => 'Not found'])

<div class="py-5 px-2 flex flex-col items-center text-slate-500">
    <x-payu::icons.none class="w-10 h-10 text-slate-600"/>
    <div class="py-2">{{ $message }}</div>
</div>
