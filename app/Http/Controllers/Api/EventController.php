<?php

namespace App\Http\Controllers\Api;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getEvents = Event::all();
        if($getEvents->isEmpty()){
         return response()->json(['message' => 'No events found'], 404);
        }
        return response()->json(['events' => $getEvents],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $event = Event::create($validated)->paginate(10);
        return response()->json(['message' => 'Event created successfully','event' => $event], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $findEvent = Event::find($id);
        if(!$findEvent){
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json(['event' => $findEvent], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $findEvent = Event::find($id);
        if(!$findEvent){
            return response()->json(['message' => 'Event not found'], 404);
        }
        $updatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'location' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $findEvent->update($updatedData);
        return response()->json(['message' => 'Event updated successfully', 'event' => $findEvent], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $findEvent = Event::find($id);
        if(!$findEvent){
            return response()->json(['message' => 'Event not found'], 404);
        }
        $findEvent->delete();
        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}
