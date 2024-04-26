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
                class="relative w-10 h-5 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-800
                rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white
                after:content-[''] after:absolute after:top-[0] after:start-[0] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5
                after:h-5 after:transition-all peer-checked:bg-blue-600">
            </div>
            <span
                class="ms-3 text-sm font-medium uppercase font-bold
                    @if($value)
                        text-green-500
                    @elseif($shouldBeNonStandard)
                        text-orange-500
                    @else
                        text-slate-500
                  @endif"
            >
                {{$label}}
            </span>
        </label>

    </div>
</div>
