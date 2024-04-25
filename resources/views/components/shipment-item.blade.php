<section>
    <input wire:model.live.debounce.200ms="items.{{$id}}.quantity"/>
    @error("items.$id.quantity")<span class="text-red-500">Błąd</span> @else no-err @enderror
</section>
