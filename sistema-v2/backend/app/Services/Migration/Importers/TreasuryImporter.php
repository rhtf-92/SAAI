<?php

namespace App\Services\Migration\Importers;

use App\Services\Migration\LegacyImporter;
use App\Models\PaymentConcept;
use App\Models\Debt;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TreasuryImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'ts_conceptos';
    }

    public function import()
    {
        $this->importConcepts();
        $this->importDebts();
        $this->importPayments();
    }

    protected function processRow($row): void
    {
        // Not used as we override run()
    }

    private function importConcepts()
    {
        $this->command->info('Importing Payment Concepts...');
        $concepts = DB::connection('mysql_legacy')->table('ts_conceptos')->get();

        foreach ($concepts as $row) {
            PaymentConcept::updateOrCreate(
                ['name' => $row->concepto],
                [
                    'amount' => 0, // No amount in concept table
                    'is_recurring' => false,
                    'code' => 'CPT-' . substr(md5($row->concepto), 0, 8),
                ]
            );
        }
        $this->command->info("Imported {$concepts->count()} concepts.");
    }

    private function importDebts()
    {
        $this->command->info('Importing Debts (Estados de Cuenta)...');
        // ts_estados_cuentas: user_id, concepto (string), monto_total, fecha_vencimiento, estado
        // We need to map 'concepto' string to a PaymentConcept ID if possible, or create a generic one.
        // Or just store the description.

        $debts = DB::connection('mysql_legacy')->table('ts_estados_cuentas')->get();
        $count = 0;

        foreach ($debts as $row) {
            // Find student by user_id (legacy user_id maps to V2 user_id, which links to Student)
            // But wait, our StudentImporter linked legacy student_id to V2 student_id.
            // We need to find the V2 Student that corresponds to the legacy User ID.
            
            // Legacy: ts_estados_cuentas.user_id -> usuarios.id
            // V2: users.id == legacy usuarios.id (preserved)
            // V2: students.user_id == users.id
            
            $student = Student::where('user_id', $row->user_id)->first();

            if (!$student) {
                continue; 
            }

            // Try to match concept by name
            $concept = PaymentConcept::where('name', $row->concepto)->first();

            Debt::create([
                'student_id' => $student->id,
                'payment_concept_id' => $concept ? $concept->id : null,
                'description' => $row->concepto,
                'amount' => $row->monto_total,
                'due_date' => $row->fecha_vencimiento,
                'status' => $this->mapStatus($row->estado),
                'created_at' => $row->fecha_emision ?? now(),
            ]);
            $count++;
        }
        $this->command->info("Imported $count debts.");
    }

    private function importPayments()
    {
        $this->command->info('Importing Payments (Pagos Personas)...');
        $payments = DB::connection('mysql_legacy')->table('ts_pagos_personas')->get();
        $count = 0;

        foreach ($payments as $row) {
            $student = Student::where('user_id', $row->user_id)->first();

            if (!$student) {
                continue;
            }

            Payment::create([
                'student_id' => $student->id,
                'debt_id' => null, // Hard to link directly without a common ID
                'amount' => $row->monto_total,
                'paid_at' => $row->fecha_pago ?? now(),
                'payment_method' => 'CASH', // Default
                'operation_number' => $row->num_comprobante,
                'observation' => 'Imported from Legacy',
                'created_at' => $row->fecha_emision ?? now(),
            ]);
            $count++;
        }
        $this->command->info("Imported $count payments.");
    }

    private function mapStatus($legacyStatus)
    {
        // 0: Pending, 1: Paid? (Need to verify, assuming standard)
        return match ($legacyStatus) {
            1 => 'paid',
            0 => 'pending',
            2 => 'cancelled',
            default => 'pending',
        };
    }
}
