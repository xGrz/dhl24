<div>
    <x-p::paper class="bg-slate-800 mb-4">
        <x-p::paper-title title="Contents">
            <x-p::button
                color="success"
                wire:click="$dispatch('openModal', {component: 'content-create'})"
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
                    @foreach($contents as $content)
                        <x-p::table.row wire:key="content_{{$content->id}}">
                            <x-p::table.cell>
                                @if($content->is_default)
                                    <strong class="text-slate-300">{{ $content->name }}</strong>
                                @else
                                    {{ $content->name }}
                                @endif
                            </x-p::table.cell>
                            <x-p::table.cell class="text-right">
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
                                <x-p::button
                                    type="button"
                                    size="small"
                                    wire:click="$dispatch('openModal', {component: 'content-edit', arguments: { content: {{$content}} } })"
                                >
                                    Edit
                                </x-p::button>
                                <x-p::buttonlink
                                    href="{{route('dhl24.contents.destroy', $content->id)}}"
                                    color="danger"
                                    size="small"
                                >
                                    Delete
                                </x-p::buttonlink>
                            </x-p::table.cell>
                        </x-p::table.row>
                    @endforeach
                </x-p::table.body>
            </x-p::table>
        </div>
    </x-p::paper>
</div>
