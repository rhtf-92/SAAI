<?php

namespace App\Http\Controllers\Api\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Ubigeo;
use App\Http\Requests\Academic\StoreUbigeoRequest;
use App\Http\Requests\Academic\UpdateUbigeoRequest;
use App\Http\Resources\Academic\UbigeoResource;

class UbigeoController extends Controller
{
    public function index()
    {
        return UbigeoResource::collection(Ubigeo::all());
    }

    public function store(StoreUbigeoRequest $request)
    {
        $ubigeo = Ubigeo::create($request->validated());
        return new UbigeoResource($ubigeo);
    }

    public function show(Ubigeo $ubigeo)
    {
        return new UbigeoResource($ubigeo);
    }

    public function update(UpdateUbigeoRequest $request, Ubigeo $ubigeo)
    {
        $ubigeo->update($request->validated());
        return new UbigeoResource($ubigeo);
    }

    public function destroy(Ubigeo $ubigeo)
    {
        $ubigeo->delete();
        return response()->noContent();
    }
}
