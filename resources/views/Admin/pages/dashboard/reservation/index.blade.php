@extends('Admin.layouts.master')
@section('index.rooms')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Reservations List</h1>
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
<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('reservation.create') }}" class="btn btn-primary">New Reservation</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Room ID</th>
                        <th>Reservation Code</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Number of guests</th>
                        <th>Total Price</th>
                        <th>Show</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $reservation->user_id}}</td>
                        <td>{{ $reservation->room_id}}</td>
                        <td>{{ $reservation->code}}</td>
                        <td>{{ $reservation->start_date}}</td>
                        <td>{{ $reservation->end_date}}</td>
                        <td>{{ $reservation->guestNumber}}</td>
                        <td>{{ $reservation->totalPrice}}</td>
                        <td><a href="{{ route('reservation.show', $reservation->id) }}" class="btn btn-outline-dark">DETAILS</a></td>
                        <td><a href='{{route("reservation.edit", $reservation->id)}}' class="btn btn-outline-success">EDIT</a></td>
                        <td>
                            <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST">
                                @csrf
                                @method ('DELETE')
                                <button type="submit" class="btn btn-outline-danger">DELETE</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection