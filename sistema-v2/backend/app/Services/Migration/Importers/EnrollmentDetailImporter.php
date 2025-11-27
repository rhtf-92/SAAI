<?php

namespace App\Services\Migration\Importers;

use App\Services\Migration\LegacyImporter;
use App\Models\EnrollmentDetail;
use App\Models\Enrollment;
use App\Models\Academic\Course;
use Illuminate\Support\Facades\DB;

class EnrollmentDetailImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'matriculas_asignaturas';
    }

    protected function processChunk(string $table, callable $callback)
    {
        $this->command->info("Starting import for table: {$table} (with Asignaturas join)");

        $query = DB::connection('mysql_legacy')
            ->table($table)
            ->join('asignaturas', 'matriculas_asignaturas.subject_id', '=', 'asignaturas.id')
            ->select('matriculas_asignaturas.*', 'asignaturas.semester_id', 'asignaturas.course_id as catalog_course_id')
            ->orderBy('matriculas_asignaturas.id');

        $total = $query->count();
        
        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->start();

        $query->chunk($this->chunkSize, function ($rows) use ($bar, $callback, $table) {
            foreach ($rows as $row) {
                try {
                    $callback($row);
                } catch (\Exception $e) {
                    $this->command->error("\nError importing Detail ID {$row->id}: " . $e->getMessage());
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->command->newLine();
        $this->command->info("Import completed for table: {$table}");
    }

    protected function processRow($row): void
    {
        // Find Enrollment (Header)
        $enrollment = Enrollment::where('student_id', $row->student_id)
            ->where('term_id', $row->semester_id)
            ->first();

        if (!$enrollment) {
            $this->command->warn("\nSkipping Detail ID {$row->id}: Enrollment not found for Student {$row->student_id} in Term {$row->semester_id}.");
            return;
        }

        // Verify Course exists (Catalog)
        // Note: catalog_course_id from asignaturas maps to v2 Course ID (preserved)
        if (!Course::find($row->catalog_course_id)) {
            $this->command->warn("\nSkipping Detail ID {$row->id}: Course ID {$row->catalog_course_id} not found.");
            return;
        }

        EnrollmentDetail::updateOrCreate(
            ['id' => $row->id],
            [
                'enrollment_id' => $enrollment->id,
                'course_id' => $row->catalog_course_id,
                'credits' => 0, // Legacy doesn't store credits in detail? Get from Course?
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]
        );
        
        // Update credits from course
        $course = Course::find($row->catalog_course_id);
        if ($course) {
            EnrollmentDetail::where('id', $row->id)->update(['credits' => $course->credits]);
        }
    }
}
