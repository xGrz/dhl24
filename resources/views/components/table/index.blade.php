<div class="p-2 w-full overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full']) }}>
        {{$slot}}
    </table>
</div>
