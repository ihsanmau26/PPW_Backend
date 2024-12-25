<?php

namespace App\Http\Resources;

use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PrescriptionDetailResource;

class PrescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient' => [
                'patient_id' => $this->patient->id,
                'name' => $this->patient->user->name,
                'email' => $this->patient->user->email,
                'gender' => $this->patient->gender,
                'age' => $this->patient->age,
                'date_of_birth' => $this->patient->date_of_birth,
                'phone_number' => $this->patient->phone_number,
            ],
            'doctor' => [
                'doctor_id' => $this->doctor->id,
                'name' => $this->doctor->user->name,
                'email' => $this->doctor->user->email,
                'specialization' => $this->doctor->specialization,
            ],
            'prescription_date' => $this->prescription_date,
            'prescription_details' => PrescriptionDetailResource::collection($this->prescriptionDetails),
        ];
    }
}
