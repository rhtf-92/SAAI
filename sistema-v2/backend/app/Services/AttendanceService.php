<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\EnrollmentCourse;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Create a new attendance record.
     *
     * @param int $enrollmentCourseId
     * @param string $date
     * @param string $status
     * @param string|null $notes
     * @param int|null $createdBy
     * @return Attendance
     */
    public function createAttendance($enrollmentCourseId, $date, $status, $notes = null, $createdBy = null)
    {
        return Attendance::create([
            'enrollment_course_id' => $enrollmentCourseId,
            'date' => $date,
            'status' => $status,
            'notes' => $notes,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Update an attendance record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateAttendance($id, array $data)
    {
        $attendance = Attendance::findOrFail($id);
        return $attendance->update($data);
    }

    /**
     * Delete an attendance record.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        return $attendance->delete();
    }

    /**
     * Record attendance for  entire session (batch).
     *
     * @param int $courseId
     * @param string $date
     * @param array $attendanceData
     * @param int|null $createdBy
     * @return array
     */
    public function recordAttendanceForSession($courseId, $date, array $attendanceData, $createdBy = null)
    {
        $results = [];

        DB::beginTransaction();
        try {
            foreach ($attendanceData as $data) {
                // Find enrollment_course_id for this student in this course
                $enrollmentCourse = EnrollmentCourse::whereHas('enrollment', function ($q) use ($data) {
                    $q->where('student_id', $data['student_id']);
                })
                    ->where('course_id', $courseId)
                    ->first();

                if ($enrollmentCourse) {
                    // Update or create attendance
                    $attendance = Attendance::updateOrCreate(
                        [
                            'enrollment_course_id' => $enrollmentCourse->id,
                            'date' => $date,
                        ],
                        [
                            'status' => $data['status'],
                            'notes' => $data['notes'] ?? null,
                            'created_by' => $createdBy,
                        ]
                    );

                    $results[] = $attendance;
                }
            }

            DB::commit();
            return $results;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get attendances for a course.
     *
     * @param int $courseId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAttendancesByCourse($courseId, $startDate = null, $endDate = null)
    {
        $query = Attendance::with(['enrollmentCourse.enrollment.student.user', 'creator'])
            ->byCourse($courseId);

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get attendances for a student.
     *
     * @param int $studentId
     * @param int|null $courseId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAttendancesByStudent($studentId, $courseId = null)
    {
        $query = Attendance::with(['enrollmentCourse.course', 'creator'])
            ->byStudent($studentId);

        if ($courseId) {
            $query->byCourse($courseId);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Calculate attendance percentage for an enrollment_course.
     *
     * @param int $enrollmentCourseId
     * @return array
     */
    public function getAttendancePercentage($enrollmentCourseId)
    {
        $attendances = Attendance::where('enrollment_course_id', $enrollmentCourseId)->get();

        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $justified = $attendances->where('status', 'justified')->count();

        // Consider 'present' + 'late' as effective attendance
        $effectiveAttendance = $present + $late;
        $percentage = $total > 0 ? round(($effectiveAttendance / $total) * 100, 2) : 0;

        return [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'justified' => $justified,
            'effective_attendance' => $effectiveAttendance,
            'percentage' => $percentage,
            'status' => $this->getAttendanceStatus($percentage),
        ];
    }

    /**
     * Get attendance status based on percentage.
     *
     * @param float $percentage
     * @return string
     */
    private function getAttendanceStatus($percentage)
    {
        if ($percentage < 70) {
            return 'critical'; // Riesgo de desaprobar
        } elseif ($percentage < 85) {
            return 'warning'; // En observación
        } else {
            return 'good'; // Normal
        }
    }

    /**
     * Get attendance  summary for a course.
     *
     * @param int $courseId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getCourseSummary($courseId, $startDate = null, $endDate = null)
    {
        $attendances = $this->getAttendancesByCourse($courseId, $startDate, $endDate);

        $total = $attendances->count();
        $byStatus = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'justified' => $attendances->where('status', 'justified')->count(),
        ];

        return [
            'total_records' => $total,
            'by_status' => $byStatus,
            'dates' => $attendances->pluck('date')->unique()->values()->toArray(),
        ];
    }
}
