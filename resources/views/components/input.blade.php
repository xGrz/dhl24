@props(['type' => 'text','name' => '','value' => '','step' => 1,'min' => null,'max' => null,'label' => '&nbsp;'])

<?php
$hasError = $errors->has($name);
$isNumeric = $type == 'number';
?>

<label class="inline-block w-full text-gray-700 font-semibold">
    @if ($label)
        <small class="@if($hasError) text-rose-600 @else text-gray-500 @endif">{{$label}}</small>
    @endif
    <input
            {{ $attributes->merge([
                'type' => $type,
                'name' => $name,
                'value' => old($name, $value),
                'step' => $isNumeric ? ($step ?? null) : null,
                'min' => $isNumeric ? ($min ?? null) : null,
                'max' => $isNumeric ? ($max ?? null) : null,
            ]) }}

            @class([
                'w-full inline-block grow shrink border bg-slate-300 focus:bg-gray-200 text-slate-600 focus:text-slate-700 rounded-md focus:outline-none py-2 px-4 disabled:bg-gray-100 disabled:text-gray-400',
                $hasError ? 'border-rose-500' : 'border-gray-300',
                '[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none' => $isNumeric,
                'text-right' => $isNumeric,
            ])

            {{ $attributes->class([
                'w-full inline-block grow shrink border bg-slate-300 focus:bg-gray-200 text-slate-600 focus:text-slate-700 rounded-md focus:outline-none text-right py-2 px-4 disabled:bg-gray-100 disabled:text-gray-400',
                'border-gray-300' => !$hasError,
                'border-rose-500' => $hasError,
                'text-right' => $isNumeric,
                '[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none' => $isNumeric,
            ])->merge([
            ])
            }}
    />
    @error($name)
    <small class="text-red-600 text-sm mb-1 leading-none">{{ $message }}</small>
    @enderror

</label>
