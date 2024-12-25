<?php

namespace App\Http\Controllers;

use App\Models\Checkup;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Http\Resources\PrescriptionResource;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['patient.user', 'doctor.user', 'prescriptionDetails.medicine'])->get();
        return PrescriptionResource::collection($prescriptions);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'checkup_id' => 'required|exists:checkups,id',
            'prescription_details' => 'required|array',
            'prescription_details.*.medicine_id' => 'required|exists:medicines,id',
            'prescription_details.*.quantity' => 'required|integer',
            'prescription_details.*.instructions' => 'required|string',
        ]);
    
        $checkup = Checkup::findOrFail($validated['checkup_id']);
    
        $prescription = Prescription::create([
            'patient_id' => $checkup->patient_id,
            'doctor_id' => $checkup->doctor_id,
            'prescription_date' => $checkup->checkup_date,
        ]);
    
        foreach ($validated['prescription_details'] as $detail) {
            $prescription->prescriptionDetails()->create([
                'medicine_id' => $detail['medicine_id'],
                'quantity' => $detail['quantity'],
                'instructions' => $detail['instructions'],
            ]);
        }
    
        return response()->json([
            'message' => 'Prescription berhasil dibuat!',
            'id' => $prescription->id
        ]);
    }
    

    public function show($id)
    {
        $prescription = Prescription::with(['patient.user', 'doctor.user', 'prescriptionDetails.medicine'])->findOrFail($id);
        return new PrescriptionResource($prescription);
    }

    public function update(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);

        $validated = $request->validate([
            'prescription_date' => 'required|date',
            'prescription_details' => 'required|array',
            'prescription_details.*.medicine_id' => 'required|exists:medicines,id',
            'prescription_details.*.quantity' => 'required|integer|min:1',
            'prescription_details.*.instructions' => 'required|string',
        ]);

        $prescription->update([
            'prescription_date' => $validated['prescription_date'],
        ]);

        $prescription->prescriptionDetails()->forceDelete();

        $prescription->prescriptionDetails()->createMany(
            array_map(function ($detail) use ($prescription) {
                $detail['prescription_id'] = $prescription->id;
                return $detail;
            }, $validated['prescription_details'])
        );

        $prescription->load(['prescriptionDetails.medicine']);
        return new PrescriptionResource($prescription);
    }

    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->prescriptionDetails()->delete();
        $prescription->delete();

        return response()->json(['message' => 'Prescription deleted successfully']);
    }
}
