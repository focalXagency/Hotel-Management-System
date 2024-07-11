@extends('Admin.layouts.master')
@section('create.roomType')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach 
    </ul>
</div>
@endif 

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Create a New Room Type</h1>

<!-- Form -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <form method="POST" action="{{ route('roomType.store') }}" >
                @csrf

                <div class="row mb-3">
                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price') }}</label>
                    <div class="col-md-6">
                        <input id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>
                        @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="capacity" class="col-md-4 col-form-label text-md-end">{{ __('Capacity') }}</label>
                    <div class="col-md-6">
                        <input id="capacity" type="number" step="0.01" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{ old('capacity') }}" required>
                        @error('capacity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                    <div class="col-md-6">
                        <input id="description" type="text" step="0.01" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="service" class="col-md-4 col-form-label text-md-end">{{ __('Service') }}</label>
                    <div class="col-md-6">
                        <select id="service"  step="0.01" class="form-control @error('service') is-invalid @enderror" name="service_id[]"  required multiple>
                            @foreach ($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                            @endforeach
                        </select>    
                        @error('service')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

             

                

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }}
                        </button>
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