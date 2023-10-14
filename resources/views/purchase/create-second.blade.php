@extends('layouts.content-wrapper')

@section('title', 'Create Purchase')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Purchase</h1>
    </div>

    <div class="row">

        <div class="col-lg-8 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Purchase</h6>
                </div>

                <div class="card-body">
                    <form class="purchase" action="{{ route('purchases.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label>Invoice Code</label>
                                <input type="text" class="form-control text-dark" id="invoice_number" name="invoice_number" value="{{ getNextInvoiceCode() }}" readonly>
                            </div>

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label>Product Code</label>
                                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                                <input type="text" class="form-control text-dark" value="{{ $product->code . ' - ' . $product->name }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label>Supplier To Purchase</label>
                                <input type="hidden" id="supplier_id" name="supplier_id" value="{{ $supplier->id }}">
                                <input type="text" class="form-control text-dark" value="{{ $supplier->user->name . ' ( ' . $supplier->phone_number . ' )'}}" disabled>
                            </div>
                            
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label>Current Quantity</label>
                                <input type="text" class="form-control text-dark" value="{{ $product->quantity }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label>Unit Selling Price of Product</label>
                                <input type="text" class="form-control text-dark" value="{{ $product->unit_selling_price }}" disabled>
                            </div>
                            
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label>Unit Buying Price of Product</label>
                                <input type="text" class="form-control text-dark" value="{{ $product->unit_buying_price }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="unit-selling-price" name="unit_selling_price" value="{{ old('unit_selling_price') ?? '' }}"
                                    required placeholder="Unit Selling Price" min="0">
    
                                @if ($errors->has('unit_selling_price'))
                                    <span class="text-danger small">{{ $errors->first('unit_selling_price') }}</span>
                                @endif
                            </div>
                            
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="unit-buying-price" name="unit_buying_price" value="{{ old('unit_buying_price') ?? '' }}"
                                    required placeholder="Unit Buying Price" min="0">
    
                                @if ($errors->has('unit_buying_price'))
                                    <span class="text-danger small">{{ $errors->first('unit_buying_price') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="quantity" name="quantity" value="{{ old('quantity') ?? '' }}"
                                    required placeholder="Quantity" min="0">
    
                                @if ($errors->has('quantity'))
                                    <span class="text-danger small">{{ $errors->first('quantity') }}</span>
                                @endif
                            </div>

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="payment-type" name="payment_type" value="{{ old('payment_type') ?? '' }}"
                                    required placeholder="Payment Type">
    
                                @if ($errors->has('payment_type'))
                                    <span class="text-danger small">{{ $errors->first('payment_type') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <textarea class="form-control text-dark" id="description" name="description" value="{{ old('description') ?? '' }}"
                                placeholder="Description"></textarea>
    
                                @if ($errors->has('description'))
                                    <span class="text-danger small">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0 text-success">
                                <label><b>Total Amount of Purchase</b></label>
                                <input type="text" id="purchase-amount" class="form-control text-success" value="0" disabled>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Create
                        </button>

                        <a href="{{ route('purchases.index') }}" class="btn btn-danger btn-user btn-block">
                            Cancel
                        </a>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@stop

@push('js')
    <script>
        var totalAmount = $('#purchase-amount');
        $('#quantity').on('input', function () {
            var unitBuyingPrice = $('#unit-buying-price').val() ?? 0;
            totalAmount.val(unitBuyingPrice * $(this).val());
        });

        $('#unit-buying-price').on('input', function () {
            var quantity = $('#quantity').val() ?? 0;
            totalAmount.val(quantity * $(this).val());
        });
    </script>
@endpush