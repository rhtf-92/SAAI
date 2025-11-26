<?php

namespace App\Services\Migration\Importers;

use App\Models\Student;
use App\Models\User;
use App\Models\Academic\Plan;
use App\Services\Migration\LegacyImporter;
use Illuminate\Support\Facades\DB;

class StudentImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'estudiantes';
    }

    protected function processChunk(string $table, callable $callback)
    {
        $this->command->info("Starting import for table: {$table} (with Users join)");

        $query = DB::connection('mysql_legacy')
            ->table($table)
            ->join('usuarios', 'estudiantes.user_id', '=', 'usuarios.id')
            ->select('estudiantes.*', 'usuarios.nroidenti')
            ->orderBy('estudiantes.id');

        $total = $query->count();
        
        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->start();

        $query->chunk($this->chunkSize, function ($rows) use ($bar, $callback, $table) {
            foreach ($rows as $row) {
                try {
                    $callback($row);
                } catch (\Exception $e) {
                    // Log error but continue
                    $this->command->error("\nError importing Student ID {$row->id}: " . $e->getMessage());
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
        // Verify User exists
        if (!User::find($row->user_id)) {
            $this->command->warn("\nSkipping Student ID {$row->id}: User ID {$row->user_id} not found.");
            return;
        }

        // Verify Plan exists
        if (!Plan::find($row->plan_id)) {
            $this->command->warn("\nSkipping Student ID {$row->id}: Plan ID {$row->plan_id} not found.");
            return;
        }

        // Determine Code
        $code = $row->nroidenti;
        if (empty($code)) {
            $code = $row->anho_ingreso . str_pad($row->id, 4, '0', STR_PAD_LEFT);
        }

        Student::updateOrCreate(
            ['id' => $row->id],
            [
                'user_id' => $row->user_id,
                'plan_id' => $row->plan_id,
                'code' => $code,
                'entry_year' => $row->anho_ingreso,
                'status' => $row->estado == 1 ? 'active' : 'inactive',
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]
        );
    }
}
