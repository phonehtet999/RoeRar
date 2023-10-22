@extends('layouts.content-wrapper')

@section('title', 'Update Promotion')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Update Promotion</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Promotion</h6>
                </div>

                <div class="card-body">
                    <form class="promotion" action="{{ route('promotions.update', $promotion->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <select name="product_id" class="form-control select2" id="product-id" required>
                                    <option>Select Product</option>
                                    @php
                                        $oldProductId = old('product_id') ?? ($promotion->product_id ?? '');
                                    @endphp
                                    @foreach ($products as $value)
                                        <option value="{{ $value->id }}" {{ $oldProductId ? 'selected' : '' }}>{{ $value->code }} - ( {{ $value->name }}) </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('product_id'))
                                    <span class="text-danger small">{{ $errors->first('product_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="amount" name="amount_per_unit" value="{{ old('name') ?? ($promotion->amount_per_unit ?? '') }}"
                                    required min="0" placeholder="Amount Per Unit">
    
                                @if ($errors->has('amount_per_unit'))
                                    <span class="text-danger small">{{ $errors->first('amount_per_unit') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="total_quantity" name="total_quantity" value="{{ old('name') ?? ($promotion->total_quantity ?? '') }}"
                                    required min="0" placeholder="Total Quantity">
    
                                @if ($errors->has('total_quantity'))
                                    <span class="text-danger small">{{ $errors->first('total_quantity') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                @php
                                    $oldStatus = old('status') ?? ($promotion->status == 1 ? '1' : '0');
                                @endphp
                                <select name="status" class="form-control" id="status" required>
                                    <option value="1" {{ $oldStatus == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $oldStatus == '0' ? 'selected' : '' }}>In Active</option>
                                </select>

                                @if ($errors->has('status'))
                                    <span class="text-danger small">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Update
                        </button>

                        <a href="{{ route('promotions.index') }}" class="btn btn-danger btn-user btn-block">
                            Cancel
                        </a>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@stop