@extends('Admin.layouts.master')
@section('show.services')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Message Details</h1>
@if ($success = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $success }}</p>
    </div>
@endif

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-body text-muted">
        <div class="table-responsive">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <h5>Contact_id:</h5>
                            <p class="text-primary">{{ $contact->id }}</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <h5>Title:</h5>
                            <p class="text-primary">{{ $message->title }}</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <h5>Message:</h5>
                            <p class="text-primary">{{ $message->body }}</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <h5>Name Contact:</h5>
                            <p class="text-primary">{{ $message->contact->name }}</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <h5>Email Contact:</h5>
                            <p class="text-primary">{{ $message->contact->email }}</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <h5>Phone Contact:</h5>
                            <p class="text-primary">{{ $message->contact->phone }}</p>
                        </div>
                    </div>
                    <div class="pull-right ">
                        <a class="btn btn-outline-primary" href="{{ route('messages.index') }}">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

