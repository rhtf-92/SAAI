<?php

use Illuminate\Support\Facades\DB;

try {
    $pdo = DB::connection('mysql_legacy')->getPdo();
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "--- LEGACY DATABASE AUDIT ---\n";
    echo "Total Tables: " . count($tables) . "\n\n";
    
    foreach ($tables as $table) {
        try {
            $count = DB::connection('mysql_legacy')->table($table)->count();
            if ($count > 0) {
                echo "[$table]: $count records\n";
            }
        } catch (\Exception $e) {
            echo "[$table]: Error counting\n";
        }
    }
    echo "\n--- END AUDIT ---\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
