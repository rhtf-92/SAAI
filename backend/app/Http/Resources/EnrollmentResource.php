<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'term' => [
                'id' => $this->term->id,
                'name' => $this->term->name,
                'start_date' => $this->term->start_date,
                'end_date' => $this->term->end_date,
            ],
            'date' => $this->date,
            'status' => $this->status,
            'observation' => $this->observation,
            'total_credits' => $this->details->sum('credits'),
            'details' => $this->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'course' => [
                        'id' => $detail->course->id,
                        'code' => $detail->course->code, // Assuming Course has code? Legacy 'codcurso' -> 'code'?
                        // Wait, Course model in v2 has 'code'?
                        // Let's check Course migration.
                        'name' => $detail->course->name,
                        'credits' => $detail->course->credits,
                    ],
                    'credits' => $detail->credits,
                    'grades' => $detail->grades->map(function ($grade) {
                        return [
                            'id' => $grade->id,
                            'name' => $grade->name,
                            'type' => $grade->type,
                            'weight' => $grade->weight,
                            'score' => $grade->score,
                        ];
                    }),
                ];
            }),
        ];
    }
}
