<?php

namespace App\Http\Controllers;

use App\Models\PrescriptionDetail;
use App\Http\Resources\PrescriptionDetailResource;
use Illuminate\Http\Request;

class PrescriptionDetailController extends Controller
{
    public function index()
    {
        $prescriptionDetails = PrescriptionDetail::with('medicine')->get();
        return PrescriptionDetailResource::collection($prescriptionDetails);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'instructions' => 'required|string',
        ]);

        $prescriptionDetail = PrescriptionDetail::create($validated);
        return new PrescriptionDetailResource($prescriptionDetail);
    }

    public function show($id)
    {
        $prescriptionDetail = PrescriptionDetail::with('medicine')->findOrFail($id);
        return new PrescriptionDetailResource($prescriptionDetail);
    }



    public function update(Request $request, $id)
    {
        $prescriptionDetail = PrescriptionDetail::findOrFail($id);

        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'instructions' => 'required|string',
        ]);

        $prescriptionDetail->update($validated);

        return new PrescriptionDetailResource($prescriptionDetail);
    }

    public function destroy($id)
    {
        $prescriptionDetail = PrescriptionDetail::findOrFail($id);
        $prescriptionDetail->delete();
        return response()->json(['message' => 'Prescription detail deleted successfully']);
    }
    
}
