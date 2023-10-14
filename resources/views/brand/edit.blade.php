@extends('layouts.content-wrapper')

@section('title', 'Edit Brand')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Brand</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Brand</h6>
                </div>

                <div class="card-body">
                    <form class="brand" action="{{ route('brands.update', $brand->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? ( $brand->name ?? '') }}"
                                    required placeholder="Brand Name">
    
                                @if ($errors->has('name'))
                                    <span class="text-danger small">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <select name="supplier_id" class="form-control" id="supplier-id" required>
                                    <option>Select Supplier</option>
                                    @php
                                        $oldSupplerId = old('supplier_id') ?? $brand->supplier_id;
                                    @endphp
                                    @foreach ($suppliers as $value)
                                        <option value="{{ $value->id }}" {{ $value->id == $oldSupplerId ? 'selected' : ''}}>{{ $value->user->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('supplier_id'))
                                    <span class="text-danger small">{{ $errors->first('supplier_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="Description">{{ old('description') ?? ($brand->description ?? '') }}</textarea>
    
                                @if ($errors->has('description'))
                                    <span class="text-danger small">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Update
                        </button>

                        <a href="{{ route('brands.index') }}" class="btn btn-danger btn-user btn-block">
                            Cancel
                        </a>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@stop