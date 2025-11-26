<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Migration\Importers\UsersImporter;
use App\Services\Migration\Importers\AcademicImporter;
use App\Services\Migration\Importers\TreasuryImporter;

class MigrateLegacyCommand extends Command
{
    protected $signature = 'migrate:legacy {--module=all : The module to migrate (users, academic, etc)}';
    protected $description = 'Migrate data from legacy database';

    public function handle()
    {
        $module = $this->option('module');

        $importers = [
            'ubigeos' => \App\Services\Migration\Importers\UbigeoImporter::class,
            'users' => UsersImporter::class,
            'academic' => AcademicImporter::class,
            'students' => \App\Services\Migration\Importers\StudentImporter::class,
            'terms' => \App\Services\Migration\Importers\TermImporter::class,
            'enrollments' => \App\Services\Migration\Importers\EnrollmentImporter::class,
            'enrollment_details' => \App\Services\Migration\Importers\EnrollmentDetailImporter::class,
            'grades' => \App\Services\Migration\Importers\GradeImporter::class,
            'treasury' => TreasuryImporter::class,
        ];

        if ($module === 'all') {
            foreach ($importers as $key => $importerClass) {
                (new $importerClass($this))->import();
            }
        } elseif (isset($importers[$module])) {
            $importerClass = $importers[$module];
            (new $importerClass($this))->import();
        } else {
            $this->error("Module '{$module}' not found.");
        }
    }
}
