<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LegacyStudentSeeder extends Seeder
{
    public function run(): void
    {
        $connection = DB::connection('mysql_legacy');

        // Disable foreign key checks
        $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->table('usuarios')->truncate();
        $connection->table('estudiantes')->truncate();
        $connection->table('matriculas_semestres')->truncate();
        $connection->table('matriculas_asignaturas')->truncate();
        $connection->table('notas')->truncate();
        $connection->table('notas_estudiantes')->truncate();
        $connection->statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ensure we have a plan (seeded by LegacyAcademicSeeder)
        $planId = $connection->table('planes')->first()->id ?? 1;

        // Create a User for the Student
        $userId = $connection->table('usuarios')->insertGetId([
            'identificationtype_id' => 1,
            'nroidenti' => '70000001',
            'password' => Hash::make('password'),
            'nombres' => 'Juan',
            'apellido_pa' => 'Perez',
            'apellido_ma' => 'Gomez',
            'fecnac' => '2000-01-01',
            'correo' => 'juan.perez@example.com',
            'estado' => 1,
            'usertype_id' => 3, // Student
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("Created Legacy User ID: $userId");

        // Create Student Record
        $studentId = $connection->table('estudiantes')->insertGetId([
            'user_id' => $userId,
            'plan_id' => $planId,
            'anho_ingreso' => 2024,
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("Created Legacy Student ID: $studentId linked to User $userId");

        // Create Enrollment (Matricula Semestre) for 2024-I
        // Term 2024-I ID is likely 1 (seeded first)
        $enrollmentId = $connection->table('matriculas_semestres')->insertGetId([
            'semester_id' => 1, // 2024-I
            'student_id' => $studentId,
            'fecha' => '2024-03-15',
            'docboucher' => 'RECIBO-001',
            'estado' => 1,
            'created_at' => '2024-03-15 10:00:00',
            'updated_at' => '2024-03-15 10:00:00',
        ]);

        $this->command->info("Created Enrollment ID: $enrollmentId for 2024-I");

        // Create Enrollment Details (Matricula Asignaturas)
        // Get Asignaturas (Classes) for Semester 2024-I (ID 1)
        $classes = $connection->table('asignaturas')->where('semester_id', 1)->get();

        foreach ($classes as $class) {
            $connection->table('matriculas_asignaturas')->insert([
                'student_id' => $studentId,
                'subject_id' => $class->id, // References asignaturas.id
                'nota' => 0,
                'estado' => 1,
                'created_at' => '2024-03-15 10:05:00',
                'updated_at' => '2024-03-15 10:05:00',
            ]);
        }

        $this->command->info("Enrolled Student in " . $classes->count() . " classes.");

        // Create Grades (Notas)
        // For each enrolled class, create 3 grades (Parcial, Final, Practica)
        $enrollmentDetails = $connection->table('matriculas_asignaturas')->where('student_id', $studentId)->get();
        $gradeTypes = $connection->table('tipos_notas')->get();

        // Ensure dummy activity exists
        $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->table('actividades')->truncate();
        $connection->table('actividades')->insert([
            'id' => 1,
            'nombre' => 'Dummy Activity',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ensure dummy clases_asignaturas exists
        $connection->table('clases_asignaturas')->truncate();
        // Insert dummy class for each asignatura
        foreach ($enrollmentDetails as $detail) {
             // Check if exists
             if (!$connection->table('clases_asignaturas')->where('id', $detail->subject_id)->exists()) {
                 $connection->table('clases_asignaturas')->insert([
                    'id' => $detail->subject_id, // Use same ID for simplicity
                    'schedule_id' => 1, // Dummy
                    'nombre' => 'Clase Dummy',
                    'dia' => 'Lunes',
                    'fecha' => now(),
                    'estado' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                 ]);
             }
        }
        $connection->statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($enrollmentDetails as $detail) {
            foreach ($gradeTypes as $type) {
                // Create 'notas' definition
                $gradeId = $connection->table('notas')->insertGetId([
                    'activity_id' => 1, // Dummy
                    'gradetype_id' => $type->id,
                    'subjectclass_id' => $detail->subject_id, // Link to Asignatura
                    'nombre' => $type->nombre . ' - ' . $detail->subject_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create 'notas_estudiantes' score
                $connection->table('notas_estudiantes')->insert([
                    'subjectenrollment_id' => $detail->id, // Link to Matricula Asignatura
                    'grade_id' => $gradeId,
                    'asistencia' => 1,
                    'nota' => rand(10, 20), // Random score 10-20
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info("Created Grades for Student.");

        // --- Treasury Seeding ---
        $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->table('ts_conceptos')->truncate();
        $connection->table('ts_estados_cuentas')->truncate();
        $connection->table('ts_pagos_personas')->truncate();
        
        // Seed Dependencies
        $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        $connection->table('ts_instituciones')->truncate();
        $connection->table('ts_instituciones')->insert(['institucion' => 1, 'nombre' => 'Institucion Test', 'ruc' => '20123456789']);
        
        $connection->table('ts_tipos_documentos')->truncate();
        $connection->table('ts_tipos_documentos')->insert(['tipo_documento' => 1, 'descripcion' => 'Boleta']);
        
        // 1. Create Concepts
        $connection->table('ts_conceptos')->insert([
            'concepto' => 'Matrícula 2024-I',
            'descripcion' => 'Matrícula Regular',
            'estado' => 1,
        ]);

        $connection->table('ts_conceptos')->insert([
            'concepto' => 'Pensión Marzo 2024',
            'descripcion' => 'Pensión Mensual',
            'estado' => 1,
        ]);

        $this->command->info("Created Treasury Concepts.");

        try {
            // 2. Create Debts (Estados de Cuenta)
            // Debt 1: Matricula (Paid)
            $connection->table('ts_estados_cuentas')->insert([
                'institucion' => 1,
                'semestre_id' => 1,
                'programa_id' => 1,
                'tipo_documento' => 1,
                'num_comprobante' => 'OP-10001',
                'num_cuota' => 1,
                'num_parte' => 1,
                'user_id' => $userId,
                'concepto' => 'Matrícula 2024-I',
                'monto_total' => 300.00,
                'fecha_vencimiento' => '2024-03-15',
                'estado' => 1, // Paid
                'fecha_emision' => '2024-03-01',
            ]);

            $this->command->info("Inserted Debt 1");

            // Debt 2: Pension (Pending)
            $connection->table('ts_estados_cuentas')->insert([
                'institucion' => 1,
                'semestre_id' => 1,
                'programa_id' => 1,
                'tipo_documento' => 1,
                'num_comprobante' => 'OP-10002',
                'num_cuota' => 2,
                'num_parte' => 1,
                'user_id' => $userId,
                'concepto' => 'Pensión Marzo 2024',
                'monto_total' => 500.00,
                'fecha_vencimiento' => '2024-03-30',
                'estado' => 0, // Pending
                'fecha_emision' => '2024-03-01',
            ]);

            $this->command->info("Inserted Debt 2");

            // 3. Create Payments (Pagos Personas)
            // Payment for Matricula
            $connection->table('ts_pagos_personas')->insert([
                'tipo_documento' => 1,
                'num_comprobante' => 'OP-10001',
                'user_id' => $userId,
                'monto_total' => 300.00,
                'fecha_pago' => '2024-03-10 10:00:00',
                'fecha_emision' => '2024-03-10 10:00:00',
            ]);

            $this->command->info("Inserted Payment");

        } catch (\Exception $e) {
            $this->command->error("Treasury Seeding Error: " . $e->getMessage());
        }
        
        $connection->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
