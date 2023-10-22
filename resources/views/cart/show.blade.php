@extends('layouts.content-wrapper')

@section('title', 'Cart Detail')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cart Detail</h1>
    </div>

    <div class="row">

        @if (!empty($productCarts->count()))
            
            <div class="card col-lg-10 mx-auto my-2">
                <div class="card-body">
                    <form action="{{ route('sales.store') }}" method="POST">
                        @php
                            $totalPromotedAmount = 0;
                        @endphp
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="p-2">
                                    @foreach ($productCarts as $value)    
                                    <div id="{{ $value->id }}">
                                        <div class="row mb-4 d-flex justify-content-between align-items-center">
                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                            <!-- Thumbnail -->
                                                @php
                                                    $path = (!empty($value->product->image) and file_exists(public_path('images/products/' . $value->product->image))) ? asset('images/products/' . $value->product->image) : null;

                                                    $promotion = $value->product->promotions()->where('status', 1)->where('remaining_quantity', '>', 0)->first();
                                                    $promotedAmount = 0;
                                                    $promotionQty = 0;
                                                    $promotionItemCount[$value->product->id] = $promotionItemCount[$value->product->id] ?? 0;
                                                    if (!empty($promotion) and ($promotionItemCount[$value->product->id] < $promotion->remaining_quantity)) {
                                                        $promotionQty = min($value->quantity, $promotion->remaining_quantity);
                                                        $promotedAmount = $promotionQty * $promotion->amount_per_unit;
                                                    }

                                                    $totalPromotedAmount += $promotedAmount;
                                                    $promotionItemCount[$value->product->id] = (isset($promotionItemCount[$value->product->id]) ? $promotionItemCount[$value->product->id] : 0) + $promotionQty;
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
                                                <input type="hidden" name="product_cart_ids[]" value="{{ $value->id }}">
                                                <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                                                <h5><b>{{ $value->product->name }}</b></h5>
                                                <h6>{{ $value->product->brand->name }}</h6>
                                                <h6 class="text-black mb-0">
                                                    <span class="badge badge-medium badge-success">
                                                        {{ $value->product->category->name }}
                                                    </span>
                                                </h6>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <h6 class="text-primary">{{ $value->quantity }}</h6>
                                            </div>
                                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                @if (!empty($promotedAmount))
                                                <h6 class="mb-0"><del>{{ number_format(($value->product->unit_selling_price * $value->quantity)) }}</del></h6>
                                                @endif
                                                <h6 class="mb-0">{{ number_format(($value->product->unit_selling_price * $value->quantity) - $promotedAmount) }}</h6>
                                                MMK
                                            </div>
                                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                <button class="btn remove-btn" data-remove="{{ $value->id }}"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        @if (!empty($promotedAmount))
                                        <div class="mb-0 text-success text-right">
                                            Promoted {{ $promotedAmount }} MMK
                                        </div>
                                        @endif
                                        <hr class="my-4">
                                    </div>
                                    @endforeach

                                    <div class="pt-5">
                                        <h6 class="mb-0">
                                            <a href="{{ route('home') }}" class="text-body">
                                                <i class="fas fa-long-arrow-alt-left me-2"></i>
                                                <span class="ml-2">Back to shop</span>
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 bg-primary text-light">
                                <div class="p-5">
                                    <h4 class="fw-bold mt-2 pt-1">Summary</h4>
                                    <hr>
                                    <h5 class="mb-2" id="total-qty">Total Quantity : {{ $cart->productCarts->sum('quantity') }}</h5>
                                    <hr>
                                    <div class="mb-2">
                                        
                                        <div class="form-outline">
                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label for="account_name">Payment Account Name</label>
                                                    <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name') ?? ( $user->account_name ?? '') }}"
                                                        required placeholder="">
                        
                                                    @if ($errors->has('account_name'))
                                                        <span class="text-danger small">{{ $errors->first('account_name') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label for="account_number">Payment Account Number</label>
                                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') ?? ( $user->account_number ?? '') }}"
                                                        required placeholder="">
                        
                                                    @if ($errors->has('account_number'))
                                                        <span class="text-danger small">{{ $errors->first('account_number') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-12 mb-3 mb-sm-0">
                                                    <label class="form-label" for="form3Examplea2">Enter your address</label>
                                                    <textarea class="form-control" id="address" name="address"
                                                        placeholder="">{{ old('address') ?? ($brand->address ?? '') }}</textarea>
                        
                                                    @if ($errors->has('address'))
                                                        <span class="text-danger small">{{ $errors->first('address') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <label class="text-right">Total Promoted</label>
                                    <h6 id="total-amount">{{ $totalPromotedAmount }} MMK</h6>

                                    <hr>

                                    <div class="float-right mb-2">
                                        <label class="text-right">Total price</label>
                                        <h5 id="total-amount">{{ $cart->total_amount - $totalPromotedAmount }} MMK</h5>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block btn-lg"
                                    data-mdb-ripple-color="dark">Order</button>

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
        $('.remove-btn').on('click', function() {
            var id = $(this).attr('data-remove');
            let url = "{{ route('remove-from-cart') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                dataType : 'json',
                type: 'POST',
                data: JSON.stringify({product_cart_id: id}),
                contentType: 'application/json',
                processData: false,
                success: function(response) {
                    if (response.status == 'success') {

                        $(`#${id}`).remove();
                        $('#total-amount').html(`${response.total_amount} MMK`);
                        $('#total-qty').html(response.total_quantity);
                        toastr.success(response.message);
                        console.log(response);
                    } else if (response.status == 'fail') {
                        swal("Failed", response.message, "error");
                    }
                },
                error: function(e, response) {
                    swal("Error", "Something Went Wrong!", "error");
                },
            });

        });
    </script>
@endpush