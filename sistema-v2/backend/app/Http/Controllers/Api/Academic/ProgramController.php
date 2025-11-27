<?php

namespace App\Http\Controllers\Api\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Program;
use App\Http\Requests\Academic\StoreProgramRequest;
use App\Http\Requests\Academic\UpdateProgramRequest;
use App\Http\Resources\Academic\ProgramResource;

class ProgramController extends Controller
{
    public function index()
    {
        return ProgramResource::collection(Program::all());
    }

    public function store(StoreProgramRequest $request)
    {
        $program = Program::create($request->validated());
        return new ProgramResource($program);
    }

    public function show(Program $program)
    {
        return new ProgramResource($program->load('plans'));
    }

    public function update(UpdateProgramRequest $request, Program $program)
    {
        $program->update($request->validated());
        return new ProgramResource($program);
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return response()->noContent();
    }
}
