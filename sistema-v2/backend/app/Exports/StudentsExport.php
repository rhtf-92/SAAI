<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Student::with(['user', 'enrollments.plan.program'])
            ->where('is_active', true)
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Código',
            'Nombre Completo',
            'Email',
            'Documento',
            'Teléfono',
            'Programa',
            'Estado',
            'Fecha de Registro',
        ];
    }

    /**
     * @param mixed $student
     * @return array
     */
    public function map($student): array
    {
        return [
            $student->id,
            str_pad($student->id, 8, '0', STR_PAD_LEFT),
            $student->user->name ?? 'N/A',
            $student->user->email ?? 'N/A',
            ($student->user->document_type ?? 'DNI') . ' - ' . ($student->user->document_number ?? 'N/A'),
            $student->user->phone ?? 'N/A',
            $student->enrollments->first()->plan->program->name ?? 'N/A',
            $student->is_active ? 'Activo' : 'Inactivo',
            $student->created_at->format('d/m/Y'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
