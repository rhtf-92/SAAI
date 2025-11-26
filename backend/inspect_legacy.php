<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
$tables = [
    'pagos',
    'pagos_matriculas',
    'ts_conceptos',
    'ts_estados_cuentas', // Likely Debts
    'ts_pagos_personas',
];

foreach ($tables as $table) {
    echo "Table: $table\n";
    try {
        $columns = DB::connection('mysql_legacy')->select("SHOW COLUMNS FROM $table");
        foreach ($columns as $column) {
            echo " - {$column->Field} ({$column->Type})\n";
        }
    } catch (\Exception $e) {
        echo "Error inspecting $table: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

} catch (\Exception $e) {
    echo "General Error: " . $e->getMessage();
}
