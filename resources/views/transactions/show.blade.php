@extends('payu::app')

@section('content')

    @include('payu::transactions.partials.transaction_details')
    @include('payu::transactions.partials.transaction_refunds', [
        'tableTitle' => 'Refunds to transaction',
        'shouldRenderAction' => true
    ])

@endsection

