<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponserTrait;
use App\Http\Traits\ApiReservationTrait;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReservationController extends Controller
{
    use ApiResponserTrait, ApiReservationTrait;

    public function index()
    {
        // return all reservations of the auth user he/she has ever made 
        try {
            $userId = auth('sanctum')->user()->id;
            $reservations = Reservation::where('user_id', $userId)->get();
            $reservationData = ReservationResource::collection($reservations)->toArray(request());
            return $this->successResponse($reservationData, 'All Your reservations', 200);
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while bringing user reservations.');
        }
    }

    public function store(StoreReservationRequest $request)
    {
        try {
            $request->validated();
            $user = auth('sanctum')->user();
            $room = Room::findOrFail($request->room_id);
            return $this->ReservationHandle($user, $room, $request);   // all the logic of handling all senatrios of the request will be handled there and if no issues then it would make new reservation
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Room not found.', [], 404);
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while creating the reservation.');
        }
    }

    public function show(string $id)
    {
       
    }

    public function MyLatestReservation()
    { 
        //returns the lastest reservation the auth user has made 
        try {
            $userId = auth('sanctum')->user()->id;
            $latestReservation = Reservation::where('user_id', $userId)->orderByDesc('created_at')->first();
            if ($latestReservation) {
                $reservationData = new ReservationResource($latestReservation);
                return $this->successResponse($reservationData->toArray(request()), 'Latest Reservation', 200);
            } else {
                return $this->errorResponse('No reservation found for the user.', [], 404);
            }
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while fetching the latest reservation.');
        }
    }

    public function update(Request $request, string $id)
    {
        // allowing the user to update the reservation if the reservation hasn't passed 24h since it was made 
        try {
            $user = auth('sanctum')->user();
            $reservation = Reservation::where('id', $id)->first();
            $this->validateReservationUpdate($reservation);
            $room = Room::findOrFail($request->room_id);
            return $this->ReservationHandle($user, $room, $request, $reservation);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Reservation or room not found.', [], 404);
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while updating the reservation.');
        }
    }
    
    public function destroy(string $id)
    {
        // allowing the user to delete the reservation if the reservation hasn't passed 24h since it was made 
        try {
            $user = auth('sanctum')->user();
            $reservation = Reservation::where('user_id', $user->id)->where('id', $id)->firstOrFail();
            $this->validateReservationUpdate($reservation);
            $reservation->delete();
            return $this->successResponse([], 'Reservation deleted successfully.', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Reservation not found.', [], 404);
        } catch (\Exception $e) {
            return $this->handleException($e, 'An error occurred while deleting the reservation.');
        }
    }

    private function handleException(\Exception $e, string $message)
    {
        $errorMessage = "Error in " . __METHOD__ . ": " . $e->getMessage();
        Log::error($errorMessage);
        $errorData = [
            'exception_class' => get_class($e),
            'exception_message' => $e->getMessage(),
        ];
        return $this->errorResponse($message, $errorData, 500);
    }

    private function validateReservationUpdate($reservation)
    {
        $createdAt = Carbon::parse($reservation->created_at);
        $now = Carbon::now();
        $timeDifference = $now->diffInHours($createdAt);

        if ($timeDifference >= 24) {
            throw new \Exception('Sorry, you cannot update or delete your reservation after 24 hours.');
        }
    }
}
