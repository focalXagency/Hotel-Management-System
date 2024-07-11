@extends('Admin.layouts.master')
@section('index.guests')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Guests List</h1>
@if(session('status'))
<div class="alert alert-success">{{ session('status') }}</div>
@endif

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('guests.create') }}" class="btn btn-primary">Add Guest</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Birth Date</th>
                        <th>Phone Number</th>
                        <th>Identification Number</th>
                        <th>Reservations</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guests as $guest)
                    <tr>
                        <td>{{ $guest->name }}</td>
                        <td>{{ $guest->birthDate }}</td>
                        <td>{{ $guest->phone_number }}</td>
                        <td>{{ $guest->identificationNumber }}</td>
                        <td>Associated with <strong>{{ $guest->reservations_count }}</strong> reservations</td>
                        <td>
                            <a href="{{ route('guests.edit', $guest->id) }}" class="btn btn-outline-success">EDIT</a>
                            <form action="{{ route('guests.destroy', $guest->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
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
