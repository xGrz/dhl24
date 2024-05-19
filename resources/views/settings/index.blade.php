@extends('p::app')

@section('content')
    <div class="max-w-md m-auto grid gap-2">
        <a href="{{ route('dhl24.settings.costCenters.index') }}"
           class="flex gap-2 items-center p-4 bg-slate-800 hover:bg-slate-700 hover:text-slate-100 transition-all cursor-pointer">
            <x-p::icons.settings class="w-5 h-5"/>
            Costs center settings
        </a>
        <a href="{{ route('dhl24.settings.contents.index') }}"
           class="flex gap-2 items-center p-4 bg-slate-800 hover:bg-slate-700 hover:text-slate-100 transition-all cursor-pointer">
            <x-p::icons.settings class="w-5 h-5"/>
            Shipping content suggestions
        </a>
        <a href="{{ route('dhl24.settings.contents.index') }}"
           class="flex gap-2 items-center p-4 bg-slate-800 hover:bg-slate-700 hover:text-slate-100 transition-all cursor-pointer">
            <x-p::icons.settings class="w-5 h-5"/>
            TODO: Shipment diamentions defaults
        </a>
        <a href="{{ route('dhl24.settings.tracking-events.index') }}"
           class="flex gap-2 items-center p-4 bg-slate-800 hover:bg-slate-700 hover:text-slate-100 transition-all cursor-pointer">
            <x-p::icons.settings class="w-5 h-5"/>
            Tracking status configuration
        </a>
    </div>
@endsection
