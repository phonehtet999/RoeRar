@extends('layouts.content-wrapper')

@section('title', 'Create Sale Return')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Sale Return</h1>
    </div>

    <div class="row">

        @if (!empty($sale->saleDetails->count()))
            
            <div class="card col-lg-10 mx-auto my-2">
                <div class="card-body">
                    <form action="{{ route('sale_returns.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-2">
                                    @foreach ($sale->saleDetails as $value)
                                    @if (!empty($value->total_amount)) 
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
            
                                                <div class="col-md-2 col-lg-2 col-xl-2">
                                                    <h5><b>{{ $value->product->name }}</b></h5>
                                                    <h6>{{ $value->product->brand->name }}</h6>
                                                    <h6 class="text-black mb-0">
                                                        <span class="badge badge-medium badge-success">
                                                            {{ $value->product->category->name }}
                                                        </span>
                                                    </h6>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <h6 class="text-primary" id="qty-{{ $value->id }}" data-value="{{ $value->quantity }}" data-unit-price="{{ $value->total_amount / $value->quantity }}">{{ $value->quantity }}</h6>
                                                </div>
                                                <div class="col-md-1 col-lg-1 col-xl-1 offset-lg-1">
                                                    <h6 class="mb-0" id="amount-{{ $value->id }}">{{ number_format($value->total_amount) }}</h6>
                                                    MMK
                                                </div>

                                                <!-- Product qty to return -->
                                                <div class="container col-md-2">
                                                    <div class="row">
                                                        <div class="col-xs-4 col-xs-offset-3">
                                                            <div class="input-group number-spinner">
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default border-0" data-dir="dwn">
                                                                        <i class="fas fa-minus text-danger fa-sm"></i>
                                                                    </button>
                                                                </span>
                                                                <input type="text" name="return_sale_details[{{$value->id}}]" id="qty-rtn-{{ $value->id }}" data-id="{{ $value->id }}" data-min-qty="{{ $value->quantity }}" class="form-control text-center border-0 qty-input" value="0">
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default border-0" data-dir="up">
                                                                        <i class="fas fa-plus text-danger fa-sm"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <center>Qty To Return</center>
                                                </div>

                                                @if (empty($value->total_promoted_amount))    
                                                <div class="col-md-3">
                                                    @php
                                                        $unitPrice = $value->total_amount / $value->quantity;
                                                        $products = App\Models\Product::where('unit_selling_price', $unitPrice)
                                                                        ->where('quantity', '>=', $value->quantity)
                                                                        ->where('status', 'in_stock')
                                                                        ->get();
                                                    @endphp
                                                    <select name="product[{{ $value->id }}]" class="form-control select2" id="product-id">
                                                        <option value="">Select Exchange Product</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}" {{ old('product[$product->id]') ? 'selected' : '' }}>{{ $product->code }} - ( {{ $product->name }}) </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @else
                                                <div class="col-md-3">
                                                </div>
                                                @endif
                                                
                                            </div>
                                            @if (!empty($value->total_promoted_amount))
                                            <div class="mb-0 text-success text-right">
                                                Promoted {{ $value->total_promoted_amount }} MMK
                                            </div>
                                            @endif
                                            <hr class="my-4">
                                        </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-lg-8 bg-primary text-light mx-auto">
                                <div class="p-5">
                                    <h4 class="fw-bold mt-2 pt-1">Customer Detail</h4>
                                    @php
                                        $customer = $sale->customer;
                                    @endphp
                                    <hr>
                                    <h5 class="mb-2" id="total-qty">Total Quantity : {{ $sale->saleDetails()->sum('quantity') }}</h5>
                                    <hr>
                                    <h5 class="mb-2" id="total-qty">Total Price : {{ $sale->total_amount }} MMK</h5>
                                    
                                    <hr>
                                    <h5 class="mb-2" id="total-qty">Name: <span class="ml-2">{{ $customer->user->name }}</span></h5>
                                    <h5 class="mb-2" id="total-qty">Phone: <span class="ml-2">{{ $customer->phone_number }}</span></h5>

                                    <hr>
                                    <div class="mb-2">
                                        
                                        <div class="form-outline">

                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label class="form-label" for="form3Examplea2">Description</label>
                                                    <textarea class="form-control" id="description" name="description"
                                                        placeholder="">{{ old('description') ?? ($brand->description ?? '') }}</textarea>
                        
                                                    @if ($errors->has('description'))
                                                        <span class="text-danger small">{{ $errors->first('description') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="mb-2">
                                        <label class="text-right">Total Price to Return</label>
                                        <h5><span id="return-amount">0</span> MMK</h5>
                                    </div>

                                    <hr>

                                    <button type="submit" class="btn btn-success btn-block btn-lg"
                                    data-mdb-ripple-color="dark">Return</button>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-warning mx-auto col-lg-8 text-center" role="alert">
                <h4 class="alert-heading">Empty Cart!</h4>
            </div>
        @endif

    </div>
</div>
@stop

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('js')
    <script>
        $(document).on('click', '.number-spinner button', function () {
            var returnAmount = $('#return-amount').html() ?? 0;
            var btn = $(this),
                id = btn.closest('.number-spinner').find('input').attr('data-id'),
                oldValue = btn.closest('.number-spinner').find('input').val().trim(),
                minQty = $(`#qty-${id}`).attr('data-value'),
                unitPrice = $(`#qty-${id}`).attr('data-unit-price'),
                newVal = 0;
            
            if (btn.attr('data-dir') == 'up') {
                if (parseInt(parseInt(minQty)) > parseInt(oldValue)) {
                    newVal = parseInt(oldValue) + 1;

                    returnAmount = parseInt(returnAmount) + parseInt(unitPrice);
                } else {
                    newVal = oldValue;
                }
            } else {
                if (oldValue > 0) {
                    newVal = parseInt(oldValue) - 1;
                    returnAmount = parseInt(returnAmount) - parseInt(unitPrice);
                } else {
                    newVal = 0;
                }
            }

            $('#return-amount').html(returnAmount);
            btn.closest('.number-spinner').find('input').val(newVal);
        });
        
    </script>
@endpush