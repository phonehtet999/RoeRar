@extends('layouts.content-wrapper')

@section('title', 'Sales')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sales</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sale List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Code</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Customer Name</th>
                            <th>Phone Number</th>
                            <th>Sale Status</th>
                            <th>Delivery Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $key => $value)    
                            <tr>
                                <td>{{ $sales->firstItem() + $key }}</td>
                                <td>{{ $value->code}}</td>
                                <td>{{ date('d M Y', strtotime($value->date)) }}</td>
                                <td class="text-right">{{ number_format($value->total_amount) }}</td>
                                <td>{{ $value->customer->user->name }}</td>
                                <td>{{ $value->customer->phone_number }}</td>
                                <td>
                                    @if ($value->status == 'ordered')
                                        <span class="badge badge-info">Ordered</span>
                                    @elseif ($value->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif ($value->status == 'delivered')
                                        <span class="badge badge-success">Delivered</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($value->delivery->status == 'pending')
                                        <span class="badge badge-secondary">Pending</span>
                                    @elseif ($value->delivery->status == 'delivered')
                                        <span class="badge badge-success">Delivered</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $userType = getUserType(auth()->user());
                                    @endphp
                                    <!-- Edit -->
                                    @if ($userType == 'staff')
                                    <a class='btn btn-primary white_font btn-sm' href="{{ route('sales.edit', $value->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <strong>Edit</strong>
                                    </a>
                                    @endif

                                    @if ($userType == 'customer')
                                    <a class='btn btn-primary white_font btn-sm' href="{{ route('sales.show', $value->id) }}" title="View">
                                        <i class="fas fa-eye"></i>
                                        <strong>View</strong>
                                    </a>
                                    @endif

                                    <!-- Delete -->
                                    {{-- <button type="submit" id="deletebtn" class="btn btn-danger btn-sm" delete_id="{{$value->id}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <strong>Delete</strong>
                                    </button>

                                    <form id="form_destroy_{{$value->id}}" method="POST" action="{{ route('sales.destroy', $value->id) }}">
                                        @method('DELETE')
                                        @csrf
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')