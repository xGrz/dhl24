<div class="flex flex-col w-full">
    <div
        class="items-center text-center text-xs uppercase @error($model) bg-orange-600 text-orange-50 @else bg-slate-400 text-slate-600 @enderror font-bold rounded-t-md">
        {{ $label }}
    </div>
    <input
        type="text"
        inputmode="numeric"
        class="block px-3 pb-[2px] outline-none focus:outline-none font-bold text-xl text-center !rounded-0 !rounded-b-md [&::-webkit-inner-spin-button]:appearance-none [appearance:textfield]
            @error($model) text-orange-700 bg-orange-300 focus:bg-orange-100 @else text-slate-900 bg-slate-300 focus:bg-gray-100 @enderror"
        value="{{$value}}"
        wire:model.live.debounce.500ms="{{$model}}"
    >
</div>
