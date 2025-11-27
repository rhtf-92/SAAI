<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'program_id' => $this->program_id,
            'program' => new ProgramResource($this->whenLoaded('program')),
            'periods' => AcademicPeriodResource::collection($this->whenLoaded('academicPeriods')),
            'name' => $this->name,
            'type' => $this->type,
            'modality' => $this->modality,
            'focus' => $this->focus,
            'date' => $this->date,
            'document_url' => $this->document_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
