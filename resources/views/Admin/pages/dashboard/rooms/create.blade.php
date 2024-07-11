@extends('Admin.layouts.master')
@section('create.rooms')
    <!-- Page Heading -->
    <div class="row mb-2 ">
        <div class="col-md-6">
        <h1 class="h3 text-gray-800">Create a New Room</h1>
        </div>
        <div class="col-md-2 offset-4">
        <button type="submit" form="createform" class="btn btn-primary m-2">
            {{ __('Save') }}
        </button> 
        <a role="button" href="{{route('rooms.index')}}" class="btn btn-outline-danger my-2">X</a>
        </div>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div>
                <form method="POST" id="createform" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Room Code Field -->
                    <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="code" class="form-label">{{ __('Code') }}</label>
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                    name="code" value="{{ old('code') }}"  autocomplete="code" autofocus>
                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
                    <!-- Floor Field -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="floorNumber" class="form-label">FloorNumber</label>
                            <select name="floorNumber" id="floorNumber" class="form-control @error('floorNumber') is-invalid @enderror">
                                <option value=""> -- Select Floor --</option>
                                <option value="0" {{ old('floorNumber') === 0 ? 'selected' : '' }}>Ground</option>
                                @for ($floor=0;$floor<16;$floor++)
                                    <option value="{{ $floor }}"  {{ (old('floorNumber'))? "selected":"" }}>{{ $floor }}</option>
                                @endfor 
                            </select>
                            @error('floorNumber')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <!-- Room Type Field -->
                        <div class="col-md-4">
                            <label for="room_type" class="form-label">{{ __('Room Type') }}</label>
                            <select name="room_type" id="room_type"
                                class="form-control @error('room_type') is-invalid @enderror"
                                name="name">{{ old('status') }}
                                @foreach ($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!--Room Status Field -->
                        <div class="col-md-2 ">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>UnAvailable</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="description"
                            class="form-label">{{ __('Description') }}</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" value="{{old('description')}}"></textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- images Field -->
                        <div class="col-md-6">
                            <label for="img" class="form-label">{{ __('Images') }}</label>
                            <input id="img" type="file" class="form-control @error('images') is-invalid @enderror"
                                name="images[]" multiple>
                            @error('images')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
