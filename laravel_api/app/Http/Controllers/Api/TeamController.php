<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;
use App\Models\Team;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::query()->select(['id','name'])->get();

        return TeamResource::collection(
            Team::query()->select(['id','name'])->orderBy('name')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    // N + 1発生版
    public function show(string $id)
    {

        // 1回目: チーム取得
        $team = Team::findOrFail($id);

        // 2回目: 選手取得
        $players = $team->players;

        // 3回目以降: 各選手のポジションを個別に取得（N+1問題発生！）
        foreach ($players as $player) {
            $positions = $player->positions; // ←ここで選手数分のクエリが発生
        }

        return new TeamResource($team);
    }

    // n+1対応版 withを使う
    // public function show(string $id){

    //     $team = Team::with(['players.positions'])->findOrFail($id);

    //     return new TeamResource($team);

    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
