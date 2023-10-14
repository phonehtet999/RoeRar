@extends('layouts.content-wrapper')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Product</h6>
                </div>

                <div class="card-body">
                    <form class="product" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                @php
                                    $prdCode = getNextProductCode();
                                @endphp
                                <input type="text" class="form-control text-dark" id="code" name="code" value="{{ $prdCode }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- Thumbnail -->
                            @php
                                $path = $product->image ? asset("images/products/{$product->image}") : '';
                            @endphp
                            <div id="thumbnail-div">
                                <a target="_blank" href="#">
                                    <img src="{{ $path }}" id="thumbnail" alt="product image" class="image-border-thumbnail col-sm-6 d-block mb-1">
                                </a>
                            </div>
    
                            <div>
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="image" accept="image/*,.png,.jpg,.gif">
                                    <label class="custom-file-label" for="image">Choose Product Image</label>
                                </div>
                            </div>
    
                            @if ($errors->has('image'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('image') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="name" name="name" value="{{ old('name') ?? ($product->name ?? '') }}"
                                    required placeholder="Product Name">
    
                                @if ($errors->has('name'))
                                    <span class="text-danger small">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <select name="category_id" class="form-control" id="category-id" required>
                                    <option>Select Category</option>
                                    @php
                                        $oldCategory = old('category_id') ?? ($product->category_id ?? '');
                                    @endphp
                                    @foreach ($categories as $value)
                                        <option value="{{ $value->id }}" {{ $oldCategory ? 'selected' : '' }}>{{ $value->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('category_id'))
                                    <span class="text-danger small">{{ $errors->first('category_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <select name="brand_id" class="form-control" id="category-id" required>
                                    <option>Select Brand</option>
                                    @php
                                        $oldBrand = old('brand_id') ?? ($product->brand_id ?? '');
                                    @endphp
                                    @foreach ($brands as $value)
                                        <option value="{{ $value->id }}" {{ $oldBrand ? 'selected' : '' }}>{{ $value->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('brand_id'))
                                    <span class="text-danger small">{{ $errors->first('brand_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="unit-selling-price" name="unit_selling_price" value="{{ old('unit_selling_price') ?? ($product->unit_selling_price ?? '') }}"
                                    required placeholder="Unit Selling Price" min="0">
    
                                @if ($errors->has('unit_selling_price'))
                                    <span class="text-danger small">{{ $errors->first('unit_selling_price') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="unit-buying-price" name="unit_buying_price" value="{{ old('unit_buying_price') ?? ($product->unit_buying_price ?? '') }}"
                                    required placeholder="Unit Buying Price" min="0">
    
                                @if ($errors->has('unit_buying_price'))
                                    <span class="text-danger small">{{ $errors->first('unit_buying_price') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="minimum_required_quantity" name="minimum_required_quantity" value="{{ old('minimum_required_quantity') ?? ($product->minimum_required_quantity ?? '') }}"
                                    required placeholder="Minimum Required Quantity" min="0">
    
                                @if ($errors->has('minimum_required_quantity'))
                                    <span class="text-danger small">{{ $errors->first('minimum_required_quantity') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <select name="status" class="form-control" id="status" required>
                                    <option value="in_stock" {{ $product->status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="out_of_stock" {{ $product->status == 'out_of_stock' ? 'selected' : '' }}>Out Of Stock</option>
                                </select>

                                @if ($errors->has('status'))
                                    <span class="text-danger small">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2 mb-3 mb-sm-0">
                                <input type="color" class="form-control form-control-color" id="color" name="color" value="{{ old('color') ?? ($product->color ?? '#ff0000') }}" title="Choose your color" required>
                            </div>
                            <label for="color" class="form-label col-form-label">Choose Color</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Update
                        </button>

                        <a href="{{ route('products.index') }}" class="btn btn-danger btn-user btn-block">
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
    $('#image').change(function () {
      let reader = new FileReader();
 
      reader.onload = (e) => {
        $('#thumbnail-div').show();
        $('#thumbnail').attr('src', e.target.result); 
      }

      reader.readAsDataURL(this.files[0]);
    });
</script>
@endpush