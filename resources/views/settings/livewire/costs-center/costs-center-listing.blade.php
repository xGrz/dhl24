<div>
    <x-p-paper class="bg-slate-800 mb-4">
        <x-slot:title>Cost centers</x-slot:title>
        <x-slot:actions>
            <x-p-button
                color="success"
                wire:click="$dispatch('openModal', {component: 'cost-center-create'})"
            >
                Add
            </x-p-button>
        </x-slot:actions>
        <div class="p-2">
            <x-p-table>
                <x-p-thead>
                    <x-p-tr>
                        <x-p-th left>Name</x-p-th>
                        <x-p-th right>Options</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <x-p-tbody>
                    @foreach($costCenters as $center)
                        <x-p-tr wire:key="costCenter_{{$center->id}}">
                            <x-p-td>
                                @if($center->is_default)
                                    <strong class="text-slate-300">{{ $center->name }}</strong>
                                @else
                                    {{ $center->name }}
                                @endif
                            </x-p-td>
                            <x-p-td right>
                                @if($center->is_default)
                                    <button type="button" class="text-yellow-500" disabled>
                                        <x-p::icons.star-full class="w-5 h-5"/>
                                    </button>
                                @else
                                    <button href="#" wire:click.prevent="setAsDefault({{$center->id}})"
                                            class="text-slate-500 hover:text-yellow-500 transition-all">
                                        <x-p::icons.star class="w-5 h-5"/>
                                    </button>
                                @endif
                                <x-p-button
                                    type="button"
                                    size="small"
                                    wire:click="$dispatch('openModal', {component: 'cost-center-edit', arguments: { costCenter: {{$center}} } })"
                                >
                                    Edit
                                </x-p-button>

                                <x-p-button
                                    color="danger"
                                    size="small"
                                    wire:click="$dispatch('openModal', {component: 'cost-center-delete', arguments: { costCenter: {{$center}} } })"
                                >
                                    Delete
                                </x-p-button>
                            </x-p-td>
                        </x-p-tr>
                    @endforeach
                </x-p-tbody>
            </x-p-table>
        </div>
    </x-p-paper>
</div>
