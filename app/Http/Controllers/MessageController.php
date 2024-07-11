<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        try {
            // dd($request->all()); 
            $query = Message::query();
            if ($request->has('contact_id') && $request->contact_id != '') {
                $query->where('contact_id', $request->contact_id);
            }
            if ($request->has('title') && $request->title != '') {
                $query->where('title', 'like', '%' . $request->title . '%');
            }
            $messages = $query->paginate(10);
            $contacts = Contact::all();
            return view('Admin/pages/dashboard/messages.index', compact('messages', 'contacts'));
        } catch (\Exception $e) {
            Log::error('Error in MessageController@index: ' . $e->getMessage());
            return redirect()->route('messages.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        try {
            $contact = $message->contact;
            return view('Admin/pages/dashboard/messages.show', compact('message', 'contact'));
        } catch (\Exception $e) {
            Log::error('Error in MessageController@show: ' . $e->getMessage());
            return redirect()->route('Admin/pages/dashboard/messages.show')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function edit(Message $message)
    {
        return view('Admin.pages.dashboard.messages.edit', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        try {
            $request->validated();
            $message->status = $request->status;
            $message->save();
    
            return redirect()->route('messages.index')->with('success', 'Message status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error in MessageController@update: ' . $e->getMessage());
            return redirect()->route('messages.index')->with('error', 'Failed to update message status: ' . $e->getMessage());
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Message $message)
    {
        try {
            $message->delete();
            return redirect()->route('messages.index')->with('success', 'Message deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error in MessageController@destroy: ' . $e->getMessage());
            return redirect()->route('messages.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
