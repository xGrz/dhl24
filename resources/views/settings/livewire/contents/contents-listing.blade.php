<div>
    <x-p-paper class="bg-slate-800 mb-4">
        <x-slot:title>Contents</x-slot:title>
        <x-slot:actions>
            <x-p-button
                color="success"
                wire:click="$dispatch('openModal', {component: 'content-create'})"
            >
                Add
            </x-p-button>
        </x-slot:actions>
        <div class="p-2">
            <x-p-table>
                <x-p-thead>
                    <x-p-th>Name</x-p-th>
                    <x-p-th class="text-right">Options</x-p-th>
                </x-p-thead>
                <x-p-tbody>
                    @foreach($contents as $content)
                        <x-p-tr wire:key="content_{{$content->id}}">
                            <x-p-td>
                                @if($content->is_default)
                                    <strong class="text-slate-300">{{ $content->name }}</strong>
                                @else
                                    {{ $content->name }}
                                @endif
                            </x-p-td>
                            <x-p-td class="text-right">
                                @if($content->is_default)
                                    <button wire:click.prevent="removeDefault({{$content->id}})"
                                            class="text-yellow-500 hover:text-yellow-700 transition-all">
                                        <x-p::icons.star-full class="w-5 h-5"/>
                                    </button>
                                @else
                                    <button wire:click.prevent="setAsDefault({{$content->id}})"
                                            class="text-slate-500 hover:text-yellow-500 transition-all">
                                        <x-p::icons.star class="w-5 h-5"/>
                                    </button>
                                @endif
                                <x-p-button
                                    type="button"
                                    size="small"
                                    wire:click.prevent="$dispatch('openModal', {component: 'content-edit', arguments: { contentSuggestion: {{$content}} } })"
                                >
                                    Edit
                                </x-p-button>
                                <x-p-button
                                    wire:click.prevent="$dispatch('openModal', {component: 'content-delete', arguments: { contentSuggestion: {{$content}} } })"
                                    color="danger"
                                    size="small"
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
