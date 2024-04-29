<x-p::paper class="bg-slate-800">
    <x-p::paper-title title="Remove confirmation required"/>
    <div class="p-2">
        Do you want to delete <strong class="text-white">{{ $form->name }}</strong> cost center?
    </div>
    <div class="p-2 text-right">
        <x-p::button wire:click="cancel" type="button" color="success" size="">Cancel</x-p::button>
        <x-p::button wire:click="deleteConfirmed" type="button" color="danger" size="">Yes, I want to delete it.</x-p::button>
    </div>
</x-p::paper>
