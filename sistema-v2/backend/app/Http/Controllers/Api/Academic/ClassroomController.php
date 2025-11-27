<?php

namespace App\Http\Controllers\Api\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Classroom;
use App\Http\Requests\Academic\StoreClassroomRequest;
use App\Http\Requests\Academic\UpdateClassroomRequest;
use App\Http\Resources\Academic\ClassroomResource;

class ClassroomController extends Controller
{
    public function index()
    {
        return ClassroomResource::collection(Classroom::all());
    }

    public function store(StoreClassroomRequest $request)
    {
        $classroom = Classroom::create($request->validated());
        return new ClassroomResource($classroom);
    }

    public function show(Classroom $classroom)
    {
        return new ClassroomResource($classroom);
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return new ClassroomResource($classroom);
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return response()->noContent();
    }
}
