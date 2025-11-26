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
    });
});
