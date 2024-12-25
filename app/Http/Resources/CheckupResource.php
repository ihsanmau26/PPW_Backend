<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckupResource extends JsonResource
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
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'checkup_type' => $this->checkup_type,
            'checkup_date' => $this->checkup_date,
            'checkup_time' => $this->checkup_time,
            'status' => $this->status,
            'patient' => [
                'id' => $this->patient->id,
                'user_id' => $this->patient->user_id,
                'name' => $this->patient->user->name,
                'gender' => $this->patient->gender,
                'age' => $this->patient->age,
                'date_of_birth' => $this->patient->date_of_birth,
                'phone_number' => $this->patient->phone_number,
            ],
            'doctor' => [
                'id' => $this->doctor->id,
                'user_id' => $this->doctor->user_id,
                'name' => $this->doctor->user->name,
                'specialization' => $this->doctor->specialization,
            ],
        ];
    }
}
