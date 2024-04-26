<div class="flex flex-row text-center items-center">
    <div class="">
        <label class="inline-flex items-center cursor-pointer mx-2">
            <input
                type="checkbox"
                class="sr-only peer"
                wire:model.live.debounce.150ms="{{$model}}"
                @if($model) checked @endif
            >
            <div
                class="relative w-12 h-5 bg-slate-700 rounded-full transition-all ease-in-out duration-300 pl-0 peer-checked:pl-7 peer-focus:ring-4 peer-focus:ring-blue-800">
                <div
                    class="w-5 h-5 @if($shouldBeNonStandard && !$value) bg-orange-500 @else bg-white @endif rounded-full ease-in-out duration-100 transition-all"></div>
            </div>
            <span
                class="ms-3 text-sm font-medium uppercase font-bold @if($value)text-green-500 @elseif($shouldBeNonStandard) text-orange-500 @else text-slate-500 @endif">
                {{$label}}
            </span>
        </label>

    </div>
</div>
