<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Http\Resources\MedicineResource;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all();
        return MedicineResource::collection($medicines);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Tablet,Syrup,Capsule,Injection,Ointment',
            'description' => 'nullable|string',
        ]);

        $medicine = Medicine::create($validated);
        return new MedicineResource($medicine);
    }

    public function show($id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        return new MedicineResource($medicine);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Tablet,Syrup,Capsule,Injection,Ointment',
            'description' => 'nullable|string',
        ]);

        $medicine = Medicine::findOrFail($id); 

        $medicine->update($validated);
        return new MedicineResource($medicine);
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);

        $medicine->delete();
        return response()->json(['message' => 'Medicine deleted successfully'], 200);
    }
}
