@if($value->isNotEmpty() && $value->__toString() !== "0")
    <div {{ $attributes->class('inline-block bg-slate-700/45 px-2 py-1 rounded-md') }}>
        @if (isset($name))
            <div {{ $name->attributes->class('text-slate-600 uppercase text-sm font-bold') }}>{{$name}}:</div>
        @endif
        <div {{ $value->attributes->class('text-white text-right md:text-center ') }}>{{ $value }}</div>
    </div>
@endif
