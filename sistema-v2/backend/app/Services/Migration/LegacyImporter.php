<?php

namespace App\Services\Migration;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

abstract class LegacyImporter
{
    protected $command;
    protected $chunkSize = 1000;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    abstract protected function getSourceTable(): string;
    abstract protected function processRow($row): void;

    public function import()
    {
        $table = $this->getSourceTable();
        $this->processChunk($table, function ($row) {
            $this->processRow($row);
        });
    }

    protected function processChunk(string $table, callable $callback)
    {
        $this->command->info("Starting import for table: {$table}");

        $query = DB::connection('mysql_legacy')->table($table)->orderBy('id');
        $total = $query->count();
        
        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->start();

        $query->chunk($this->chunkSize, function ($rows) use ($bar, $callback, $table) {
            foreach ($rows as $row) {
                try {
                    $callback($row);
                } catch (\Exception $e) {
                    Log::channel('migration')->error("Failed to import row ID {$row->id} from {$table}: " . $e->getMessage());
                    $this->command->error("\nError importing ID {$row->id}: " . $e->getMessage());
                }
                $bar->advance();
            }
        });

        $bar->finish();
        $this->command->newLine();
        $this->command->info("Import completed for table: {$table}");
    }
}
