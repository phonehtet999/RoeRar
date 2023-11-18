@extends('layouts.content-wrapper')

@section('title', 'Sale Returns')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sale Returns</h1>
        <a href="{{ route('sale_returns.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Create</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sale Return List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Code</th>
                            <th>Sale Code</th>
                            <th>Product Code</th>
                            <th>Exchanged Product Code</th>
                            <th>Returned Quantity</th>
                            <th>Total Returned Amount</th>
                            <th>Description</th>
                            {{-- <th>Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saleReturns as $key => $value)    
                            <tr>
                                <td>{{ $saleReturns->firstItem() + $key }}</td>
                                <td>{{ $value->code }}</td>
                                <td>
                                    <a href="{{ route('sales.show', $value->saleDetail->sale->id) }}">
                                        {{ $value->saleDetail->sale->code }}
                                    </a>
                                </td>
                                <td>{{ $value->product->code }}</td>
                                <td>{{ $value->exchangedProduct->code ?? '' }}</td>
                                <td>{{ $value->returned_quantity }}</td>
                                <td class="text-right">{{ number_format($value->total_returned_amount) }} MMK</td>
                                <td>{{ $value->description }}</td>
                                {{-- <td>
                                    <!-- Edit -->
                                    <a class='btn btn-primary white_font btn-sm' href="{{ route('sale_returns.edit', $value->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <strong>Edit</strong>
                                    </a>
                                    <!-- Delete -->
                                    <button type="submit" id="deletebtn" class="btn btn-danger btn-sm" delete_id="{{$value->id}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <strong>Delete</strong>
                                    </button>

                                    <form id="form_destroy_{{$value->id}}" method="POST" action="{{ route('sale_returns.destroy', $value->id) }}">
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
                {{ $saleReturns->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')