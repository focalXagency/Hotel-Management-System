@extends('Admin.layouts.master')
@section('ending_soon.rooms')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Rooms that expire after 24 hours</h1>

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
    {{-- <a href="{{ route('roomType.create') }}" class="btn btn-primary">New Room Type</a> --}}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>floorNumber</th>
                        <th>Price</th>
                        
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $room->code}}</td>
                        <td>{{ $room->floorNumber}}</td>
                        <td>{{ $room->price}}</td>
                      
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
