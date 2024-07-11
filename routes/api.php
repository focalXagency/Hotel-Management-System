<?php


use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RoomTypeContoller;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\V1\ReservationEventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/reservation/events/{reservation}', [ReservationEventController::class, 'reservationEvents']);
        Route::get('/current/available/rooms', [RoomController::class, 'showCurrnetAvailableRooms']);
        Route::get('/available/rooms/specificTime', [RoomController::class, 'showAvailableRoomsInSpecificTime']);
        Route::get('/available/rooms/specificPeriod', [RoomController::class, 'showAvailableRoomsInPeriod']);
        Route::get('/current/reserved/rooms', [RoomController::class, 'showCurrnetReservedRooms']);
        Route::get('/reserved/rooms/specificTime', [RoomController::class, 'showReservedRoomsInSpecificTime']);
        Route::get('/reserved/rooms/specificPeriod', [RoomController::class, 'showReservedRoomsInPeriod']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reservation/MyLastestReservation', [ReservationController::class, 'MyLatestReservation']);
    Route::get('/reservation', [ReservationController::class, 'index']);
    Route::post('/reservation', [ReservationController::class, 'store']);
    Route::put('/reservation/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservation/{id}', [ReservationController::class, 'destroy']);
});
Route::post('/contacts', [ContactController::class, 'store']);
Route::post('/messages', [MessageController::class, 'store']);
Route::get('/services', [ServicesController::class, 'index']);
Route::get('/services/{service}', [ServicesController::class, 'show']);
Route::get('/roomType', [RoomTypeContoller::class, 'index']);
Route::get('/rooms', [RoomController::class, 'index']); // for filter

