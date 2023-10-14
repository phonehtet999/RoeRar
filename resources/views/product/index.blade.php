@extends('layouts.content-wrapper')

@section('title', 'Products')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
        <a href="{{ route('products.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Create</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="overflow:scroll;white-space: nowrap;" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Code</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Color</th>
                            <th>Qty</th>
                            <th>Required Qty</th>
                            <th>Qty Status</th>
                            <th>Unit Selling Price</th>
                            <th>Unit Buying Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $value)    
                            <tr>
                                <td>{{ $products->firstItem() + $key }}</td>
                                <td>{{ $value->code }}</td>
                                <td>
                                    <!-- Thumbnail -->
                                    @php
                                        $path = (!empty($value->image) and file_exists(public_path('images/products/' . $value->image))) ? asset('images/products/' . $value->image) : null;
                                    @endphp
                                    @if (!empty($path))
                                        <a target="_blank" href="{{ $path }}">
                                            <img src="{{ $path }}" width="100px" class="image-border-thumbnail d-block mb-1">
                                        </a>
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->brand->name }}</td>
                                <td>{{ $value->category->name }}</td>
                                <td>
                                    <input type="color" style="border-width: 1px" readonly value="{{ $value->color }}" disabled>    
                                </td>
                                <td class="text-right">{{ number_format($value->quantity) }}</td>
                                <td class="text-right">{{ $value->minimum_required_quantity }}</td>
                                <td>
                                    @if ($value->minimum_required_quantity > $value->quantity)
                                    <form action="{{ route('purchases.create-second') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ $value->id }}" name="product_id">
                                        <button type="submit" style="all: unset;">
                                            <span class="badge badge-medium badge-danger mb-2" style="text-decoration: underline;cursor: pointer;">Insufficient</span>
                                        </button>
                                    </form>
                                    @else
                                        <span class="badge badge-success">Sufficient</span>
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($value->unit_selling_price) }} MMK</td>
                                <td class="text-right">{{ number_format($value->unit_buying_price) }} MMK</td>
                                <td>
                                    @if ($value->status = 'in_stock')
                                        <span class="badge badge-success">In Stock</span>
                                    @else
                                        <span class="badge badge-danger">Out of Stock</span>
                                    @endif
                                </td>

                                <td>
                                    @if (empty($value->purchases()->count()))
                                    <!-- Edit -->
                                    <a class='btn btn-primary white_font btn-sm' href="{{ route('products.edit', $value->id) }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <strong>Edit</strong>
                                    </a>
                                    @endif
                                    <!-- Delete -->
                                    <button type="submit" id="deletebtn" class="btn btn-danger btn-sm" delete_id="{{$value->id}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        <strong>Delete</strong>
                                    </button>

                                    <form id="form_destroy_{{$value->id}}" method="POST" action="{{ route('products.destroy', $value->id) }}">
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
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@include('notification')