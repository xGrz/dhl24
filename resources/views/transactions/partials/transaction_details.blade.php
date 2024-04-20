@php use \xGrz\PayU\Facades\Config; @endphp
<x-payu::paper class="bg-slate-800">
    <x-payu::paper-title title="Transaction details">
        <form action="{{route(Config::getRouteName('payments.accept'), $transaction->id)}}" method="POST" id="accept">
            @method('PATCH')
            @csrf
        </form>
        <form action="{{route(Config::getRouteName('.payments.reject'), $transaction->id)}}" method="POST" id="reject">
            @method('DELETE')
            @csrf
        </form>

        @if($transaction->status->hasAction('delete'))
            <x-payu::button form="reject" type="submit" color="danger">Delete transaction</x-payu::button>
        @endif

        @if($transaction->status->hasAction('accept'))
            <x-payu::button form="accept" type="submit" color="success">Accept</x-payu::button>
        @endif
        @if($transaction->status->hasAction('reject'))
            <x-payu::button form="reject" type="submit" color="danger">Reject</x-payu::button>
        @endif
    </x-payu::paper-title>
    <x-payu::table class="p-2">
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Transaction id</x-payu::table.header>
            <x-payu::table.cell>{{$transaction->id}}</x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">PayU order id</x-payu::table.header>
            <x-payu::table.cell>{{$transaction->payu_order_id}}</x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Payment link</x-payu::table.header>
            <x-payu::table.cell>
                <x-payu::link href="{!! $transaction->link !!}">Payment link</x-payu::link>
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Status</x-payu::table.header>
            <x-payu::table.cell>
                <x-payu::status :status="$transaction->status" class="text-lg py-1 px-2"/>
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">PayMethod</x-payu::table.header>
            <x-payu::table.cell>
                @if (empty($transaction->payMethod))
                    No transaction details received yet.
                    @if($transaction->status->hasAction('payMethod'))
                        <x-payu::link href="{{ route('payu.payments.method', $transaction->id) }}">Get pay method now.
                        </x-payu::link>
                    @endif
                @else
                    {{ $transaction->payMethod->name }}
                    <img
                            src="{!! $transaction->payMethod->image !!}"
                            alt="{{ $transaction->payMethod->name }}"
                            class="max-h-10 max-w-16 fill bg-white p-2"
                    />
                @endif
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Amount</x-payu::table.header>
            <x-payu::table.cell>{{ humanAmount($transaction->payload['totalAmount'] / 100, $transaction->payload['currencyCode']) }}</x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Refunded</x-payu::table.header>
            <x-payu::table.cell>
                {{ humanAmount($transaction->refundedAmount(), $transaction->payload['currencyCode']) }}
            </x-payu::table.cell>
        </x-payu::table.row>
        <x-payu::table.row>
            <x-payu::table.header class="text-left">Description</x-payu::table.header>
            <x-payu::table.cell>{{ $transaction->payload['description'] }}</x-payu::table.cell>
        </x-payu::table.row>
        @if (!empty($transaction->payload['additionalDescription']) && $transaction->payload['additionalDescription'] !== $transaction->payload['description'])
            <x-payu::table.row>
                <x-payu::table.header class="text-left">Additional description</x-payu::table.header>
                <x-payu::table.cell>{{ $transaction->payload['additionalDescription'] }}</x-payu::table.cell>
            </x-payu::table.row>
        @endif
        @if (!empty($transaction->payload['visibleDescription']) && $transaction->payload['visibleDescription'] !== $transaction->payload['description'])
            <x-payu::table.row>
                <x-payu::table.header class="text-left">User visible description</x-payu::table.header>
                <x-payu::table.cell>{{ $transaction->payload['visibleDescription'] }}</x-payu::table.cell>
            </x-payu::table.row>
        @endif
    </x-payu::table>
</x-payu::paper>
