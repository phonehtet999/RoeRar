@extends('layouts.content-wrapper')

@section('title', 'Edit Sale')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Sale</h1>
    </div>

    <div class="row">

        <div class="col-lg-6">

            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sale Detail</h6>
                    </div>

                    <div class="card-body">
                        <table  class="table table-bordered">
                            <tr>
                                <th>Sale Code</th>
                                <td>{{ $sale->code }}</td>
                            </tr>
                            <tr>
                                <th>Total Promoted Amount</th>
                                <td>{{ number_format($sale->saleDetails()->sum('total_promoted_amount')) }} MMK</td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>{{ number_format($sale->total_amount) }} MMK</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ date('d M Y', strtotime($sale->date)) }}</td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td>{{ $sale->customer->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Customer Phone Number</th>
                                <td>{{ $sale->customer->phone_number }}</td>
                            </tr>
                            <tr>
                                <th>Customer Email</th>
                                <td>{{ $sale->customer->user->email }}</td>
                            </tr>

                            @if (!empty($sale->staff))
                            <tr>
                                <th>Staff Updated By</th>
                                <td>{{ $sale->staff->user->name }}</td>
                            </tr>
                            @endif
                        </table>
                        <form class="sale" action="{{ route('sales.update', $sale->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <label for="order_status">Order Status</label>
                                    <select name="order_status" class="form-control" id="supplier-id" required>
                                        <option>Select Order Status</option>
                                        <option value="ordered" {{ 'ordered' == $sale->status ? 'selected' : ''}}>Ordered</option>
                                        <option value="approved" {{ 'approved' == $sale->status ? 'selected' : ''}}>Approved</option>
                                        <option value="delivered" {{ 'delivered' == $sale->status ? 'selected' : ''}}>Delivered</option>
                                    </select>

                                    @if ($errors->has('order_status'))
                                        <span class="text-danger small">{{ $errors->first('order_status') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description"
                                        placeholder="Description">{{ old('description') ?? ($sale->description ?? '') }}</textarea>
        
                                    @if ($errors->has('description'))
                                        <span class="text-danger small">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Update Sale
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Detail</h6>
                        @php
                            $payment = App\Models\Payment::where('model_type', 'Sale')->where('reference_id', $sale->id)->first();
                        @endphp
                    </div>

                    <div class="card-body">
                        <table  class="table table-bordered">
                            <tr>
                                <th class="col-sm-6">Total Amount</th>
                                <td class="col-sm-6">
                                    {{ $payment->total_amount }}
                                </td>
                            </tr>
                            <tr>
                                <th class="col-sm-6">Account Name</th>
                                <td class="col-sm-6">
                                    {{ $payment->account_name }}
                                </td>
                            </tr>
                            <tr>
                                <th class="col-sm-6">Account Number</th>
                                <td class="col-sm-6">
                                    {{ $payment->account_number }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6">

            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
                    </div>

                    <div class="card-body">
                        <div class="p-2">
                            @foreach ($sale->saleDetails as $value)    
                            <div id="{{ $value->id }}">
                                <div class="row mb-4 d-flex justify-content-between align-items-center">
                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                    <!-- Thumbnail -->
                                        @php
                                            $path = (!empty($value->product->image) and file_exists(public_path('images/products/' . $value->product->image))) ? asset('images/products/' . $value->product->image) : null;
                                        @endphp
                                        @if (!empty($path))
                                            <a target="_blank" href="{{ $path }}">
                                                <img src="{{ $path }}" width="100px" class="image-border-thumbnail d-block mb-1">
                                            </a>
                                        @else
                                            ---
                                        @endif
                                    </div>

                                    <div class="col-md-4 col-lg-3 col-xl-3">
                                        <h5><b>{{ $value->product->name }}</b></h5>
                                        <h6>{{ $value->product->brand->name }}</h6>
                                        <h6 class="text-black mb-0">
                                            <span class="badge badge-medium badge-success">
                                                {{ $value->product->category->name }}
                                            </span>
                                        </h6>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if ($value->total_amount <= 0)
                                            <h6 class="mb-0 text-warning">Returned</h6>
                                        @else
                                            <h6 class="text-primary">{{ $value->quantity }}</h6>
                                        @endif
                                    </div>
                                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                        @if ($value->total_amount <= 0)
                                                <h6 class="mb-0 text-warning">Returned</h6>
                                        @else
                                            <h6 class="mb-0">{{ number_format($value->total_amount) }}</h6>
                                            MMK
                                        @endif
                                    </div>
                                </div>

                                @if (!empty($value->total_promoted_amount))
                                    <div class="mb-0 text-success text-right">
                                        Promoted {{ $value->total_promoted_amount }} MMK
                                    </div>
                                @endif
                                <hr class="my-4">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Delivery Detail</h6>
                        @php
                            $delivery = $sale->delivery;
                        @endphp
                    </div>

                    <div class="card-body">
                        <table  class="table table-bordered">
                            <tr>
                                <th class="col-sm-3">Address</th>
                                <td class="col-sm-9">
                                    {{ $delivery->address }}
                                </td>
                            </tr>
                        </table>

                        <form class="delivery" action="{{ route('deliveries.update', $delivery->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <label for="delivery_status">Delivery Status</label>
                                    <select name="delivery_status" class="form-control" id="supplier-id" required>
                                        <option>Select Delivery Status</option>
                                        <option value="pending" {{ 'pending' == $delivery->status ? 'selected' : ''}}>Pending</option>
                                        <option value="delivered" {{ 'delivered' == $delivery->status ? 'selected' : ''}}>Delivered</option>
                                    </select>

                                    @if ($errors->has('delivery_status'))
                                        <span class="text-danger small">{{ $errors->first('delivery_status') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <label for="delivery_cost">Delivery Cost</label>
                                    @php
                                        $deliveryPayment = App\Models\Payment::where('model_type', 'Delivery')->where('reference_id', $delivery->id)->first();
                                        $deliveryCost = (!empty($deliveryPayment) and $deliveryPayment->total_amount) ? $deliveryPayment->total_amount : 0;
                                    @endphp
                                    <input type="number" class="form-control" id="delivery_cost" name="delivery_cost" value="{{ old('delivery_cost') ?? ( $deliveryCost ?? '') }}"
                                        required placeholder="" min="0">
        
                                    @if ($errors->has('delivery_cost'))
                                        <span class="text-danger small">{{ $errors->first('delivery_cost') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <label for="deli_description">Description</label>
                                    <textarea class="form-control" id="deli_description" name="deli_description"
                                        placeholder="Description">{{ old('deli_description') ?? ($delivery->description ?? '') }}</textarea>
        
                                    @if ($errors->has('deli_description'))
                                        <span class="text-danger small">{{ $errors->first('deli_description') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    
    </div>

</div>
@stop

@include('notification')