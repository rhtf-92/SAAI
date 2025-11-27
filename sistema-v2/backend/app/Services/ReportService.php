<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Payment;

class ReportService
{
    /**
     * Generate Study Certificate PDF.
     *
     * @param int $studentId
     * @return \Illuminate\Http\Response
     */
    public function generateStudyCertificate($studentId)
    {
        $student = Student::with(['user', 'enrollments.grades', 'enrollments.plan.program'])
            ->findOrFail($studentId);

        // Calculate overall average
        $allGrades = $student->enrollments->flatMap(function ($enrollment) {
            return $enrollment->grades;
        });

        $average = $allGrades->avg('grade') ?? 0;

        $data = [
            'student' => $student,
            'grades' => $allGrades,
            'average' => round($average, 2),
            'date' => now()->format('d/m/Y'),
        ];

        return $this->generatePDF('reports.study-certificate', $data, 'certificado-estudios.pdf');
    }

    /**
     * Generate Enrollment Certificate PDF.
     *
     * @param int $enrollmentId
     * @return \Illuminate\Http\Response
     */
    public function generateEnrollmentCertificate($enrollmentId)
    {
        $enrollment = Enrollment::with([
            'student.user',
            'plan.program',
            'term',
            'enrollmentCourses.course'
        ])->findOrFail($enrollmentId);

        // Calculate total credits
        $totalCredits = $enrollment->enrollmentCourses->sum(function ($ec) {
            return $ec->course->credits ?? 0;
        });

        $data = [
            'enrollment' => $enrollment,
            'student' => $enrollment->student,
            'totalCredits' => $totalCredits,
            'date' => now()->format('d/m/Y'),
        ];

        return $this->generatePDF('reports.enrollment-certificate', $data, 'constancia-matricula.pdf');
    }

    /**
     * Generate Payment Receipt PDF.
     *
     * @param int $paymentId
     * @return \Illuminate\Http\Response
     */
    public function generatePaymentReceipt($paymentId)
    {
        $payment = Payment::with([
            'student.user',
            'debt.paymentConcept'
        ])->findOrFail($paymentId);

        $data = [
            'payment' => $payment,
            'student' => $payment->student,
            'concept' => $payment->debt->paymentConcept ?? null,
            'date' => now()->format('d/m/Y H:i'),
        ];

        return $this->generatePDF('reports.payment-receipt', $data, 'boleta-pago.pdf');
    }

    /**
     * Helper method to generate PDF from view.
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    private function generatePDF($view, $data, $filename)
    {
        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        return $pdf->download($filename);

        // Para mostrar en el navegador en lugar de descargar, usar:
        // return $pdf->stream($filename);
    }

    /**
     * Stream PDF (show in browser).
     *
     * @param string $view
     * @param array $data
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function streamPDF($view, $data, $filename)
    {
        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        return $pdf->stream($filename);
    }

    /**
     * Generate Attendance Report PDF.
     *
     * @param int $courseId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return \Illuminate\Http\Response
     */
    public function generateAttendanceReport($courseId, $startDate = null, $endDate = null)
    {
        $course = \App\Models\Course::findOrFail($courseId);

        // Get all attendances for this course
        $query = \App\Models\Attendance::with(['enrollmentCourse.enrollment.student.user'])
            ->byCourse($courseId);

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        $attendances = $query->orderBy('date')->get();

        // Get unique dates
        $dates = $attendances->pluck('date')->unique()->sort()->values()->toArray();

        // Get all enrolled students in this course
        $enrollmentCourses = \App\Models\EnrollmentCourse::with(['enrollment.student.user'])
            ->where('course_id', $courseId)
            ->get();

        $students = [];
        $totalPercentage = 0;

        foreach ($enrollmentCourses as $ec) {
            $studentAttendances = $attendances->where('enrollment_course_id', $ec->id);

            // Calculate percentage
            $total = $studentAttendances->count();
            $present = $studentAttendances->whereIn('status', ['present', 'late'])->count();
            $percentage = $total > 0 ? ($present / $total) * 100 : 0;
            $totalPercentage += $percentage;

            $students[] = [
                'student' => $ec->enrollment->student,
                'attendances' => $studentAttendances,
                'percentage' => $percentage,
            ];
        }

        $averagePercentage = count($students) > 0 ? $totalPercentage / count($students) : 0;

        $data = [
            'course' => $course,
            'term' => null, // TODO: Get term from enrollment
            'dates' => $dates,
            'students' => $students,
            'averagePercentage' => $averagePercentage,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedDate' => now()->format('d/m/Y'),
        ];

        return $this->generatePDF('reports.attendance-report', $data, 'reporte-asistencia.pdf');
    }
}
