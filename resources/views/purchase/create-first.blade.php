@extends('layouts.content-wrapper')

@section('title', 'Create Purchase')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Purchase</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Purchase</h6>
                </div>

                <div class="card-body">
                    <form class="purchase" action="{{ route('purchases.create-second') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <label for="product_id">Choose Product To Purchase</label>
                                <select name="product_id" class="form-control" id="product-id" required>
                                    <option>Select Product</option>
                                    @foreach ($products as $value)
                                        <option value="{{ $value->id }}" {{ old('product_id') ? 'selected' : '' }}>{{ $value->code }} - ( {{ $value->name }}) </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('product_id'))
                                    <span class="text-danger small">{{ $errors->first('product_id') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Next
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