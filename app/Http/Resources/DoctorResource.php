<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ShiftResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'doctor_id' => $this->id ?? null,
            'specialization' => $this->specialization ?? null,
            'shifts' => ShiftResource::collection($this->shifts),
        ];
    }
}
