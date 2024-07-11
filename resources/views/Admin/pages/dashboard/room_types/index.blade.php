@extends('Admin.layouts.master')
@section('index.roomType')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Room Type</h1>

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
    <a href="{{ route('roomType.create') }}" class="btn btn-primary">New Room Type</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>capacity</th>
                        <th>description</th>
                        <th>service list</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomsType as $roomType)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $roomType->name}}</td>
                        <td>{{ $roomType->price}}</td>
                        <td>{{ $roomType->capacity}}</td>
                        <td>{{ $roomType->description}}</td>
                        <td>
                            @foreach($roomType->services as $service)
                            {{ $loop->iteration }}. {{ $service->name }}<br>
                        @endforeach
                        </td>
                      
                        <td><a href='{{route("roomType.edit", $roomType->id)}}' class="btn btn-outline-success">EDIT</a></td>
                        <td>
                            <form action="{{ route('roomType.destroy', $roomType->id) }}" method="POST">
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
