<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Payment::with(['student.user', 'debt.paymentConcept'])
            ->where('status', 'paid');

        if ($this->startDate) {
            $query->whereDate('paid_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('paid_at', '<=', $this->endDate);
        }

        return $query->orderBy('paid_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'N° Operación',
            'Estudiante',
            'Documento',
            'Concepto',
            'Monto (S/)',
            'Método',
            'Banco',
            'Estado',
            'Fecha de Pago',
        ];
    }

    /**
     * @param mixed $payment
     * @return array
     */
    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->operation_number ?? 'N/A',
            $payment->student->user->name ?? 'N/A',
            ($payment->student->user->document_type ?? 'DNI') . ' - ' . ($payment->student->user->document_number ?? 'N/A'),
            $payment->debt->paymentConcept->name ?? 'Pago General',
            number_format($payment->amount, 2),
            ucfirst($payment->payment_method),
            $payment->bank ?? 'N/A',
            $this->getStatusLabel($payment->status),
            $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A',
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
            'paid' => 'Pagado',
            'pending' => 'Pendiente',
            'cancelled' => 'Cancelado',
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}
