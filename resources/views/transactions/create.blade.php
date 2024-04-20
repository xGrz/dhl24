@php use \xGrz\PayU\Facades\Config; @endphp
@extends('payu::app')

@section('css')
@endsection

@section('content')
    <div class="max-w-[800px] m-auto">
        <form action="{{route(Config::getRouteName('.payments.store'))}}" method="POST" class="grid gap-2">
            @csrf
            <x-payu::paper class="bg-slate-800">
                <x-payu::paper-title title="Customer"/>
                @include('payu::transactions.create.customer')
            </x-payu::paper>

            <x-payu::paper class="bg-slate-800">
                <x-payu::paper-title title="Order items"/>
                @include('payu::transactions.create.items')
            </x-payu::paper>

            @if($methods->count())
            <x-payu::paper class="bg-slate-800">
                <x-payu::paper-title title="Payment method"/>
                @include('payu::transactions.create.paymethod')
            </x-payu::paper>
            @endif

            <div class="text-center">
                <x-payu::button type="submit" size="large">
                    Create order
                </x-payu::button>
            </div>
        </form>
    </div>
@endsection
