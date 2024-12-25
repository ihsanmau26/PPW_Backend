<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Checkup;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CheckupResource;
use App\Services\DoctorAvailabilityService;

class CheckupController extends Controller
{
    protected $doctorAvailabilityService;

    public function __construct(DoctorAvailabilityService $doctorAvailabilityService)
    {
        $this->doctorAvailabilityService = $doctorAvailabilityService;
    }

    public function showAll()
    {
        $checkups = Checkup::with(['patient.user', 'doctor.user'])->get();
        return CheckupResource::collection($checkups);
    }

    public function showForDoctor()
    {
        $doctorId = Auth::user()->doctor->id;
        $checkups = Checkup::where('doctor_id', $doctorId)
            ->with(['patient.user', 'doctor.user'])
            ->get();
        return CheckupResource::collection($checkups);
    }

    public function showForPatient()
    {
        $patientId = Auth::user()->patient->id;
        $checkups = Checkup::where('patient_id', $patientId)
            ->with(['patient.user', 'doctor.user'])
            ->get();
        return CheckupResource::collection($checkups);
    }

    public function showById($id)
    {
        $checkup = Checkup::with(['patient.user', 'doctor.user'])->findOrFail($id);

        if (!$checkup) {
            return response()->json(['message' => 'Checkup not found.'], 404);
        }

        return new CheckupResource($checkup);
    }

    public function store(Request $request)
    {
        $request->validate([
            'checkup_date' => 'required|date',
            'checkup_time' => 'required|date_format:H:i',
            'checkup_type' => 'required|in:Dental,General',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $doctorId = $request->input('doctor_id');
        if (!$doctorId) {
            $doctorId = $this->doctorAvailabilityService->getAvailableDoctors(
                $request->input('checkup_date'),
                $request->input('checkup_time'),
                $request->input('checkup_type')
            );
        }

        if (!$doctorId) {
            return response()->json(['message' => 'No available doctor for the specified time and type of consultation.'], 400);
        }

        $patientId = Auth::user()->patient->id ?? $request->input('patient_id');

        $checkup = new Checkup();
        $checkup->patient_id = $patientId;
        $checkup->doctor_id = $doctorId;
        $checkup->checkup_type = $request->input('checkup_type');
        $checkup->checkup_date = $request->input('checkup_date');
        $checkup->checkup_time = $request->input('checkup_time');
        $checkup->status = 'Belum Selesai';
        $checkup->save();

        return response()->json(['message' => 'Checkup created successfully.', 'checkup' => $checkup]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'checkup_date' => 'required|date',
            'checkup_time' => 'required|date_format:H:i',
            'checkup_type' => 'required|in:Dental,General',
        ]);

        $checkup = Checkup::findOrFail($id);
        if ($checkup->status !== 'Belum Selesai' && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Only admin can update completed checkups.'], 403);
        }

        $doctorId = $this->doctorAvailabilityService->getAvailableDoctors(
            $request->input('checkup_date'),
            $request->input('checkup_time'),
            $request->input('checkup_type')
        );

        if (!$doctorId) {
            return response()->json(['message' => 'No available doctor for the specified time and type of consultation.'], 400);
        }

        $checkup->checkup_date = $request->input('checkup_date');
        $checkup->checkup_time = $request->input('checkup_time');
        $checkup->checkup_type = $request->input('checkup_type');
        $checkup->doctor_id = $doctorId;
        $checkup->save();

        return response()->json(['message' => 'Checkup updated successfully.', 'checkup' => $checkup]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Selesai,Dalam Proses,Belum Selesai',
        ]);

        $checkup = Checkup::findOrFail($id);

        $user = Auth::user();

        if ($user->role !== 'admin' && (!$user->doctor || $user->doctor->id !== $checkup->doctor_id)) {
            return response()->json(['message' => 'You do not have permission to update this checkup.'], 403);
        }

        $checkup->status = $request->input('status');
        $checkup->save();

        return response()->json(['message' => 'Checkup status updated successfully.', 'checkup' => $checkup]);
    }

    public function destroy($id)
    {
        $checkup = Checkup::findOrFail($id);

        if ($checkup->status !== 'Belum Selesai') {
            if (!Auth::user()->isAdmin()) {
                return response()->json(['message' => 'Only admin can delete completed checkups.'], 403);
            }
        }

        $checkup->delete();
        return response()->json(['message' => 'Checkup deleted successfully.']);
    }
}

