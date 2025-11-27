<?php

namespace App\Services\Migration\Importers;

use App\Services\Migration\LegacyImporter;
use App\Models\Term;

class TermImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'semestres';
    }

    protected function processRow($row): void
    {
        // Convert legacy period number to Roman numeral for name
        $roman = $row->numero == 1 ? 'I' : ($row->numero == 2 ? 'II' : $row->numero);
        $name = "{$row->anho}-{$roman}";

        Term::updateOrCreate(
            ['id' => $row->id],
            [
                'name' => $name,
                'year' => $row->anho,
                'period' => $row->numero,
                'start_date' => $row->fecinicio,
                'end_date' => $row->fecfin,
                'is_active' => $row->estado == 1,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]
        );
    }
}
