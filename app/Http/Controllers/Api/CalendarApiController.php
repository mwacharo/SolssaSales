<?php

namespace App\Http\Controllers\Api;

use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalendarApiController extends Controller
{
    public function index()
    {
      $events = Calendar::all();

        return response()->json([
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'event' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'status' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $calendar = Calendar::create($request->all());
        return response()->json($calendar, 201);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'event' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'status' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $calendar = Calendar::findOrFail($id);
        $calendar->update($request->all());
        return response()->json($calendar, 200);
    }

    public function destroy(string $id)
    {
        $calendar = Calendar::findOrFail($id);
        $calendar->delete();
        return response()->json(null, 204);
    }
}
