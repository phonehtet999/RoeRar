@extends('layouts.content-wrapper')

@section('title', 'Create Category')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Category</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Category</h6>
                </div>

                <div class="card-body">
                    <form class="category" action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="name" name="name" value="{{ old('name') ?? '' }}"
                                    required placeholder="Category Name">
    
                                @if ($errors->has('name'))
                                    <span class="text-danger small">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="design" name="design" value="{{ old('design') ?? '' }}"
                                placeholder="Category Design">
    
                                @if ($errors->has('design'))
                                    <span class="text-danger small">{{ $errors->first('design') }}</span>
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
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Create
                        </button>

                        <a href="{{ route('categories.index') }}" class="btn btn-danger btn-user btn-block">
                            Cancel
                        </a>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@stop