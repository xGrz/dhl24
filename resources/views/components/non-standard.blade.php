<div class="flex flex-col w-20 text-center">
    <div class="text-xs uppercase text-slate-600 font-bold rounded-t-md">
        <small>{{$label}}</small>
    </div>

    <div class="">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" value="1" class="sr-only peer" @if($value) checked @endif>
            <div
                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800
                rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white
                after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5
                after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
            </div>
        </label>
    </div>

</div>
