@extends('layouts.content-wrapper')

@section('title', 'Create Staff')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Staff</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Staff</h6>
                </div>

                <div class="card-body">
                    <form class="supplier" action="{{ route('staffs.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="name" name="name" value="{{ old('name') ?? '' }}"
                                    required autocomplete="off" placeholder="User Name">
    
                                @if ($errors->has('name'))
                                    <span class="text-danger small">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="email" name="email" value="{{ old('email') ?? '' }}"
                                required autocomplete="off" placeholder="Email">
    
                                @if ($errors->has('email'))
                                    <span class="text-danger small">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="position" name="position" value="{{ old('position') ?? '' }}"
                                required autocomplete="off" placeholder="Position">
    
                                @if ($errors->has('position'))
                                    <span class="text-danger small">{{ $errors->first('position') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="text" class="form-control text-dark" id="phone_number" name="phone_number" value="{{ old('phone_number') ?? '' }}"
                                required autocomplete="off" placeholder="Phone Number">
    
                                @if ($errors->has('phone_number'))
                                    <span class="text-danger small">{{ $errors->first('phone_number') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="number" class="form-control text-dark" id="salary" name="salary" value="{{ old('salary') ?? '' }}"
                                required autocomplete="off" placeholder="Salary" min="0">
    
                                @if ($errors->has('salary'))
                                    <span class="text-danger small">{{ $errors->first('salary') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user"
                                    id="password" name="password" required autocomplete="off" placeholder="Password">

                                @if ($errors->has('password'))
                                    <span class="text-danger small">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="password" class="form-control form-control-user"
                                    id="password_confirmation" name="password_confirmation" required autocomplete="off" placeholder="Repeat Password">

                                @if ($errors->has('password_confirmation'))
                                    <span class="text-danger small">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <textarea class="form-control text-dark" id="address" name="address" value="{{ old('address') ?? '' }}"
                                required autocomplete="off" placeholder="Address"></textarea>
    
                                @if ($errors->has('address'))
                                    <span class="text-danger small">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Create
                        </button>

                        <a href="{{ route('staffs.index') }}" class="btn btn-danger btn-user btn-block">
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
    $(document).ready(function() {
        $('#password').val('');
        $('#name').val('');
    });
</script>
@endpush