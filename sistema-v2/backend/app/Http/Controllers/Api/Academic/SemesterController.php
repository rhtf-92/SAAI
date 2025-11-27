<?php

namespace App\Http\Controllers\Api\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Semester;
use App\Http\Requests\Academic\StoreSemesterRequest;
use App\Http\Requests\Academic\UpdateSemesterRequest;
use App\Http\Resources\Academic\SemesterResource;

class SemesterController extends Controller
{
    public function index()
    {
        return SemesterResource::collection(Semester::orderBy('year', 'desc')->orderBy('number', 'desc')->get());
    }

    public function store(StoreSemesterRequest $request)
    {
        $semester = Semester::create($request->validated());
        return new SemesterResource($semester);
    }

    public function show(Semester $semester)
    {
        return new SemesterResource($semester);
    }

    public function update(UpdateSemesterRequest $request, Semester $semester)
    {
        $semester->update($request->validated());
        return new SemesterResource($semester);
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();
        return response()->noContent();
    }
}
