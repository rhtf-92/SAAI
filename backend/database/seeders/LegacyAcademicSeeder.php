<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyAcademicSeeder extends Seeder
{
    public function run(): void
    {
        $connection = DB::connection('mysql_legacy');

        // Disable foreign key checks to allow truncation
        $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->table('programas')->truncate();
        $connection->table('planes')->truncate();
        $connection->table('periodos')->truncate();
        $connection->table('cursos')->truncate();
        $connection->table('asignaturas')->truncate();
        $connection->table('tipos_notas')->truncate();
        $connection->statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Programas
        $programId = $connection->table('programas')->insertGetId([
            'codprograma' => 'P001',
            'nombre' => 'Ingeniería de Sistemas',
            'nivel_formativo' => 'Profesional',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("Created Program ID: $programId");

        // 2. Planes
        $planId = $connection->table('planes')->insertGetId([
            'program_id' => $programId,
            'nombre' => 'Plan Curricular 2024',
            'tipo' => 'REGULAR',
            'modalidad' => 'PRESENCIAL',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("Created Plan ID: $planId");

        // 3. Periodos (Ciclos)
        for ($i = 1; $i <= 2; $i++) {
            $periodId = $connection->table('periodos')->insertGetId([
                'plan_id' => $planId,
                'numero' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Created Period ID: $periodId (Ciclo $i)");

            // 4. Cursos
            for ($j = 1; $j <= 3; $j++) {
                $connection->table('cursos')->insert([
                    'period_id' => $periodId,
                    'codcurso' => "C{$i}0{$j}",
                    'nombre' => "Curso {$i}-{$j} de Sistemas",
                    'tipo' => 'OBLIGATORIO',
                    'horas' => 4,
                    'creditos' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $this->command->info("Created 6 Courses.");

        // 5. Semestres (Terms)
        $connection->table('semestres')->insert([
            [
                'anho' => 2024,
                'numero' => 1,
                'tipo' => 'REGULAR',
                'fecinicio' => '2024-03-01',
                'fecfin' => '2024-07-15',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anho' => 2024,
                'numero' => 2,
                'tipo' => 'REGULAR',
                'fecinicio' => '2024-08-15',
                'fecfin' => '2024-12-15',
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        $this->command->info("Created 2 Semesters.");

        // 6. Asignaturas (Classes/Sections)
        // Create classes for Semester 2024-I (ID 1)
        $courses = $connection->table('cursos')->get();
        foreach ($courses as $course) {
            $connection->table('asignaturas')->insert([
                'course_id' => $course->id,
                'semester_id' => 1, // 2024-I
                'seccion' => 'A',
                'turno' => 'M',
                'tipo' => 'REGULAR',
                'nota_minima' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->command->info("Created " . $courses->count() . " Classes (Asignaturas).");

        // 7. Tipos de Notas
        $connection->table('tipos_notas')->insert([
            ['id' => 1, 'nombre' => 'Parcial', 'porcentaje' => 0.3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Final', 'porcentaje' => 0.3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Prácticas', 'porcentaje' => 0.4, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->command->info("Created 3 Grade Types.");
    }
}
