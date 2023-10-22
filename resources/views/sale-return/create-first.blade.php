@extends('layouts.content-wrapper')

@section('title', 'Create Sale Return')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Sale Return</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Sale Return</h6>
                </div>

                <div class="card-body">
                    <form class="sale-return" action="{{ route('sale_returns.create-second') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <label for="sale_id">Choose Sale Id To Return</label>
                                <select name="sale_id" class="form-control select2" id="product-id" required>
                                    <option>Select Sale</option>
                                    @foreach ($sales as $value)
                                        <option value="{{ $value->id }}" {{ old('sale_id') ? 'selected' : '' }}>{{ $value->code }} - ( {{ $value->customer->user->name }}) </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('sale_id'))
                                    <span class="text-danger small">{{ $errors->first('sale_id') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Next
                        </button>

                        <a href="{{ route('sale_returns.index') }}" class="btn btn-danger btn-user btn-block">
                            Cancel
                        </a>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>
@stop

@push('css')
    <style>
        body {
            background: #f0f!important;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush