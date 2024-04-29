<div>
    <x-p::paper class="bg-slate-800 mb-4">
        <x-p::paper-title title="Contents"/>
        <div class="p-2">
            <x-p::table>
                <x-p::table.head>
                    <x-p::table.th>Name</x-p::table.th>
                    <x-p::table.th>Default</x-p::table.th>
                    <x-p::table.th class="text-right">Options</x-p::table.th>
                </x-p::table.head>
                <x-p::table.body>
                    @foreach($contents as $content)
                        <x-p::table.row wire:key="content_{{$content->id}}">
                            <x-p::table.cell>{{ $content->content }}</x-p::table.cell>
                            <x-p::table.cell>{{ $content->is_default }}</x-p::table.cell>
                            <x-p::table.cell class="text-right">
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
