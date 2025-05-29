<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SportingGoodRequest;
use App\Http\Resources\SportingGoodResource;
use App\Http\Resources\SportingGoodListResource;
use App\Models\SportingGood;

class SportingGoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sportingGoods = SportingGood::all();
        return SportingGoodListResource::collection($sportingGoods);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SportingGoodRequest $request)
    {
        $sportingGood = SportingGood::create($request->validated());

        return  (new SportingGoodResource($sportingGood))
        ->additional(['message' => '商品情報が登録されました'])
        ->response()
        ->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sportingGood = SportingGood::findOrFail($id);
        return new SportingGoodResource($sportingGood);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
