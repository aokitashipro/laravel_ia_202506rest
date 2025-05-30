<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Player;
use App\Models\Position;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerPosition>
 */
class PlayerPositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'player_id'      => Player::factory(),
            'position_id'    => Position::inRandomOrder()->first()->id,
            'skill'          => fake()->numberBetween(40, 100),
            'assigned_from'  => fake()->dateTimeBetween('-10 years', 'now')
                                   ->format('Y-m-d'),
        ];
    }
}
