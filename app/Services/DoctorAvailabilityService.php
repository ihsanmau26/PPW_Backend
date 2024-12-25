<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorAvailabilityService
{
    /**
     * Get available doctors based on date, specialization, and appointment time.
     *
     * @param string $date The date in 'Y-m-d' format.
     * @param string $specialization Specialization of the doctor ('Dental', 'General').
     * @param string $time The appointment time in 'H:i' format (e.g., '15:00').
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableDoctors(string $consultationDate, string $consultationTime, string $consultationType)
    {
        // Determine the day of the week (e.g., 'Monday')
        $dayOfWeek = Carbon::parse($consultationDate)->format('l');

        // Query to find an available doctor
        $doctor = DB::table('doctor_shifts')
            ->join('shifts', 'doctor_shifts.shift_id', '=', 'shifts.id')
            ->join('doctors', 'doctor_shifts.doctor_id', '=', 'doctors.id')
            ->where('shifts.day', $dayOfWeek)
            ->whereTime('shifts.shift_start', '<=', $consultationTime)
            ->whereTime('shifts.shift_end', '>=', $consultationTime)
            ->where('doctors.specialization', $consultationType)
            ->select('doctors.id')
            ->first();

        return $doctor ? $doctor->id : null;
    }
}
