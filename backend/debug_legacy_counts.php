<?php

use Illuminate\Support\Facades\DB;

try {
    $tables = ['usuarios', 'alumnos', 'matriculas', 'programas', 'planes', 'periodos', 'cursos'];
    
    foreach ($tables as $table) {
        try {
            $count = DB::connection('mysql_legacy')->table($table)->count();
            echo "Table '$table': $count records\n";
        } catch (\Exception $e) {
            echo "Table '$table': Error - " . $e->getMessage() . "\n";
        }
    }

} catch (\Exception $e) {
    echo "General Error: " . $e->getMessage();
}
