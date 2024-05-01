<div>
    <x-p::paper class="bg-slate-800 mb-4">
        <x-p::paper-title title="Cost centers">
            <x-p::button
                color="success"
                wire:click="$dispatch('openModal', {component: 'cost-center-create'})"
            >
                Add
            </x-p::button>
        </x-p::paper-title>
        <div class="p-2">
            <x-p::table>
                <x-p::table.head>
                    <x-p::table.th>Name</x-p::table.th>
                    <x-p::table.th class="text-right">Options</x-p::table.th>
                </x-p::table.head>
                <x-p::table.body>
                    @foreach($costCenters as $center)
                        <x-p::table.row wire:key="costCenter_{{$center->id}}">
                            <x-p::table.cell>
                                @if($center->is_default)
                                    <strong class="text-slate-300">{{ $center->name }}</strong>
                                @else
                                    {{ $center->name }}
                                @endif
                            </x-p::table.cell>
                            <x-p::table.cell class="text-right">
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
                                <x-p::button
                                    type="button"
                                    size="small"
                                    wire:click="$dispatch('openModal', {component: 'cost-center-edit', arguments: { costCenter: {{$center}} } })"
                                >
                                    Edit
                                </x-p::button>

                                <x-p::button
                                    color="danger"
                                    size="small"
                                    wire:click="$dispatch('openModal', {component: 'cost-center-delete', arguments: { costCenter: {{$center}} } })"
                                >
                                    Delete
                                </x-p::button>
                            </x-p::table.cell>
                        </x-p::table.row>
                    @endforeach
                </x-p::table.body>
            </x-p::table>
        </div>
    </x-p::paper>
</div>
