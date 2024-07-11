@extends('Admin.layouts.master')
@section('edit.guests')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Guest <a href="{{route('guests.index')}}" class="btn btn-outline-danger" style="float:right;">X</a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('guests.update', $guest->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $guest->name }}" >
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="birthDate">Birth Date</label>
                                <input type="date" class="form-control @error('birthDate') is-invalid @enderror" id="birthDate" name="birthDate" value="{{ $guest->birthDate }}" >
                                @error('birthDate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ $guest->phone_number }}">
                                @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="identificationNumber">Identification Number</label>
                                <input type="text" class="form-control @error('identificationNumber') is-invalid @enderror" id="identificationNumber" name="identificationNumber" value="{{ $guest->identificationNumber }}">
                                @error('identificationNumber')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="reservations">Reservations</label>
                                <select name="reservations[]" id="reservations" class="form-control @error('reservations') is-invalid @enderror" multiple>
                                    @if(isset($guest) && $guest->reservations)
                                        @foreach ($guest->reservations as $reservation)
                                            <option value="{{ $reservation->id }}" selected>{{ $reservation->code }} in {{ $reservation->start_date }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('reservations')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#reservations').select2({
                ajax: {
                    url: '{{ route('reservations.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term // The search query is sent as 'q'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(item) {
                                return { id: item.id, text: item.code + ' in ' + item.start_date };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Search for a reservation',
                allowClear: true,
            });
        });
    </script>
@endsection