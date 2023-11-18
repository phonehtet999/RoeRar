@extends('layouts.content-wrapper')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer Dashboard</h1>
        <div class="d-flex">
            <a href="{{ route('carts.show', $cart->id) }}" class="btn btn-outline-primary" type="submit">
                <i class="fas fa-cart-plus fa-sm"></i>
                Cart
                <span id="cart-count" class="badge bg-primary text-white ms-1 rounded-pill">{{ $count }}</span>
            </a>
        </div>
    </div>

    <section>
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach ($products as $value)
                    @php
                        $today = date('Y-m-d');
                        $inStock = (($value->status == 'in_stock') and ($value->quantity > 0));
                        $promotion = $value->promotions()->where('status', 1)
                                            ->where('remaining_quantity', '>', 0)
                                            ->where(function ($query) use ($today) {
                                                return $query->where('date_from', null)
                                                    ->orWhere('date_from', '<=', $today);
                                            })
                                            ->where(function ($query) use ($today) {
                                                return $query->where('date_to', null)
                                                    ->orwhere('date_to', '>=', $today);
                                            })
                                            ->first();
                    @endphp
                    <div class="col mb-5">
                        <div class="card h-100">
                            <div class="row position-absolute" style="top: 0.5rem; right: 0.5rem">
                                @if (!empty($promotion))
                                    <div class="badge bg-success text-white mr-2">Promotion</div>
                                @endif
                                @if (!empty($inStock))
                                    <div class="badge bg-primary text-white mr-2">In Stock</div>
                                @else
                                    <div class="badge bg-secondary text-white mr-2">Out Of Stock</div>
                                @endif
                            </div>
                            <!-- Product image-->
                            @php
                                $path = (!empty($value->image) and file_exists(public_path('images/products/' . $value->image))) ? asset('images/products/' . $value->image) : null;
                            @endphp
                            <img class="card-img-top" src="{{ asset($path) }}" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product qty to add -->
                                    <div class="container mb-2 mt-0">
                                        <div class="row">
                                            <div class="col-xs-3 col-xs-offset-3">
                                                <div class="input-group number-spinner">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default border-0" data-dir="dwn">
                                                            <i class="fas fa-minus fa-sm"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" id="qty-{{ $value->id }}" data-min-qty="{{ $value->quantity }}" class="form-control text-center border-0 qty-input" value="1">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default border-0" data-dir="up">
                                                            <i class="fas fa-plus fa-sm"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Product name -->
                                    <h5 class="fw-bolder">{{ $value->name }}</h5>
                                    <!-- Product price -->
                                    @if (!empty($promotion))
                                    <p class="text-warning"><del>{{ $value->unit_selling_price}} MMK</del></p>
                                    <p class="text-info">{{ $value->unit_selling_price - $promotion->amount_per_unit }} MMK</p>
                                    @else
                                    <p class="text-info">{{ $value->unit_selling_price }} MMK</p>
                                    @endif
                                    <!-- Product Qty -->
                                    <p id="stock-qty-{{ $value->id }}">In Stock: {{ $value->quantity }}</p>

                                    <!-- Product categoty -->
                                    <a href="{{ route('home', ['category_id' => $value->category->id]) }}">
                                        <span class="badge badge-medium badge-success">
                                            {{ $value->category->name }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <!-- Product actions-->
                            @if (!empty($inStock))    
                                <div class="add-btn card-footer p-4 pt-0 border-top-0 bg-transparent" id="{{ $value->id }}">
                                    <div class="text-center"><button class="btn btn-outline-primary mt-auto">Add To Cart</button></div>
                                </div>
                            @else
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent" id="{{ $value->id }}">
                                    <div class="text-center"><button class="btn btn-outline-secondary mt-auto" disabled>Add To Cart</button></div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </section>
</div>
@stop

@include('notification')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('js')
    <script>
        $('.add-btn').on('click', function () {
            let productId = $(this).attr('id');
            let qty = $(`#qty-${productId}`).val();
            
            let url = "{{ route('add-to-cart') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                dataType : 'json',
                type: 'POST',
                data: JSON.stringify({product_id: productId, qty: qty}),
                contentType: 'application/json',
                processData: false,
                success: function(response) {
                    if (response.status == 'success') {

                        swal("Success", response.message, "success");
                        $('#cart-count').html(response.count);
                        $(`#stock-qty-${response.product_id}`).html(response.qty);
                        $(`qty-${response.product_id}`).val(1);
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

        $(document).on('click', '.number-spinner button', function () {    
            var btn = $(this),
                oldValue = btn.closest('.number-spinner').find('input').val().trim(),
                minQty = btn.closest('.number-spinner').children('.qty-input').attr('data-min-qty'),
                newVal = 0;
            
            if (btn.attr('data-dir') == 'up') {
                if (parseInt(minQty) > parseInt(oldValue)) {
                    newVal = parseInt(oldValue) + 1;
                } else {
                    newVal = oldValue;
                }
            } else {
                if (oldValue > 1) {
                    newVal = parseInt(oldValue) - 1;
                } else {
                    newVal = 1;
                }
            }
            btn.closest('.number-spinner').find('input').val(newVal);
        });
    </script>
@endpush