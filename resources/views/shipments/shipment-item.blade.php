<section>
    <input wire:model.live.debounce.200ms="items.{{$key}}.quantity"/>
    @error("items.{{$key}}.quantity")<span class="text-red-500">Błąd</span> @enderror
    items.{{$key}}.quantity
</section>
