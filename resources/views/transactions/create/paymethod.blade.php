<div class="p-2">
    <ul class="grid grid-cols-2 gap-2">
        @foreach($methods as $method)
            <li class="flex">
                <input
                    type="radio"
                    id="method_{{$method->code}}"
                    value="{{$method->code}}"
                    name="method"
                    class="opacity-0 absolute peer"
                />
                <label
                    for="method_{{$method->code}}"
                    class="flex border border-gray-700 rounded-md peer-checked:border-slate-400 peer-checked:bg-slate-700 peer-focus:outlined cursor-pointer p-2 grow"
                >
                    <img src="{{$method->image}}" alt="{{$method->name}}"
                         class="bg-white rounded-md object-scale-down max-w-12 aspect-auto p-1"/>
                    <div class="self-center m-1 text-sm">{{$method->name}}</div>
                </label>
            </li>
        @endforeach
    </ul>
</div>
