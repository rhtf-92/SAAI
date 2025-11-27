<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Academic Routes
        Route::prefix('academic')->group(function () {
            Route::apiResource('ubigeos', \App\Http\Controllers\Api\Academic\UbigeoController::class);
            Route::apiResource('programs', \App\Http\Controllers\Api\Academic\ProgramController::class);
            Route::apiResource('plans', \App\Http\Controllers\Api\Academic\PlanController::class);
            Route::apiResource('semesters', \App\Http\Controllers\Api\Academic\SemesterController::class);
            Route::apiResource('classrooms', \App\Http\Controllers\Api\Academic\ClassroomController::class);
        });

        // Student Routes
        Route::apiResource('students', \App\Http\Controllers\Api\StudentController::class);
        Route::apiResource('enrollments', \App\Http\Controllers\Api\EnrollmentController::class)->only(['index', 'show']);

        // Notifications Routes
        Route::prefix('notifications')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
            Route::get('/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
            Route::post('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
            Route::post('/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'destroy']);
        });

        // Reports Routes
        Route::prefix('reports')->group(function () {
            // PDF exports
            Route::get('/study-certificate', [\App\Http\Controllers\Api\ReportController::class, 'studyCertificate']);
            Route::get('/enrollment-certificate/{id}', [\App\Http\Controllers\Api\ReportController::class, 'enrollmentCertificate']);
            Route::get('/payment-receipt/{id}', [\App\Http\Controllers\Api\ReportController::class, 'paymentReceipt']);
            Route::get('/attendance-report/{courseId}', [\App\Http\Controllers\Api\ReportController::class, 'attendanceReport']);

            // Excel exports
            Route::get('/export-students', [\App\Http\Controllers\Api\ReportController::class, 'exportStudents']);
            Route::get('/export-payments', [\App\Http\Controllers\Api\ReportController::class, 'exportPayments']);
            Route::get('/export-enrollments', [\App\Http\Controllers\Api\ReportController::class, 'exportEnrollments']);
        });

        // Attendance Routes
        Route::prefix('attendances')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\AttendanceController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\AttendanceController::class, 'store']);
            Route::post('/batch', [\App\Http\Controllers\Api\AttendanceController::class, 'storeBatch']);
            Route::get('/course/{courseId}', [\App\Http\Controllers\Api\AttendanceController::class, 'byCourse']);
            Route::get('/course/{courseId}/summary', [\App\Http\Controllers\Api\AttendanceController::class, 'courseSummary']);
            Route::get('/student/{studentId}', [\App\Http\Controllers\Api\AttendanceController::class, 'byStudent']);
            Route::get('/percentage/{enrollmentCourseId}', [\App\Http\Controllers\Api\AttendanceController::class, 'percentage']);
            Route::get('/{id}', [\App\Http\Controllers\Api\AttendanceController::class, 'show']);
            Route::put('/{id}', [\App\Http\Controllers\Api\AttendanceController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\AttendanceController::class, 'destroy']);
        });
    });
});
