<?php

namespace App\Services\Migration\Importers;

use App\Services\Migration\LegacyImporter;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Term;

class EnrollmentImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'matriculas_semestres';
    }

    protected function processRow($row): void
    {
        // Verify Student exists
        if (!Student::find($row->student_id)) {
            $this->command->warn("\nSkipping Enrollment ID {$row->id}: Student ID {$row->student_id} not found.");
            return;
        }

        // Verify Term exists
        if (!Term::find($row->semester_id)) {
            $this->command->warn("\nSkipping Enrollment ID {$row->id}: Term ID {$row->semester_id} not found.");
            return;
        }

        // Get Student's Plan (Assuming enrollment is in current plan)
        $student = Student::find($row->student_id);
        $planId = $student->plan_id;

        Enrollment::updateOrCreate(
            ['id' => $row->id],
            [
                'student_id' => $row->student_id,
                'term_id' => $row->semester_id,
                'plan_id' => $planId,
                'academic_period_id' => null, // We don't know the target cycle yet
                'date' => $row->fecha,
                'status' => $row->estado == 1 ? 'active' : 'inactive',
                'observation' => $row->docboucher,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]
        );
    }
}
