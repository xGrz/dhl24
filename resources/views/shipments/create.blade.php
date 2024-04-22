@extends('p::app')

@section('content')
    <x-p::paper class="bg-slate-800">
        <x-p::paper-title title="Create shipment"/>
        <form method="POST" action="{{route('dhl24.shipments.store')}}">
            @csrf
            <div class="grid grid-cols-3 gap-2 mx-2">
                <div class="col-span-2">
                    <h2>Recipient address:</h2>
                    <div class="grid grid-cols-12 gap-4 mx-2">
                        <div class="col-span-12">
                            <x-p::input name="recipient[name]" label="Recipient full name"/>
                        </div>
                        <div class="col-span-4">
                            <x-p::input name="recipient[postal_code]" label="Postal code"/>
                        </div>
                        <div class="col-span-8">
                            <x-p::input name="recipient[city]" label="City"/>
                        </div>
                        <div class="col-span-8">
                            <x-p::input name="recipient[street]" label="Street"/>
                        </div>
                        <div class="col-span-2">
                            <x-p::input name="recipient[house_number]" label="House number"/>
                        </div>
                        <div class="col-span-2">
                            <x-p::input name="recipient[apartment_number]" label="Apartment"/>
                        </div>
                    </div>
                </div>
                <div>
                    <h2>Contact</h2>
                    <div class="grid gap-4 mx-2">
                        <div class="col-span-12">
                            <x-p::input name="contact[name]" label="Contact name"/>
                        </div>
                        <div class="col-span-12">
                            <x-p::input name="contact[email]" label="Contact email"/>
                        </div>
                        <div class="col-span-12">
                            <x-p::input name="contact[phone]" label="Contact phone"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-2 py-4">
                <x-p::button type="submit">Test</x-p::button>
            </div>
        </form>
    </x-p::paper-title>
@endsection
