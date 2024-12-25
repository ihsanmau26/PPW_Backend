<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\DoctorDetailResource;
use App\Http\Resources\PatientDetailResource;

class UserController extends Controller
{
    public function indexAdmins()
    {
        $admins = User::where('role', 'admin')->get();
        return response()->json($admins->makeHidden(['created_at', 'updated_at']));
    }

    public function indexDoctors()
    {
        $doctors = Doctor::with('user', 'shifts')->get();

        if ($doctors->isEmpty()) {
            return response()->json(['message' => 'No doctors found'], 200);
        }

        return DoctorResource::collection($doctors);
    }

    public function indexPatients()
    {
        $patients = User::where('role', 'patient')->with('patient')->get();
        return PatientResource::collection($patients);
    }

    public function showAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return response()->json($admin->makeHidden(['created_at', 'updated_at']));
    }

    public function showDoctor($id)
    {
        $user = User::where('role', 'doctor')
                ->with([
                    'doctor.shifts',
                    'doctor.checkups',
                    ])
                ->findOrFail($id);

        return new DoctorDetailResource($user->doctor);
    }

    public function showPatient($id)
    {
        $patient = User::where('role', 'patient')
                    ->with([
                        'patient',
                        'patient.checkups',
                        'patient.checkups.checkup_histories',
                        'patient.checkups.checkup_histories.prescription',
                        'patient.checkups.checkup_histories.prescription.doctor',
                    ])
                    ->findOrFail($id);

        return new PatientDetailResource($patient);
    }

    public function storePatient(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
        ]);

        $dateOfBirth = Carbon::parse($validatedData['date_of_birth']);
        $age = $dateOfBirth->age;

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role' => 'patient',
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
            'gender' => $validatedData['gender'],
            'age' => $age,
            'date_of_birth' => $validatedData['date_of_birth'],
            'phone_number' => $validatedData['phone_number'],
        ]);

        return response()->json(['message' => 'Patient added successfully'], 200)
                        ->header('Access-Control-Allow-Origin', '*');
    }

    public function storeDoctor(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'specialization' => 'required|in:Dental,General',
            'shifts' => 'required|array',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role' => 'doctor',
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'specialization' => $validatedData['specialization'],
        ]);

        foreach ($validatedData['shifts'] as $shiftId) {
            $shift = Shift::findOrFail($shiftId);
            DoctorShift::create([
                'doctor_id' => $doctor->id,
                'shift_id' => $shift->id,
            ]);
        }

        return response()->json(['message' => 'Doctor added successfully']);
    }

    public function editPatient(Request $request, $id)
    {
        $patient = User::where('role', 'patient')->with('patient')->findOrFail($id);
        $user = $patient;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
        ]);

        $dateOfBirth = Carbon::parse($validatedData['date_of_birth']);
        $age = $dateOfBirth->age;

        $user->name = $validatedData['name'];
        $user->save();

        $user->patient->update([
            'gender' => $validatedData['gender'],
            'age' => $age,
            'date_of_birth' => $validatedData['date_of_birth'],
            'phone_number' => $validatedData['phone_number'],
        ]);

        return response()->json(['message' => 'Patient edited successfully']);
    }

    public function editDoctor(Request $request, $id)
    {
        $doctor = User::where('role', 'doctor')->with('doctor.shifts')->find($id);
        $user = $doctor;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|in:Dental,General',
            'shifts' => 'required|array',
        ]);        

        $user->name = $validatedData['name'];
        $user->save();

        $user->doctor->update([
            'specialization' => $validatedData['specialization'],
        ]);

        DoctorShift::where('doctor_id', $user->doctor->id)->delete();

        foreach ($validatedData['shifts'] as $shiftId) {
            $shift = Shift::findOrFail($shiftId);
            DoctorShift::create([
                'doctor_id' => $user->doctor->id,
                'shift_id' => $shift->id,
            ]);
        }

        return response()->json(['message' => 'Doctor edited successfully']);
    }

    public function deletePatient($id)
    {
        $patient = Patient::findOrFail($id);
        $user = $patient->user;

        $patient->delete();
        $user->delete();

        return response()->json(['message' => 'Patient deleted successfully']);
    }

    public function deleteDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $user = $doctor->user;

        DoctorShift::where('doctor_id', $doctor->id)->delete();
        $doctor->delete();
        $user->delete();

        return response()->json(['message' => 'Doctor deleted successfully']);
    }
}