<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['term', 'details.course']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $enrollments = $query->orderBy('date', 'desc')->get();

        return EnrollmentResource::collection($enrollments);
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['term', 'details.course']);
        return new EnrollmentResource($enrollment);
    }
}
