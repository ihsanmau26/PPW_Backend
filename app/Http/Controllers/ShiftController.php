<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function getShifts()
    {
        $shifts = Shift::all();
        return response()->json(['data' => $shifts], 200);
    }

    public function show($id)
    {
        $shift = Shift::with('doctors.user')->find($id);

        if (!$shift) {
            return response()->json(['message' => 'Shift not found'], 404);
        }

        return response()->json(['data' => $shift], 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'shift_start' => 'required|date_format:H:i',
            'shift_end' => 'required|date_format:H:i|after:shift_start',
        ]);

        $shift = Shift::create($validatedData);

        return response()->json(['data' => $shift, 'message' => 'Shift created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return response()->json(['message' => 'Shift not found'], 404);
        }

        $validatedData = $request->validate([
            'day' => 'sometimes|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'shift_start' => 'sometimes|date_format:H:i',
            'shift_end' => 'sometimes|date_format:H:i|after:shift_start',
        ]);

        $shift->update($validatedData);

        return response()->json(['data' => $shift, 'message' => 'Shift updated successfully'], 200);
    }

    public function destroy($id)
    {
        $shift = Shift::find($id);

        if (!$shift) {
            return response()->json(['message' => 'Shift not found'], 404);
        }

        $shift->delete();

        return response()->json(['message' => 'Shift deleted successfully'], 200);
    }
}
