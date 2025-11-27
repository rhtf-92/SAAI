<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display a listing of attendances.
     * GET /api/v1/attendances
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['enrollment Courses.enrollment.student.user', 'enrollmentCourse.course', 'creator']);

        // Apply filters
        if ($request->has('course_id')) {
            $query->byCourse($request->course_id);
        }

        if ($request->has('student_id')) {
            $query->byStudent($request->student_id);
        }

        if ($request->has('date')) {
            $query->byDate($request->date);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate($request->input('per_page', 15));

        return AttendanceResource::collection($attendances);
    }

    /**
     * Store a new attendance record.
     * POST /api/v1/attendances
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enrollment_course_id' => 'required|exists:enrollment_courses,id',
            'date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:present,absent,late,justified',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $attendance = $this->attendanceService->createAttendance(
                $request->enrollment_course_id,
                $request->date,
                $request->status,
                $request->notes,
                $request->user()->id
            );

            return new AttendanceResource($attendance);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear asistencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store attendance for entire session (batch).
     * POST /api/v1/attendances/batch
     */
    public function storeBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date|before_or_equal:today',
            'attendances' => 'required|array|min:1',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,justified',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $results = $this->attendanceService->recordAttendanceForSession(
                $request->course_id,
                $request->date,
                $request->attendances,
                $request->user()->id
            );

            return response()->json([
                'message' => 'Asistencia registrada exitosamente',
                'count' => count($results),
                'data' => AttendanceResource::collection($results)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar asistencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified attendance.
     * GET /api/v1/attendances/{id}
     */
    public function show($id)
    {
        $attendance = Attendance::with(['enrollmentCourse.enrollment.student.user', 'enrollmentCourse.course', 'creator'])
            ->findOrFail($id);

        return new AttendanceResource($attendance);
    }

    /**
     * Update the specified attendance.
     * PUT /api/v1/attendances/{id}
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|date|before_or_equal:today',
            'status' => 'sometimes|in:present,absent,late,justified',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->attendanceService->updateAttendance($id, $request->only(['date', 'status', 'notes']));

            $attendance = Attendance::with(['enrollmentCourse.enrollment.student.user', 'enrollmentCourse.course', 'creator'])
                ->findOrFail($id);

            return new AttendanceResource($attendance);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar asistencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified attendance.
     * DELETE /api/v1/attendances/{id}
     */
    public function destroy($id)
    {
        try {
            $this->attendanceService->deleteAttendance($id);
            return response()->json(['message' => 'Asistencia eliminada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar asistencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendances by course.
     * GET /api/v1/attendances/course/{courseId}
     */
    public function byCourse(Request $request, $courseId)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $attendances = $this->attendanceService->getAttendancesByCourse($courseId, $startDate, $endDate);

        return AttendanceResource::collection($attendances);
    }

    /**
     * Get attendances by student.
     * GET /api/v1/attendances/student/{studentId}
     */
    public function byStudent(Request $request, $studentId)
    {
        $courseId = $request->query('course_id');
        $attendances = $this->attendanceService->getAttendancesByStudent($studentId, $courseId);

        return AttendanceResource::collection($attendances);
    }

    /**
     * Get attendance percentage for an enrollment_course.
     * GET /api/v1/attendances/percentage/{enrollmentCourseId}
     */
    public function percentage($enrollmentCourseId)
    {
        $stats = $this->attendanceService->getAttendancePercentage($enrollmentCourseId);

        return response()->json($stats);
    }

    /**
     * Get course attendance summary.
     * GET /api/v1/attendances/course/{courseId}/summary
     */
    public function courseSummary(Request $request, $courseId)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $summary = $this->attendanceService->getCourseSummary($courseId, $startDate, $endDate);

        return response()->json($summary);
    }
}
