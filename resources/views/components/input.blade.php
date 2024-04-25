@props(['type' => 'text', 'value' => '', 'label' => '', 'model', 'numeric' => false, 'lazy'=> false])

<label class="inline-block w-full text-gray-700 font-semibold">
    @if ($label)
        <small class="@error($model) text-rose-600 @else text-gray-500 @enderror">{{$label}}</small>
    @endif
    <input
        @if($lazy)
            wire:model.lazy="{{$model}}"
        @else
            wire:model.live.debounce.500ms="{{$model}}"
        @endif
        @if($numeric) inputmode="numeric" @endif
        value="{{$model}}"
        class="
        w-full inline-block grow shrink border bg-slate-300 focus:bg-gray-200 text-slate-600 focus:text-slate-700
        rounded-md focus:outline-none py-2 px-2 disabled:bg-gray-100 disabled:text-gray-400
        @error($model) border-orange-500 bg-orange-100 @else border-gray-300 @enderror,
        "
    />
    @error($model)
    <small class="text-red-600 text-sm mb-1 leading-none">{{ $message }}</small>
    @enderror

</label>
