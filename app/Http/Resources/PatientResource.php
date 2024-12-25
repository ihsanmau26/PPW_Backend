<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'patient_id' => $this->patient->id ?? null,
            'gender' => $this->patient->gender ?? null,
            'age' => $this->patient->age ?? null,
            'date_of_birth' => $this->patient->date_of_birth ?? null,
            'phone_number' => $this->patient->phone_number ?? null,
        ];
    }
}
