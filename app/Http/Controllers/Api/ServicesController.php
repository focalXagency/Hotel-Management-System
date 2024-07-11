<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponserTrait;
use App\Http\Resources\ServiceResource;

class ServicesController extends Controller
{
    use ApiResponserTrait;
    public function index()
    {
        try {
            $services = Service::all();
            return $this->successResponse(ServiceResource::collection($services)->toArray(request()), 'All available services', 200);
        } catch (\Exception $e) {
            Log::error('Error in ServicesController@index: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function show(string $id)
    {
        try {
            $service = Service::find($id);
            if (!$service) {
                return $this->errorResponse('Service does not exist', [], 404);
            }
            return $this->successResponse(['service' => new ServiceResource($service)], 'This is the service you requested', 200);
        } catch (\Exception $e) {
            Log::error('Error in ServicesController@show' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}