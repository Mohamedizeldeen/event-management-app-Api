<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendee;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllAttendees = Attendee::all();
        if($AllAttendees->isEmpty()){
            return response()->json(['message' => 'No attendees found'], 404);
        }
        return response()->json(['attendees' => $AllAttendees], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:attendees,email',
            'organization' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);
        $attendee = Attendee::create($validated);
        return response()->json(['message' => 'Attendee registered successfully','attendee' => $attendee], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $findAttendee = Attendee::find($id);
        if(!$findAttendee){
            return response()->json(['message' => 'Attendee not found'], 404);
        }
        return response()->json(['attendee' => $findAttendee], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $findAttendee = Attendee::find($id);
        if(!$findAttendee){
            return response()->json(['message' => 'Attendee not found'], 404);
        }
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:attendees,email,'.$findAttendee->id,
            'organization' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
        ]);
        $findAttendee->update($validated);
        return response()->json(['message' => 'Attendee updated successfully','attendee' => $findAttendee], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $findAttendee = Attendee::find($id);
        if(!$findAttendee){
            return response()->json(['message' => 'Attendee not found'], 404);
        }
        $findAttendee->delete();
        return response()->json(['message' => 'Attendee deleted successfully'], 200);
    }
}
