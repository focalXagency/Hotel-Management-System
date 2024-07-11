@extends('Admin.layouts.master')
@section('index.services')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Room Services</h1>

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

<!-- DataTable -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="mx-2">
            <a href="{{ route('services.create') }}" class="btn btn-primary">New Service</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <!-- Table Head -->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody>
                    @foreach($services as $service)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $service->name}}</td>
                        <td>{{ $service->price}}</td>
                        <td>{{ $service->description}}</td>
                        <td><img src="{{ asset('images/' . $service->img) }}" alt="{{ $service->name }}" style="max-width: 100px;"></td>
                        <td><a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-dark">SHOW</a></td>
                        <td><a href='{{route("services.edit", $service->id)}}' class="btn btn-outline-success">EDIT</a></td>
                        <td>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST">
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