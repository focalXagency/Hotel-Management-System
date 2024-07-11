<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Traits\ApiResponserTrait;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    use ApiResponserTrait;
    public function store(StoreMessageRequest $request)
    {
        try {
            $contact = Contact::where('email', $request->email)->first();

            if (!$contact) {
                return $this->errorResponse('Contact not found.', [], 404);
            }

            $message = new Message();
            $message->contact_id = $contact->id;
            $message->title = $request->title;
            $message->body = $request->body;
            $message->save();
            $messageResource = new MessageResource($message);
            return $this->successResponse($messageResource->toArray($request), 'Message created successfully.', 201);
        } catch (\Exception $e) {
            Log::error('Error in MessageController@store: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}

