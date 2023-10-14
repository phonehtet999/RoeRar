@extends('layouts.content-wrapper')

@section('title', 'User Profile')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
    </div>

    <div class="row">

        <div class="col-lg-6 mx-auto">

            <!-- Circle Buttons -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Profile</h6>
                </div>

                <div class="card-body">
                    <form class="user" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            @php
                                $path = $user->image ? asset("images/profiles/{$user->image}") : asset('images/profiles/Dog.jpg');
                            @endphp
                            <!-- Thumbnail -->
                            <center class="mb-4">
                                <div id="thumbnail-div">
                                    <a href="{{ $path }}">
                                        <img src="{{ $path}}"
                                        id="thumbnail" alt="product image" class="image-border-thumbnail col-sm-6 d-block mb-1 rounded-circle" style="object-fit: cover;">
                                    </a>
                                </div>
                            </center>
    
                            <div>
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="image" accept="image/*,.png,.jpg,.gif">
                                    <label class="custom-file-label" for="image">Choose Profile Picture</label>
                                </div>
                            </div>
    
                            @if ($errors->has('image'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('image') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <label for="name">User Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? ( $user->name ?? '') }}"
                                    required placeholder="User Name">
    
                                @if ($errors->has('name'))
                                    <span class="text-danger small">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <label for="email">Email</label>
                                <input type="email" class="form-control text-dark" id="email" name="email" value="{{ old('email') ?? ($user->email ?? '') }}"
                                required autocomplete="off" placeholder="">
    
                                @if ($errors->has('email'))
                                    <span class="text-danger small">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') ?? ( $user->$userType->phone_number ?? '') }}"
                                    required placeholder="">
    
                                @if ($errors->has('phone_number'))
                                    <span class="text-danger small">{{ $errors->first('phone_number') }}</span>
                                @endif
                            </div>
                        </div>

                        @if ($userType == 'staff')
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" value="{{ old('position') ?? ( $user->$userType->position ?? '') }}"
                                        required placeholder="">
        
                                    @if ($errors->has('position'))
                                        <span class="text-danger small">{{ $errors->first('position') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <label for="address">Address</label>
                                <textarea class="form-control text-dark" id="address" name="address"
                                required autocomplete="off">{{ old('address') ?? ($user->$userType->address ?? '') }}</textarea>
    
                                @if ($errors->has('address'))
                                    <span class="text-danger small">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Update
                        </button>

                        <a href="{{ route('home') }}" class="btn btn-danger btn-user btn-block">
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