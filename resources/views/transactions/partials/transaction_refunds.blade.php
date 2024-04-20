@php use \xGrz\PayU\Facades\Config; @endphp
@props(['tableTitle' => '[tableTitle] not set', 'shouldRenderAction' => false])

@if ($transaction->status->hasAction('refund'))
    <x-payu::paper class="bg-slate-800 mt-4">
        <x-payu::paper-title title="{{ $tableTitle ?? 'Upss, no title set' }}">
            @if($shouldRenderAction && $transaction->isRefundAvailable())
                @include('payu::refunds.create-refund')
            @endif
        </x-payu::paper-title>

        @if ($transaction->refunds->count())
            <x-payu::table>
                <x-payu::table.thead>
                    <x-payu::table.row>
                        <x-payu::table.header class="text-left">Description</x-payu::table.header>
                        <x-payu::table.header>RefundId</x-payu::table.header>
                        <x-payu::table.header class="text-left">Bank description</x-payu::table.header>
                        <x-payu::table.header class="text-left">Status</x-payu::table.header>
                        <x-payu::table.header class="text-right">Amount</x-payu::table.header>
                        <x-payu::table.header class="text-right">Date</x-payu::table.header>
                        <td></td>
                    </x-payu::table.row>
                </x-payu::table.thead>
                <tbody>
                @foreach($transaction->refunds as $refund)
                    <x-payu::table.row>
                        <x-payu::table.cell>{{ $refund->description }}</x-payu::table.cell>
                        <x-payu::table.cell>{{ $refund->refund_id }}</x-payu::table.cell>
                        <x-payu::table.cell>{{ $refund->bank_description }}</x-payu::table.cell>
                        <x-payu::table.cell>
                            <x-payu::status :status="$refund->status"/>
                            @if($refund->error)
                                <small class="block">{{ $refund->errorDescription }}</small>
                            @endif
                        </x-payu::table.cell>
                        <x-payu::table.cell
                            class="text-right">{{ humanAmount($refund->amount, $refund->currency_code) }}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">{{ $refund->created_at }}</x-payu::table.cell>
                        <x-payu::table.cell class="text-right">
                            @if($refund->status->hasAction('delete'))
                                <form action="{{ route(Config::getRouteName('refunds.destroy'), $refund->id) }}" method="POST"
                                      id="delete_refund_{{$refund->id}}">
                                    @csrf @method('DELETE')
                                </form>
                                <x-payu::button class="text-red-500" form="delete_refund_{{$refund->id}}" size="small"
                                                color="danger" type="submit">Delete
                                </x-payu::button>
                            @endif
                            @if($refund->status->hasAction('retry'))
                                <x-payu::buttonlink href="{{route(Config::getRouteName('refunds.retry'), $refund->id)}}">
                                    Retry
                                </x-payu::buttonlink>
                            @endif
                        </x-payu::table.cell>
                    </x-payu::table.row>
                @endforeach
                </tbody>
            </x-payu::table>
            <div class="text-gray-500 my-1 mx-2">
                Once refund is sent you cannot revoke it.
            </div>
        @else
            <x-payu::not-found message="Refunds not found"/>
        @endif

    </x-payu::paper>
@endif
