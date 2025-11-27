<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $enrollmentCourse = $this->enrollmentCourse;
        $student = $enrollmentCourse->enrollment->student ?? null;
        $course = $enrollmentCourse->course ?? null;
        $creator = $this->creator;

        return [
            'id' => $this->id,
            'enrollment_course_id' => $this->enrollment_course_id,
            'enrollment_course' => $enrollmentCourse ? [
                'id' => $enrollmentCourse->id,
                'student' => $student ? [
                    'id' => $student->id,
                    'name' => $student->user->name ?? 'N/A',
                    'code' => str_pad($student->id, 8, '0', STR_PAD_LEFT),
                ] : null,
                'course' => $course ? [
                    'id' => $course->id,
                    'name' => $course->name ?? 'N/A',
                    'code' => $course->code ?? 'N/A',
                ] : null,
            ] : null,
            'date' => $this->date?->format('Y-m-d'),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'notes' => $this->notes,
            'created_by' => $creator ? [
                'id' => $creator->id,
                'name' => $creator->name ?? 'Sistema',
            ] : null,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
