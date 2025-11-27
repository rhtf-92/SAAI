<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Generate Study Certificate PDF.
     * GET /api/v1/reports/study-certificate
     */
    public function studyCertificate(Request $request)
    {
        $userId = $request->user()->id;

        // Find student by user_id
        $student = \App\Models\Student::where('user_id', $userId)->first();

        if (!$student) {
            return response()->json([
                'message' => 'No se encontró información de estudiante para este usuario'
            ], 404);
        }

        try {
            return $this->reportService->generateStudyCertificate($student->id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar certificado de estudios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Enrollment Certificate PDF.
     * GET /api/v1/reports/enrollment-certificate/{id}
     */
    public function enrollmentCertificate(Request $request, $id)
    {
        $userId = $request->user()->id;

        // Verify enrollment belongs to user
        $enrollment = \App\Models\Enrollment::with('student')
            ->whereHas('student', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->find($id);

        if (!$enrollment) {
            return response()->json([
                'message' => 'Matrícula no encontrada o no autorizada'
            ], 404);
        }

        try {
            return $this->reportService->generateEnrollmentCertificate($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar constancia de matrícula',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Payment Receipt PDF.
     * GET /api/v1/reports/payment-receipt/{id}
     */
    public function paymentReceipt(Request $request, $id)
    {
        $userId = $request->user()->id;

        // Verify payment belongs to user
        $payment = \App\Models\Payment::with('student')
            ->whereHas('student', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->find($id);

        if (!$payment) {
            return response()->json([
                'message' => 'Pago no encontrado o no autorizado'
            ], 404);
        }

        try {
            return $this->reportService->generatePaymentReceipt($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar boleta de pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Students to Excel.
     * GET /api/v1/reports/export-students
     */
    public function exportStudents(Request $request)
    {
        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\StudentsExport(),
                'estudiantes_' . now()->format('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar estudiantes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Payments to Excel.
     * GET /api/v1/reports/export-payments
     */
    public function exportPayments(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\PaymentsExport($startDate, $endDate),
                'pagos_' . now()->format('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar pagos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Enrollments to Excel.
     * GET /api/v1/reports/export-enrollments
     */
    public function exportEnrollments(Request $request)
    {
        $termId = $request->query('term_id');

        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\EnrollmentsExport($termId),
                'matriculas_' . now()->format('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar matrículas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Attendance Report PDF.
     * GET /api/v1/reports/attendance-report/{courseId}
     */
    public function attendanceReport(Request $request, $courseId)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        try {
            return $this->reportService->generateAttendanceReport($courseId, $startDate, $endDate);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de asistencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
