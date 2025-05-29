<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // 追記

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => '商品A',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '商品B',
                'price' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '商品C',
                'price' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ]
        );

    }
}
