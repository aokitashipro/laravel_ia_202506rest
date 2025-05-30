<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cafe;
use App\Http\Resources\CafeResource;

class CafeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // リソースを使いフォーマットを合わせて返却
        $cafes = Cafe::all();
        return CafeResource::collection($cafes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cafe = Cafe::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
        ]);

        return  (new CafeResource($cafe))
        ->additional(['message' => 'カフェ情報が登録されました'])
        ->response()
        ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // リソースクラスを使うように変更
        $cafe = Cafe::findOrFail($id);
        return new CafeResource($cafe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cafe = Cafe::findOrFail($id);
        $cafe->name = $request->name;
        $cafe->price = $request->price;
        $cafe->category = $request->category;
        $cafe->save();

        return  (new CafeResource($cafe))
        ->additional(['message' => 'カフェ情報が更新されました'])
        ->response()
        ->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cafe = Book::findOrFail($id);
        $cafe->delete();

        return  (new CafeResource($cafe))
        ->additional(['message' => '商品情報が削除されました'])
        ->response()
        ->setStatusCode(201);
    }
}
