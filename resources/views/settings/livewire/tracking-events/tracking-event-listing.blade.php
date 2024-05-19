<div>
    <x-p-paper>
        <x-slot:title>Tracking events</x-slot:title>
        <x-p-table>
            <x-p-thead>
                <x-p-tr left>
                    <x-p-th>Status symbol</x-p-th>
                    <x-p-th>Description</x-p-th>
                    <x-p-th>Typ</x-p-th>
                    <x-p-th right>Options</x-p-th>
                </x-p-tr>
            </x-p-thead>
            <x-p-tbody>
                @foreach($events as $event)
                    <x-p-tr>
                        <x-p-td>{{$event->symbol}}</x-p-td>
                        <x-p-td>{{$event->custom_description ?? $event->description}}</x-p-td>
                        <x-p-td>{{$event->type->getLabel()}}</x-p-td>
                        <x-p-td right>
                            <x-p-button
                                wire:click="$dispatch('openModal', { component: 'tracking-event-edit', arguments: { event: '{{$event->symbol}}' }}) " size="small"
                            >
                                Edit
                            </x-p-button>
                        </x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>
    </x-p-paper>

</div>
