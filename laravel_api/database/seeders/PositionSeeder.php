<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // 追記

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
        ['name' => 'ゴールキーパー'], 
        ['name' => 'センターバック'], 
        ['name' => 'サイドバック'],
        ['name' => 'ボランチ'], 
        ['name' => 'センターハーフ'], 
        ['name' => 'トップ下'],
        ['name' => 'ウィング'], 
        ['name' => 'ストライカー'], 
        ['name' => 'セカンドトップ'], 
        ['name' => 'ウイングバック'],
        ]);
    }
}
