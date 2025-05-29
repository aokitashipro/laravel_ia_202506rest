<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // 追記
use App\Http\Resources\ProductResource; // 追記
use Illuminate\Validation\ValidationException; // 追記


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // シンプル
        // return response()->json(
        //     ['hello world!'], 200
        // );

        // jsonで返却
        // $products = Product::all(); 
        // return response()->json([
        //     'data' => $products
        // ], 200);

        // リソースを使いフォーマットを合わせて返却
        $products = Product::all();
        return ProductResource::collection($products);




    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // tryで囲みバリデーションをかける
        try{
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:50',
            'price' => 'required|numeric',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return  (new ProductResource($product))
        ->additional(['message' => '商品情報が登録されました'])
        ->response()
        ->setStatusCode(201);

        // バリデーションエラー
        } catch (ValidationException $e) {
        return response()->json([
            'errors' => $e->errors()
        ], 422);
    }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $product = Product::findOrFail($id);
        // return response()->json([
        //     'data' => $product
        // ], 200);

          // リソースクラスを使うように変更
        $product = Product::findOrFail($id);
        return new ProductResource($product);


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
