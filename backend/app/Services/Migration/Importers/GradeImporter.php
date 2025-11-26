<?php

namespace App\Services\Migration\Importers;

use App\Services\Migration\LegacyImporter;
use App\Models\Grade;
use App\Models\EnrollmentDetail;
use Illuminate\Support\Facades\DB;

class GradeImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'notas_estudiantes';
    }

    protected function processChunk(string $table, callable $callback)
    {
        $this->command->info("Starting import for table: {$table} (with Joins)");

        // Join structure:
        // notas_estudiantes (grade_id) -> notas (id)
        // notas (gradetype_id) -> tipos_notas (id)
        // notas_estudiantes (subjectenrollment_id) -> matriculas_asignaturas (id) -> EnrollmentDetail (id)

        $query = DB::connection('mysql_legacy')
            ->table($table)
            ->join('notas', 'notas_estudiantes.grade_id', '=', 'notas.id')
            ->join('tipos_notas', 'notas.gradetype_id', '=', 'tipos_notas.id')
            ->select(
                'notas_estudiantes.id as legacy_id',
                'notas_estudiantes.subjectenrollment_id as enrollment_detail_id',
                'notas_estudiantes.nota as score',
                'notas.nombre as grade_name',
                'tipos_notas.nombre as type_name',
                'tipos_notas.porcentaje as weight',
                'notas_estudiantes.created_at',
                'notas_estudiantes.updated_at'
            )
            ->orderBy('notas_estudiantes.id');

        $total = $query->count();
        
        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->start();

        $query->chunk($this->chunkSize, function ($rows) use ($bar, $callback, $table) {
            foreach ($rows as $row) {
                try {
                    $callback($row);
                } catch (\Exception $e) {
                    $this->command->error("\nError importing Grade ID {$row->legacy_id}: " . $e->getMessage());
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
        // Verify EnrollmentDetail exists (preserved ID from matriculas_asignaturas)
        if (!EnrollmentDetail::find($row->enrollment_detail_id)) {
            // This might happen if we filtered enrollments or if data is inconsistent
            // $this->command->warn("\nSkipping Grade ID {$row->legacy_id}: EnrollmentDetail {$row->enrollment_detail_id} not found.");
            return;
        }

        Grade::updateOrCreate(
            [
                'enrollment_detail_id' => $row->enrollment_detail_id,
                'name' => $row->grade_name,
                'type' => $row->type_name,
            ],
            [
                'weight' => $row->weight, // Assuming percentage is 0-1 or 0-100? Legacy usually 0-100 or 0-1.
                // Let's assume it's 0-1 based on typical systems, but we should check.
                // If it's > 1, maybe divide by 100?
                // Let's store as is for now, or check sample data.
                'score' => $row->score,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]
        );
    }
}
