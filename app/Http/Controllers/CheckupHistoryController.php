<?php

namespace App\Http\Controllers;

use App\Models\Checkup;
use Illuminate\Http\Request;
use App\Models\CheckupHistory;
use App\Http\Resources\CheckupHistoryResource;

class CheckupHistoryController extends Controller
{
    public function index()
    {
        $histories = CheckupHistory::with(['checkup', 'prescription'])->get();
        return CheckupHistoryResource::collection($histories);
    }

    public function show(Request $request, $id)
    {
        $history = CheckupHistory::with(['checkup', 'prescription'])->findOrFail($id);

        $includeFrom = $request->get('include_patient_doctor', 'checkup');
        if ($includeFrom === 'checkup') {
            $history->load('checkup.patient', 'checkup.doctor');
        } elseif ($includeFrom === 'prescription') {
            $history->load('prescription.patient', 'prescription.doctor');
        }

        return new CheckupHistoryResource($history);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'checkup_id' => 'required|exists:checkups,id',
            'prescription_id' => 'required|exists:prescriptions,id',
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $checkup = Checkup::findOrFail($validated['checkup_id']);
        if ($checkup->status !== 'Selesai') {
            return response()->json([
                'message' => 'Only checkups with status "Selesai" can be recorded in checkup histories.'
            ], 400);
        }

        $checkupHistory = CheckupHistory::create([
            'checkup_id' => $validated['checkup_id'],
            'prescription_id' => $validated['prescription_id'],
            'diagnosis' => $validated['diagnosis'],
            'notes' => $validated['notes'],
        ]);

        return new CheckupHistoryResource($checkupHistory);
    }

    public function update(Request $request, $id)
    {
        $history = CheckupHistory::findOrFail($id);

        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $history->update($validated);

        return new CheckupHistoryResource($history);
    }

    public function destroy($id)
    {
        $history = CheckupHistory::findOrFail($id);
        $history->delete();

        return response()->json(['message' => 'Checkup history deleted successfully.'], 200);
    }
}
