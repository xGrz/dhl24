<section>
    <input wire:model.live.debounce.200ms="packages.items.{{$id}}.quantity"/>
    @error("packages.items.$id.quantity")<span class="text-red-500">Błąd</span> @else no-err @enderror
</section>
