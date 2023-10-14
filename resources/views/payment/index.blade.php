@extends('layouts.content-wrapper')

@section('title', 'Payments')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payments</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Code</th>
                            <th>Total Amount</th>
                            <th>Account Name</th>
                            <th>Account Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $key => $value)    
                            <tr>
                                <td>{{ $payments->firstItem() + $key }}</td>
                                <td>{{ $value->model_type }}</td>
                                <td>
                                    @php
                                        $sale = null;
                                        $purchase = null;
                                        $delivery = null;

                                        if ($value->model_type == 'Sale') {
                                            $sale = App\Models\Sale::find($value->reference_id);
                                        } elseif ($value->model_type == 'Purchase') {
                                            $purchase = App\Models\Purchase::find($value->reference_id);
                                        } elseif ($value->model_type == 'Delivery') {
                                            $delivery = App\Models\Delivery::find($value->reference_id);
                                        }
                                    @endphp
                                    @if (!empty($sale))    
                                        <a href="{{ route('sales.edit', $sale->id) }}">
                                            {{ $sale->code }}
                                        </a>
                                    @endif

                                    @if (!empty($purchase))    
                                        <a href="{{ route('purchases.index', ['id' => $value->reference_id]) }}">
                                            {{ $purchase->invoice_number }}
                                        </a>
                                    @endif

                                    @if (!empty($delivery))    
                                        <a href="{{ route('sales.edit', $delivery->sale->id) }}">
                                            {{ $delivery->sale->code }}
                                        </a>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if ($value->model_type == 'Purchase')
                                        -
                                    @endif
                                    {{ number_format($value->total_amount) }} MMK
                                </td>
                                <td>{{ $value->account_name }}</td>
                                <td>{{ $value->account_number }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')