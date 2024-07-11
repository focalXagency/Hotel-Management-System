@extends('Admin.layouts.master')
@section('edit.users')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit User</h1>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table">
                <form method="POST" action="{{ route('users.update', $user->id)}}" enctype="multipart/form-data">
                    @csrf

                    <!-- Name Field -->
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{$user->name}}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="row mb-3">
                        <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="email" step="0.01"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{$user->name}}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                        <div class="col-md-6">
                            <input id="password" type="password" step="0.01"
                                class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password"
                                value="" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Save Button -->
                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                            <a class="btn btn-outline-primary" href="{{ route('users.index') }}">Back</a>
                        </div>
                    </div>

                </form>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>
@endsection
