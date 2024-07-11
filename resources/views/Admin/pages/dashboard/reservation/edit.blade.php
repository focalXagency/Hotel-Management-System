@extends('Admin.layouts.master')
@section('edit.reservations')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Reservation</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table">
                <form method="POST" action="{{ route('reservation.update', $reservation->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- User Name Field -->
                    <div class="row mb-3">
                        <label for="user_id" class="col-md-4 col-form-label text-md-end">{{ __('User Name') }}</label>
                        <div class="col-md-6">
                            <select name="user_id" id="user_id"
                                class="form-control @error('user_id') is-invalid @enderror">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $reservation->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Room Field -->
                    <div class="row mb-3">
                        <label for="room_id" class="col-md-4 col-form-label text-md-end">{{ __('Room Code') }}</label>
                        <div class="col-md-6">
                            <select name="room_id" id="room_id"
                                class="form-control @error('room_id') is-invalid @enderror">
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}"
                                        {{ $reservation->room_id == $room->id ? 'selected' : '' }}>
                                        {{ $room->code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Start Date Field -->
                    <div class="row mb-3">
                        <label for="start_date" class="col-md-4 col-form-label text-md-end">{{ __('Start Date') }}</label>
                        <div class="col-md-6">
                            <input type="date" name="start_date" id="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ $reservation->start_date }}">
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- End Date Field -->
                    <div class="row mb-3">
                        <label for="end_date" class="col-md-4 col-form-label text-md-end">{{ __('End Date') }}</label>
                        <div class="col-md-6">
                            <input type="date" name="end_date" id="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ $reservation->end_date }}">
                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Guests Number Field -->
                    <div class="row mb-3">
                        <label for="guestNumber"
                            class="col-md-4 col-form-label text-md-end">{{ __('Number of Guests') }}</label>
                        <div class="col-md-6">
                            <input id="guestNumber" type="text"
                                class="form-control @error('guestNumber') is-invalid @enderror" name="guestNumber"
                                value="{{ $reservation->guestNumber }}" required autocomplete="guestNumber" autofocus>
                            @error('guestNumber')
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
                                {{ __('Update') }}
                            </button>
                            <a class="btn btn-outline-primary" href="{{ route('reservation.index') }}">Back</a>
                        </div>
                    </div>
                </form>
                <br><br><br>
            </div>
        </div>
    </div>
@endsection
