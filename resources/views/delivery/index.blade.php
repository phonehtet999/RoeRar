@extends('layouts.content-wrapper')

@section('title', 'Deliveries')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Deliveries</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Delivery List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Sale Code</th>
                            <th>Customer Name</th>
                            <th>Customer Phone No:</th>
                            <th>Status</th>
                            <th>Address</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveries as $key => $value)    
                            <tr>
                                <td>{{ $deliveries->firstItem() + $key }}</td>
                                <td>
                                    <a href="{{ route('sales.edit', $value->sale->id) }}">
                                        {{ $value->sale->code}}
                                    </a>
                                </td>
                                <td>{{ $value->sale->customer->user->name }}</td>
                                <td>{{ $value->sale->customer->phone_number }}</td>
                                <td>
                                    @if ($value->status == 'pending')
                                        <span class="badge badge-info">Pending</span>
                                    @elseif ($value->status == 'delivered')
                                        <span class="badge badge-success">Delivered</span>
                                    @endif
                                </td>
                                <td>{{ $value->address }}</td>
                                <td>{{ $value->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $deliveries->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')