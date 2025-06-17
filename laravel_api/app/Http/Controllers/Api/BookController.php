<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Http\Requests\BookStoreRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // リソースを使いフォーマットを合わせて返却
        $books = Book::all();
        return BookResource::collection($books);

    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //バリデーション直書き
    //     try {
    //         $validated = $request->validate([
    //             'title' => 'required|string|min:3|max:255',
    //             'price' => 'required|numeric|min:0',
    //         ]);
    //         $book = Book::create($validated);

    //         return  (new BookResource($book))
    //         ->additional(['message' => '商品情報が登録されました'])
    //         ->response()
    //         ->setStatusCode(201);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //     return response()->json([
    //         'message' => 'Validation failed',
    //         'errors' => $e->errors()
    //     ], 422);
    // }
    // }

    // カスタムフォームリクエスト
    public function store(BookStoreRequest $request)
    {

        $validated = $request->validated();
        $book = Book::create($validated);

        return  (new BookResource($book))
        ->additional(['message' => '商品情報が登録されました'])
        ->response()
        ->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // リソースクラスを使うように変更
        $book = Book::findOrFail($id);
        return new BookResource($book);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        $book->title = $request->title;
        $book->price = $request->price;
        $book->save();

        return  (new BookResource($book))
        ->additional(['message' => '商品情報が更新されました'])
        ->response()
        ->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return  (new BookResource($book))
        ->additional(['message' => '商品情報が削除されました'])
        ->response()
        ->setStatusCode(201);

    }
}
