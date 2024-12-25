<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function getShifts()
    {
        $shifts = Shift::all();
        return response()->json(['data' => $shifts]);
    }
}
