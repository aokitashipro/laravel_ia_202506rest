<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Player;
use App\Models\PlayerPosition;
use App\Models\Team;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
            PositionSeeder::class,
            TeamSeeder::class,
        ]);

        // ポジション一覧を取得
        $positions = Position::all();

        // 選手を多めに作成
        $players = Player::factory()->count(300)->create();

        // 各選手に1〜3つのポジションを割り当て
        foreach ($players as $player) {
            $posIds = $positions->random(rand(1,3))->pluck('id');
            foreach ($posIds as $idx => $posId) {
                PlayerPosition::factory()->create([
                    'player_id'   => $player->id,
                    'position_id' => $posId,
                    'skill'         => rand(40, 100), // 40〜100のランダム値
                    'assigned_from' => now()->subYears(rand(0, 10))->format('Y-m-d'), // 0〜10年前の日付
                ]);
            }
        }
    }
}
