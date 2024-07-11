<?php

namespace App\Http\Controllers\Api;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponserTrait;
use App\Http\Resources\RoomTypeResource;

class RoomTypeContoller extends Controller
{
  use ApiResponserTrait;
  public function index()
  {
    try {
      $roomsType = RoomType::with('services')->get();
      $roomsType = RoomTypeResource::collection($roomsType);
      if ($roomsType->isNotEmpty()) {
        return $this->successResponse($roomsType->toArray(request()), 'this is all room types',  200);
      } else
        return $this->notFound('there are not any rooms type!', 404);
    } catch (\Exception $e) {
      Log::error('Error in RoomTypeController@index' . $e->getMessage());
      return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
    }
  }
}
