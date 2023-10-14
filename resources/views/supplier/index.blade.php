@extends('layouts.content-wrapper')

@section('title', 'Suppliers')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Suppliers</h1>
        <a href="{{ route('suppliers.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Create</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Supplier List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company Name</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $key => $value)    
                            <tr>
                                <td>{{ $suppliers->firstItem() + $key }}</td>
                                <td>{{ $value->user->name }}</td>
                                <td>{{ $value->user->email }}</td>
                                <td>{{ $value->company_name }}</td>
                                <td>{{ $value->phone_number }}</td>
                                <td>{{ $value->address }}</td>
                                <td>
                                    <!-- Edit -->
                                    <a class='btn btn-primary white_font btn-sm' href="{{ route('suppliers.edit', $value->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <strong>Edit</strong>
                                    </a>
                                    <!-- Delete -->
                                    <button type="submit" id="deletebtn" class="btn btn-danger btn-sm" delete_id="{{$value->id}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <strong>Delete</strong>
                                    </button>

                                    <form id="form_destroy_{{$value->id}}" method="POST" action="{{ route('suppliers.destroy', $value->id) }}">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')