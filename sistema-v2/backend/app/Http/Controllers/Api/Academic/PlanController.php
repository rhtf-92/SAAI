<?php

namespace App\Http\Controllers\Api\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Plan;
use App\Http\Requests\Academic\StorePlanRequest;
use App\Http\Requests\Academic\UpdatePlanRequest;
use App\Http\Resources\Academic\PlanResource;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('program')->get();
        return PlanResource::collection($plans);
    }

    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create($request->validated());
        return new PlanResource($plan->load('program'));
    }

    public function show(Plan $plan)
    {
        return new PlanResource($plan->load(['program', 'academicPeriods.courses']));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());
        return new PlanResource($plan->load('program'));
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->noContent();
    }
}
