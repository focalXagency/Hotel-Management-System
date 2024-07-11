@extends('Admin.layouts.master')
@section('show.services')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Room Service Details</h1>

<!-- Details -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ $service->name }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <img src="{{ asset('images/' . $service->img) }}" alt="{{ $service->name }}" class="img-fluid">
            </div>
            <div class="col-md-8">
                <p><strong>Name:</strong> {{ $service->name }}</p>
                <p><strong>Price:</strong> {{ $service->price }}</p>
                <p><strong>Description:</strong> {{ $service->description }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
