<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $player = Player::create([
            'name' => $request->name,
            'team_id' => $request->team_id,
        ]);

        return  (new PlayerResource($player))
        ->additional(['message' => '選手情報が登録されました'])
        ->response()
        ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();                 // ① 取引開始

        try {
            /* 1) 対象プレーヤー取得 */
            $player = Player::findOrFail($id);

            /* 2) players テーブル更新 */
            $player->update([
                'name'    => $request->name,
                'team_id' => $request->team_id,
            ]);

            /* 3) pivot 用配列を組み立て */
            $syncData = [];
            foreach ($request->positions ?? [] as $row) {
                $syncData[$row['id']] = [
                    'skill'         => $row['skill']         ?? null,
                    'assigned_from' => $row['assigned_from'] ?? null,
                ];
            }

            /* 4) pivot を sync で置き換え */
            $player->positions()->sync($syncData);

            DB::commit(); // ⑤ すべて成功 → コミット
        } catch (\Throwable $e) {
            DB::rollBack(); // ⑥ 失敗したら巻き戻し
            throw $e; // ※ ここでレスポンス用に整えても OK
        }

        /* 5) 最新状態を返却 */
        return new PlayerResource(
            $player->fresh()->load(['team', 'positions'])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
