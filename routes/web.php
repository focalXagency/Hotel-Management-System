<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\RoomController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\Admin\adminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationStatusEventController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/dashboard', [adminController::class, 'index'])->name('dashboard');
    Route::resource('/services', ServicesController::class);
    Route::resource('/messages', MessageController::class);
    Route::resource('/rooms', RoomController::class);
    Route::resource('/roomType', RoomTypeController::class);
    Route::resource('/reservation', ReservationController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/guests', GuestController::class);

    Route::get('/current/bookings/room/Guests',[RoomController::class,'showCurrnetOccupiedRoomsWithguests'])->name('today.Bookings.Rooms.guests');
    Route::get('/current/available/rooms',[RoomController::class,'showCurrnetAvailableRooms'])->name('rooms.available.current');
    Route::get('/available/rooms/specificTime',[RoomController::class,'showAvailableRoomsInSpecificTime'])->name('rooms.available.specificTime');
    Route::get('/available/rooms/specificPeriod',[RoomController::class,'showAvailableRoomsInPeriod'])->name('rooms.available.period');
    Route::get('/current/reserved/rooms',[RoomController::class,'showCurrnetReservedRooms'])->name('rooms.reserved.current');
    Route::get('/reserved/rooms/specificTime',[RoomController::class,'showReservedRoomsInSpecificTime'])->name('rooms.reserved.specificTime');
    Route::get('/reserved/rooms/specificPeriod',[RoomController::class,'showReservedRoomsInPeriod'])->name('rooms.reserved.period');
    Route::get('/ending-in-24-hours', [RoomController::class, 'roomsEndingIn24Hours'])->name('rooms.ending-in-24-hours');
    Route::get('reservations/search', [ReservationController::class, 'search'])->name('reservations.search');
});
