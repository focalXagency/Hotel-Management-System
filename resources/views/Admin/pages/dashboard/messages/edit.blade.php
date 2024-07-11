@extends('Admin.layouts.master')
@section('edit.messages')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Make it done</h1>

<!-- Form -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <form method="POST" action="{{ route('messages.update', $message->id) }}" enctype="multipart/form-data" class="mb-5">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label for="status" class="col-md-4 col-form-label text-md-end" style="font-size: 30px;">{{ __('Status') }}</label>
                    <div class="col-md-6">
                        <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="Done" {{ $message->status == 'Done' ? 'selected' : '' }}>Done</option>
                            <option value="Unread" {{ $message->status == 'Unread' ? 'selected' : '' }}>Unread</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-outline-success">
                            {{ __('Save') }}
                        </button>
                        <a class="btn btn-outline-primary" href="{{ route('messages.index') }}">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection