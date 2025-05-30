<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // 追記


class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
        ['name' => 'マンチェスターユナイテッド'], 
        ['name' => 'リバプール'], 
        ['name' => 'チェルシー'],
        ['name' => 'レアルマドリード'], 
        ['name' => 'バルセロナ'], 
        ['name' => 'セビージャ'],
        ['name' => 'ユベントス'], 
        ['name' => 'ACミラン'], 
        ['name' => 'インテル'],
        ['name' => 'バイエルンミュンヘン'], 
        ['name' => 'ドルトムント'], 
        ['name' => 'レバークーゼン'],
        ['name' => 'パリサンジェルマン'],
        ]);
    }
}
