<?php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EnrollmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $termId;

    public function __construct($termId = null)
    {
        $this->termId = $termId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Enrollment::with([
            'student.user',
            'plan.program',
            'term',
            'enrollmentCourses'
        ]);

        if ($this->termId) {
            $query->where('term_id', $this->termId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Código Matrícula',
            'Estudiante',
            'Documento',
            'Programa',
            'Plan de Estudios',
            'Periodo',
            'Total Cursos',
            'Estado',
            'Fecha de Matrícula',
        ];
    }

    /**
     * @param mixed $enrollment
     * @return array
     */
    public function map($enrollment): array
    {
        return [
            $enrollment->id,
            str_pad($enrollment->id, 6, '0', STR_PAD_LEFT),
            $enrollment->student->user->name ?? 'N/A',
            ($enrollment->student->user->document_type ?? 'DNI') . ' - ' . ($enrollment->student->user->document_number ?? 'N/A'),
            $enrollment->plan->program->name ?? 'N/A',
            $enrollment->plan->name ?? 'N/A',
            $enrollment->term->name ?? 'N/A',
            $enrollment->enrollmentCourses->count(),
            $this->getStatusLabel($enrollment->status),
            $enrollment->date ? $enrollment->date->format('d/m/Y') : ($enrollment->created_at->format('d/m/Y')),
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

    /**
     * Get status label in Spanish
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'active' => 'Activa',
            'inactive' => 'Inactiva',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}
