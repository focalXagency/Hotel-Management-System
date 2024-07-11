@extends('Admin.layouts.master')
@section('dashboardContent')
<div class="container">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card card-custom">
                <div class="card-header card-header-custom">Room Occupancy</div>
                <div class="card-body card-body-custom">
                    <i class="icon fas fa-bed"></i>
                    <div class="stat">
                        <p class="stat-number" id="occupiedRooms">{{ $occupiedRooms }}</p>
                        <p class="stat-label">Occupied Rooms</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-custom">
                <div class="card-header card-header-custom">Daily Revenue</div>
                <div class="card-body card-body-custom">
                    <i class="icon fas fa-dollar-sign"></i>
                    <div class="stat">
                        <p class="stat-number" id="dailyRevenue">${{ number_format($dailyRevenue, 2) }}</p>
                        <p class="stat-label">Revenue Today</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-custom">
                <div class="card-header card-header-custom">ِAvaliable Rooms</div>
                <div class="card-body card-body-custom">
                    <i class="icon fas fa-door-open"></i>
                    <div class="stat">
                        <p class="stat-number" id="availableRooms">{{ $availableRooms }}</p>
                        <p class="stat-label">Available Rooms</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-custom">
                <div class="card-header card-header-custom"> Rooms under Maintenance</div>
                <div class="card-body card-body-custom">
                    <i class="icon fas fa-tools"></i>
                    <div class="stat">
                        <p class="stat-number" id="maintenanceRooms"> {{ $maintenanceRooms }}</p>
                        <p class="stat-label">Rooms under Maintenance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card card-custom">
                <div class="card-header card-header-custom">Upcoming Reservations</div>
                <div class="card-body card-body-custom">
                    <ul id="upcomingReservations" class="list-unstyled">
                            @foreach($upcomingReservations as $reservation)
                                <li> <strong>user:</strong> {{ $reservation->user->name }} <strong>- Check-in:</strong> {{ $reservation->start_date }} <strong>- Room:</strong> {{ $reservation->room->code }}</li>
                            @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card card-custom">
                <div class="card-header card-header-custom">Current Guests</div>
                <div class="card-body card-body-custom">
                    <ul id="currentGuests" class="list-unstyled">
                        @foreach($currentGuestsWithRoom as $entry)
                            <li><strong>{{ $entry['guest']}}</strong> in Room <strong>{{$entry['room']}}</strong></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection