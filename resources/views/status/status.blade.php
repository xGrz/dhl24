<div class="container mx-auto px-4">

    @if (session()->has('success'))
        <div class="py-2 px-2 bg-green-700 text-white font-bold rounded-md shadow-xl my-1">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="py-2 px-2 bg-red-800 text-white font-bold rounded-md shadow-xl my-1">
            {{ session('error') }}
        </div>
    @endif

</div>


