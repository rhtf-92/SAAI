<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'number' => $this->number,
            'floor' => $this->floor,
            'capacity' => $this->capacity,
            'location' => $this->location,
            'observation' => $this->observation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
