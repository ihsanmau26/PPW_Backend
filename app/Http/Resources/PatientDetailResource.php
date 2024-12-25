<?php

namespace App\Http\Resources;

use App\Http\Resources\CheckupResource;
use App\Http\Resources\CheckupHistoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientDetailResource extends JsonResource
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
            'patient_details' => [
                'gender' => $this->patient->gender ?? null,
                'age' => $this->patient->age ?? null,
                'date_of_birth' => $this->patient->date_of_birth ?? null,
                'phone_number' => $this->patient->phone_number ?? null,
            ],
            'checkups' => CheckupResource::collection($this->whenLoaded('patient.checkups')),
            'history' => $this->patient->checkups->flatMap(function ($checkup) {
                return $checkup->checkup_histories->map(function ($history) {
                    return new CheckupHistoryResource($history);
                });
            }),
        ];
    }
}
