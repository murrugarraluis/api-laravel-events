<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityCreateRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $cities = City::all();
        return CityResource::collection($cities);
    }

    public function show(City $city): CityResource
    {
        return new CityResource($city);
    }

    public function store(CityCreateRequest $request): CityResource
    {
        $city = City::create($request->all());
        return (new CityResource($city))->additional([
            'message' => 'City created.'
        ]);
    }

    public function update(CityUpdateRequest $request, City $city): CityResource
    {
        $city->update($request->all());
        $city->save();
        return (new CityResource($city))->additional([
            'message' => 'City updated.'
        ]);
    }

    public function destroy(City $city): JsonResponse
    {
        $city->delete();
        return response()->json(['message' => 'City deleted.']);
    }
}
