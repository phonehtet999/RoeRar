@extends('layouts.content-wrapper')

@section('title', 'Promotions')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Promotions</h1>
        <a href="{{ route('promotions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Create</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Promotion List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Product Code</th>
                            <th>Amount Per Unit</th>
                            <th>Total Quantity</th>
                            <th>Remaining Quantity</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promotions as $key => $value)    
                            <tr>
                                <td>{{ $promotions->firstItem() + $key }}</td>
                                <td>{{ $value->product->code }} ({{ $value->product->name }})</td>
                                <td>{{ number_format($value->amount_per_unit) }}</td>
                                <td>{{ $value->total_quantity }}</td>
                                <td>{{ $value->remaining_quantity }}</td>
                                <td>{{ !empty($value->date_from) ? date('d/M/Y', strtotime($value->date_from)) : '' }}</td>
                                <td>{{ !empty($value->date_to) ? date('d/M/Y', strtotime($value->date_to)) : '' }}</td>
                                <td>
                                    
                                    <!-- Edit -->
                                    <a class='btn btn-primary white_font btn-sm' href="{{ route('promotions.edit', $value->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <strong>Edit</strong>
                                    </a>

                                    @if (empty($value->saleDetails()->count()))
                                        <!-- Delete -->
                                        <button type="submit" id="deletebtn" class="btn btn-danger btn-sm" delete_id="{{$value->id}}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                            <strong>Delete</strong>
                                        </button>

                                        <form id="form_destroy_{{$value->id}}" method="POST" action="{{ route('promotions.destroy', $value->id) }}">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')