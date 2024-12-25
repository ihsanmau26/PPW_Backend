<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionDetailResource extends JsonResource
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
            'medicine_name' => $this->medicine->name ?? null,
            'medicine_type' => $this->medicine->type ?? null,
            'medicine_description' => $this->medicine->description ?? null,
            'quantity' => $this->quantity ?? null,
            'instructions' => $this->instructions ?? null,
        ];
    }
}
