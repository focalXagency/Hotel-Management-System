<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Traits\UploadImageTrait;
use App\Http\Requests\StoreServicesRequest;
use App\Http\Requests\UpdateServicesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $services = Service::all();
            return view('Admin.pages.dashboard.services.index', compact('services'));
        } catch (\Exception $e) {
            Log::error('Error in ServicesController@index: ' . $e->getMessage());
            return redirect()->route('services.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.pages.dashboard.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServicesRequest $request)
    {
        try {
            $request->validated();
            $path = $this->storeImage($request->file('img'), 'services');

            if ($path) {
                Service::create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'description' => $request->description,
                    'img' => $path,
                ]);
                return redirect()->route('services.index')->with('success', 'Service created successfully!');
            }
            return redirect()->back()->with('error', 'Failed!. Image was not stored');
        } catch (\Exception $e) {
            Log::error('Error in ServicesController@store: ' . $e->getMessage());
            return redirect()->route('services.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('Admin.pages.dashboard.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('Admin.pages.dashboard.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServicesRequest $request, Service $service)
    {
        try {
            $request->validated();

            $service->name = $request->name;
            $service->price = $request->price;
            $service->description = $request->description;

            if ($request->hasFile('img')) {
                $path = $this->storeImage($request->file('img'), 'services');

                if ($path) {
                    $this->deleteImage($service->img);
                    $service->img = $path;
                } else {
                    return redirect()->back()->with('error', 'Failed to upload image.');
                }
            }
            $service->save();
            return redirect()->route('services.index')->with('success', 'Service updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error in ServicesController@update: ' . $e->getMessage());
            return redirect()->route('services.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            $this->deleteImage($service->img);
            $service->delete();

            return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error in ServicesController@destroy: ' . $e->getMessage());
            return redirect()->route('services.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
