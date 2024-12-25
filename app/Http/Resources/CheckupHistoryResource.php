<?php

namespace App\Http\Resources;

use App\Http\Resources\CheckupResource;
use App\Http\Resources\PrescriptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckupHistoryResource extends JsonResource
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
            'checkup_id' => $this->checkup_id,
            'prescription_id' => $this->prescription_id,
            'diagnosis' => $this->diagnosis,
            'notes' => $this->notes,
            'checkup' => new CheckupResource($this->whenLoaded('checkup')),
            'prescription' => new PrescriptionResource($this->whenLoaded('prescription')),
        ];
    }
}
