<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Team;

class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'    => fake('ja_JP')->name,
            'team_id' => function () {
                return Team::inRandomOrder()->first()->id;
            },
        ];
    }
}
