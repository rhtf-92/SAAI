<?php

namespace App\Services\Migration\Importers;

use App\Services\Migration\LegacyImporter;
use App\Models\Academic\Ubigeo;

class UbigeoImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'ubigeos';
    }

    protected function processRow($row): void
    {
        Ubigeo::firstOrCreate(
            ['id' => $row->id], // Legacy 'id' is the 6-digit code? Need to verify
            [
                'department' => $row->departamento,
                'province' => $row->provincia,
                'district' => $row->distrito,
            ]
        );
    }
}
