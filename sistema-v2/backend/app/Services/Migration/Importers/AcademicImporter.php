<?php

namespace App\Services\Migration\Importers;

use App\Models\Academic\AcademicPeriod;
use App\Models\Academic\Course;
use App\Models\Academic\Plan;
use App\Models\Academic\Program;
use App\Services\Migration\LegacyImporter;
use Illuminate\Support\Facades\DB;

class AcademicImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return '';
    }

    protected function processRow($row): void
    {
        // Not used
    }
    public function import(): void
    {
        $this->importPrograms();
        $this->importPlans();
        $this->importPeriods();
        $this->importCourses();
    }

    private function importPrograms(): void
    {
        $this->processChunk('programas', function ($row) {
            Program::updateOrCreate(
                ['id' => $row->id],
                [
                    'code' => $row->codprograma,
                    'name' => $row->nombre,
                    'level' => $row->nivel_formativo,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]
            );
        });
    }

    private function importPlans(): void
    {
        $this->processChunk('planes', function ($row) {
            // Verify program exists
            if (!Program::find($row->program_id)) {
                $this->log("Skipping Plan ID {$row->id}: Program ID {$row->program_id} not found.", 'warning');
                return;
            }

            Plan::updateOrCreate(
                ['id' => $row->id],
                [
                    'program_id' => $row->program_id,
                    'name' => $row->nombre,
                    'type' => $row->tipo ?? 'REGULAR', // Default if missing
                    'modality' => $row->modalidad ?? 'PRESENCIAL',
                    'focus' => $row->enfoque ?? null,
                    'date' => $row->fecha ?? null,
                    'document_url' => $row->documento ?? null,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]
            );
        });
    }

    private function importPeriods(): void
    {
        // Legacy table might be 'periodos' or 'semestres' depending on inspection
        // Assuming 'periodos' based on previous steps
        $this->processChunk('periodos', function ($row) {
             // Verify plan exists
             if (!Plan::find($row->plan_id)) {
                $this->log("Skipping Period ID {$row->id}: Plan ID {$row->plan_id} not found.", 'warning');
                return;
            }

            AcademicPeriod::updateOrCreate(
                ['id' => $row->id],
                [
                    'plan_id' => $row->plan_id,
                    'number' => $row->numero,
                    'name' => 'Ciclo ' . $this->roman($row->numero), // Generate name like "Ciclo I"
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]
            );
        });
    }

    private function importCourses(): void
    {
        $this->processChunk('cursos', function ($row) {
            // Verify period exists
            // Legacy 'period_id' maps to 'academic_period_id'
            if (!AcademicPeriod::find($row->period_id)) {
                 $this->log("Skipping Course ID {$row->id}: Period ID {$row->period_id} not found.", 'warning');
                 return;
            }

            Course::updateOrCreate(
                ['id' => $row->id],
                [
                    'academic_period_id' => $row->period_id,
                    'code' => $row->codcurso,
                    'name' => $row->nombre,
                    'type' => $row->tipo,
                    'hours' => $row->horas,
                    'credits' => $row->creditos,
                    'predecessor_code' => $row->codpredecesor,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]
            );
        });
    }

    private function roman($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
