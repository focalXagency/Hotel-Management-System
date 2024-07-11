@extends('Admin.layouts.master')
@section('index.messages')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Messages</h1>   
@if ($success = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $success }}</p>
    </div>
@endif

<!-- DataTales -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('messages.index') }}" method="GET">
            <div class="row mb-3 my-1">
                <div class="col-md-3">
                    <input type="text" name="contact_id" class="form-control" placeholder="Search by contact_id" value="{{ request()->contact_id }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="title" class="form-control" placeholder="Search by title" value="{{ request()->title }}">
                </div>
                <div >
                    <button type="submit" class="btn btn-outline-secondary">Filter</button>
                </div>
            </div>
        </form> 
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Contact_id</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $message->contact_id}}</td>
                        <td>{{ $message->title}}</td>
                        <td>{{ $message->body}}</td>
                        <td>{{ $message->status}}</td>
                        <td class="d-flex">
                            <a href='{{route("messages.edit", $message->id)}}' class="btn btn-outline-success mx-2">EDIT</a>
                            <a href='{{route("messages.show", $message->id)}}' class="btn btn-outline-primary mx-2">SHOW</a>
                            <form action="{{ route('messages.destroy', $message->id) }}" method="POST">
                                @csrf
                                @method ('DELETE')
                                <button type="submit" class="btn btn-outline-danger mx-2">DELETE</button>
                            </form>
                            <a href='mailto:{{ $message->contact->email }}' class="btn btn-outline-info mx-2">REPLY</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection