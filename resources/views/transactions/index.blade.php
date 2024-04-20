@php use Illuminate\Support\Number;use xGrz\PayU\Enums\PaymentStatus; use xGrz\PayU\Facades\Config @endphp
@extends('payu::app')

@section('breadcrumbs')
    @if(!empty($shop))
        <div class="flex justify-end">
            <div class="bg-green-700 px-3 py-1 shrink rounded-l-md shadow-md text-white text-center">
                <small>ShopBalance balance:</small><br/>
                {{ $shop->humanAmount->balance }}
            </div>
            <a
                class="bg-green-700 hover:bg-green-800 cursor-pointer px-3 py-1 shrink rounded-r-md shadow-md text-white align-self-center"
                href="{{ route(Config::getRouteName('payouts.index')) }}"
            >
                <small>Payout</small>
            </a>
        </div>
    @endif
@endsection

@section('content')
    <x-payu::pagination.info :source="$transactions"/>
    <x-payu::paper class="bg-slate-800">
        <x-payu::paper-title title="Transactions listing">
            <x-payu::buttonlink href="{{route(Config::getRouteName('payments.create'))}}">Wizard</x-payu::buttonlink>
            <form action="{{route(Config::getRouteName('.payments.storeFake'))}}" method="POST" id="createTransaction" class="hidden">
                @csrf
            </form>
            <x-payu::button type="submit" form="createTransaction" color="success">
                Fake payment
            </x-payu::button>
            <x-payu::buttonlink href="https://merch-prod.snd.payu.com/user/login?lang=pl" target="new" color="warning">
                PayU-Panel
            </x-payu::buttonlink>
        </x-payu::paper-title>

        @if($transactions->count())
            <x-payu::table>
                <x-payu::table.thead class="text-left text-white leading-8">
                    <x-payu::table.row>
                        <x-payu::table.header>Description</x-payu::table.header>
                        <x-payu::table.header>Value</x-payu::table.header>
                        <x-payu::table.header class="text-right">Refunded</x-payu::table.header>
                        <x-payu::table.header class="text-center">Status</x-payu::table.header>
                        <x-payu::table.header class="text-right">Created</x-payu::table.header>
                        <x-payu::table.header class="text-right">Updated</x-payu::table.header>
                        <x-payu::table.header class="text-right">Actions</x-payu::table.header>
                    </x-payu::table.row>
                </x-payu::table.thead>
                <tbody class="leading-tight">
                @foreach($transactions as $transaction)
                    <x-payu::table.row class="hover:bg-gray-100">
                        <x-payu::table.cell>
                            @if($transaction->status === PaymentStatus::INITIALIZED)
                                <span class="text-gray-300">{{ $transaction->payload['description']  }}</span>
                            @else
                                <x-payu::link href="{{ route(Config::getRouteName('payments.show'), $transaction->id )}}">
                                    {{ $transaction->payload['description']  }}
                                </x-payu::link>
                            @endif
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            {{ Number::currency($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode'], 'pl') }}
                            @if($transaction->payMethod)
                                <small class="block">
                                    {{$transaction->payMethod->name}}
                                </small>
                            @endif
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            @if($transaction->hasSuccessfulRefunds())
                                <small
                                    class="block text-green-500">{{ humanAmount($transaction->refundedAmount()) }}</small>
                            @endif
                            @if($transaction->hasDefinedRefunds())
                                <small
                                    class="block text-slate-500">{{ humanAmount($transaction->getDefinedRefundsTotalAmount()) }}</small>
                            @endif
                            @if($transaction->hasFailedRefunds())
                                <small
                                    class="block text-red-500">{{ humanAmount($transaction->getFailedRefundsTotalAmount()) }}</small>
                            @endif
                        </x-payu::table.cell>
                        <x-payu::table.cell>
                            <x-payu::status :status="$transaction->status" class="text-sm mx-2"/>
                        </x-payu::table.cell>
                        <x-payu::table.cell class="text-right">{{ $transaction->created_at }}</x-payu::table.cell>
                        <x-payu::table.cell
                            class="text-right">{{ $transaction->created_at == $transaction->updated_at ? '' : $transaction->updated_at}}</x-payu::table.cell>
                        <x-payu::table.cell class="text-nowrap text-right">
                            @include('payu::transactions.partials.transaction_actions')
                        </x-payu::table.cell>
                    </x-payu::table.row>
                @endforeach
                </tbody>
            </x-payu::table>

            <div class="py-3">
                <x-payu::pagination :source="$transactions"/>
            </div>
        @else
            <x-payu::not-found message="Transactions for found."/>
        @endif


    </x-payu::paper>
    <x-payu::pagination.info :source="$transactions"/>
@endsection

