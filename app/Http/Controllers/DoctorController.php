<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Services\DoctorAvailabilityService;

class DoctorController extends Controller
{
    protected $doctorAvailabilityService;

    public function __construct(DoctorAvailabilityService $doctorAvailabilityService)
    {
        $this->doctorAvailabilityService = $doctorAvailabilityService;
    }

    public function getAvailableDoctors(Request $request)
    {
        $request->validate([
            'consultation_date' => 'required|date',
            'consultation_time' => 'required|date_format:H:i',
            'consultation_type' => 'required|in:Dental,General',
        ]);

        $doctorId = $this->doctorAvailabilityService->getAvailableDoctors(
            $request->input('consultation_date'),
            $request->input('consultation_time'),
            $request->input('consultation_type')
        );

        if ($doctorId) {
            return response()->json([
                'doctor_id' => $doctorId,
                'message' => 'Doctor found and available for the specified consultation.'
            ]);
        } else {
            return response()->json([
                'doctor_id' => null,
                'message' => 'No available doctor found for the specified consultation.'
            ]);
        }
    }
}
