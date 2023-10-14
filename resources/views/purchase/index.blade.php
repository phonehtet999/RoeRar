@extends('layouts.content-wrapper')

@section('title', 'Purchases')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Purchases</h1>
        <a href="{{ route('purchases.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Create</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Purchase List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="overflow:scroll;white-space: nowrap;" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Invoice Number</th>
                            <th>Supplier Name</th>
                            <th>Staff Name</th>
                            <th>Product Code</th>
                            <th>Unit Selling Price</th>
                            <th>Unit Buying Price</th>
                            <th>Payment Type</th>
                            <th>Total Amount</th>
                            <th>Qty</th>
                            <th>Description</th>
                            {{-- <th>Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $key => $value)    
                            <tr>
                                <td>{{ $purchases->firstItem() + $key }}</td>
                                <td>{{ $value->invoice_number }}</td>
                                <td>{{ $value->supplier->user->name }}</td>
                                <td>{{ $value->staff->user->name }}</td>
                                <td>{{ $value->product->code }}</td>
                                <td class="text-right">{{ number_format($value->unit_selling_price) }} MMK</td>
                                <td class="text-right">{{ number_format($value->unit_buying_price) }} MMK</td>
                                <td>{{ $value->payment_type }}</td>
                                <td class="text-right">{{ number_format($value->payment->total_amount) }} MMK</td>
                                <td class="text-right">{{ $value->quantity }}</td>

                                <td>{{ \Illuminate\Support\Str::limit($value->description, 50, $end = '...') }}</td>
                                {{-- <td>
                                    <!-- Edit -->
                                    <a class='btn btn-primary white_font btn-sm' href="{{ route('purchases.edit', $value->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <strong>Edit</strong>
                                    </a>
                                    <!-- Delete -->
                                    <button type="submit" id="deletebtn" class="btn btn-danger btn-sm" delete_id="{{$value->id}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <strong>Delete</strong>
                                    </button>

                                    <form id="form_destroy_{{$value->id}}" method="POST" action="{{ route('purchases.destroy', $value->id) }}">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')