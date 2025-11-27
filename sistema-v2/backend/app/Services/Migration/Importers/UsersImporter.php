<?php

namespace App\Services\Migration\Importers;

use App\Services\Migration\LegacyImporter;
use App\Models\User;
use App\Models\Academic\Ubigeo;
use Illuminate\Support\Facades\Hash;

class UsersImporter extends LegacyImporter
{
    protected function getSourceTable(): string
    {
        return 'usuarios';
    }

    protected function processRow($row): void
    {
        // Check if user already exists by email or document_number
        // Legacy 'correo' might be empty, so check 'nroidenti' as well
        
        $existingUser = null;
        
        if (!empty($row->correo)) {
            $existingUser = User::where('email', $row->correo)->first();
        }

        if (!$existingUser && !empty($row->nroidenti)) {
             // Assuming we store document_number in personal_info json or separate table?
             // In Phase 5 we added 'document_number' to users table directly via migration 'add_personal_info_to_users_table'
             $existingUser = User::where('document_number', $row->nroidenti)->first();
        }

        if ($existingUser) {
            // Update existing? Or skip? For now, skip to avoid overwriting
            return;
        }

        // Map Legacy Role ID to Spatie Role Name
        // Based on inspection: 1 = Admin. We need to guess others or default to Student.
        $roleName = match ($row->usertype_id) {
            1 => 'admin',
            2 => 'teacher', // Assumption
            3 => 'student', // Assumption
            default => 'student',
        };

        // Map Document Type
        $docType = match ($row->identificationtype_id) {
            1, 2 => 'DNI',
            default => 'DNI',
        };

        // Create or Update User
        $user = User::updateOrCreate(
            ['id' => $row->id],
            [
                'name' => $row->nombres . ' ' . $row->apellido_pa . ' ' . $row->apellido_ma,
                'email' => !empty($row->correo) ? $row->correo : 'user_' . $row->id . '@sistema-legacy.com',
                'password' => $row->password,
                'document_type' => $docType,
                'document_number' => $row->nroidenti,
                'birthdate' => $row->fecnac,
                'phone' => $row->celular ?? $row->telefono,
                'address' => $row->direccion,
                'ubigeo_id' => $row->ubigeo_id,
                'is_active' => $row->estado == 1,
            ]
        );

        // Assign Role
        $user->assignRole($roleName);
    }
}
