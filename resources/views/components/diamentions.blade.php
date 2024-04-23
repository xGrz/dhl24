<div class="flex flex-col w-20">
    <div
        class="items-center text-center text-xs uppercase bg-slate-400 text-slate-600 font-bold rounded-t-md">
        {{ $label }}
    </div>
    <input
        type="number"
        class="block px-3 pb-[2px] outline-none focus:outline-none text-slate-900 bg-slate-300 focus:bg-gray-100 font-bold text-xl text-center rounded-b-md [&::-webkit-inner-spin-button]:appearance-none [appearance:textfield]"
        value="{{$value}}"
        wire:model.live.debounce.500ms="{{$model}}"
    >
</div>
