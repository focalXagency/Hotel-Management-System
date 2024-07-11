@extends('Admin.layouts.master')
@section('index.rooms')

<style>
    .read-more, .read-less {
        display: inline-block;
        margin-left: 5px;
        font-size: 0.9em;
        color: #007bff;
        cursor: pointer;
    }
    .read-more:hover, .read-less:hover {
        text-decoration: underline;
    }
    .table-image td, .table-image th {
        vertical-align: middle;
        text-align: center;
    }
    .image-item {
        position: relative;
        display: inline-block;
    }

    .image-item img {
        width: 80px;
        height: 60px;
        border-radius: 4px;
        display: block;
        padding:5px;
        float:left;
        object-fit: cover;
    }
</style>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hotel Rooms</h1>
        <div>
            <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> New Room
            </a>
            <a href="{{ route('rooms.ending-in-24-hours') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-clock fa-sm text-white-50"></i> Rooms Expiring in 24 Hours
            </a>
        </div>
    </div>

    <!-- DataTales -->
    <div class="card shadow mb-4">
        @if(session('status'))
            <div class="alert alert-success mb-0">{{ session('status') }}</div>
        @endif

        <div class="card-body">
            <form action="{{ route('rooms.index') }}" method="GET" class="row mb-3 my-1">
                <div class="col-md-3">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Search by Room Type" value="{{ request()->name }}">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-outline-secondary">Filter</button>
                </div>
            </form>

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Filter Rooms by Date <span id="collapseIndicator" style="cursor: pointer;">^</span></h4>
                </div>
                <div class="card-body" id="filterCard">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="filterOptions">Select Filtering Option:</label>
                                <select id="filterOptions" class="form-control">
                                    <option value="availableSpecificDate">Available Rooms on Specific Date</option>
                                    <option value="reservedSpecificDate">Booked Rooms on Specific Date</option>
                                    <option value="availablePeriod">Available Rooms for Period</option>
                                    <option value="reservedPeriod">Reserved Rooms for Period</option>
                                </select>
                            </div>
                            <div id="filterForms"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-bordered table-hover table-image" id="dataTable">
                    <thead>
                        <tr>
                            <th class="col-md-1">#</th>
                            <th class="col-md-1">Room type</th>
                            <th class="col-md-1">Room code</th>
                            <th class="col-md-1">Floor Number</th>
                            <th class="col-md-1">Description</th>
                            <th class="col-md-1">Status</th>
                            <th class="col-md-1">Price</th>
                            <th class="col-md-4">Images</th>
                            <th class="col-md-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $room->roomType->name }}</td>
                            <td><a href='{{ route("rooms.show", $room->id) }}'>{{ $room->code }}</a></td>
                            <td>{{ $room->floorNumber }}</td>
                            <td>
                                <div class="description-cell" id="description-{{ $room->id }}">
                                    {{ Str::limit($room->description, 50) }}
                                    @if (strlen($room->description) > 50)
                                        <span class="read-more" onclick="toggleDescription({{ $room->id }})">Read more</span>
                                    @endif
                                </div>
                                <div class="full-description-cell d-none" id="full-description-{{ $room->id }}">
                                    {{ $room->description }}
                                    <span class="read-less" onclick="toggleDescription({{ $room->id }})">Read less</span>
                                </div>
                            </td>
                            <td>{{ $room->status }}</td>
                            <td>{{ $room->price }}</td>
                            <td>
                                @if ($room->images)
                                    @foreach (json_decode($room->images,true) as $image)
                                    <div class="image-item">
                                        <img src="{{ asset('images/'.$image) }}" alt="{{ $room->code }}" >
                                    </div>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this room?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        
        $('#filterOptions').change(function() {
            var selectedOption = $(this).val();
            var formContent = '';

            if (selectedOption === 'availableSpecificDate') {
                formContent = `
                    <form action="{{ route('rooms.available.specificTime') }}" method="GET">
                        <div class="form-group">
                            <label for="specificDate">Select Date:</label>
                            <input type="date" name="specificDate" id="specificDate" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-outline-secondary">Filter</button>
                    </form>
                `;
        } else if (selectedOption === 'reservedSpecificDate') {
            formContent = `
                    <form action="{{ route('rooms.reserved.specificTime') }}" method="GET">
                        <div class="form-group">
                            <label for="specificDate">Select Date:</label>
                            <input type="date" name="specificDate" id="specificDate" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-outline-secondary">Filter</button>
                    </form>
                `;
        } else if (selectedOption === 'availablePeriod') {
            formContent = `
                    <form action="{{ route('rooms.available.period') }}" method="GET">
                        <div class="form-group">
                            <label for="start_range">Start Date:</label>
                            <input type="date" name="start_range" id="start_range" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="end_range">End Date:</label>
                            <input type="date" name="end_range" id="end_range" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-outline-secondary">Filter</button>
                    </form>
                `;
        } else if (selectedOption === 'reservedPeriod') {
            formContent = `
                    <form action="{{ route('rooms.reserved.period') }}" method="GET">
                        <div class="form-group">
                            <label for="start_range">Start Date:</label>
                            <input type="date" name="start_range" id="start_range" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="end_range">End Date:</label>
                            <input type="date" name="end_range" id="end_range" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-outline-secondary">Filter</button>
                    </form>
                `;
        }
        $('#filterForms').html(formContent);
    });


    $('#collapseIndicator').click(function() {
        $('#filterCard').toggle();
        $(this).text($(this).text() === '^' ? 'v' : '^');
    });
});
</script>
<script>
    function toggleDescription(id) {
        var shortDesc = document.getElementById('description-' + id);
        var fullDesc = document.getElementById('full-description-' + id);
        if (shortDesc.classList.contains('d-none')) {
            shortDesc.classList.remove('d-none');
            fullDesc.classList.add('d-none');
        } else {
            shortDesc.classList.add('d-none');
            fullDesc.classList.remove('d-none');
        }
    }
    </script>
@endsection

