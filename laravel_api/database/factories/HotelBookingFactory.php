<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelBooking>
 */
class HotelBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $checkinDate = $this->faker->dateTimeBetween('-30 days', '+60 days');
        $checkoutDate = Carbon::parse($checkinDate)->addDays($this->faker->numberBetween(1, 20));

        return [
            'guest_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'guest_count' => $this->faker->numberBetween(1, 4),
            'room_type' => $this->faker->randomElement(['シングル', 'ダブル', 'ツイン', 'スイート', 'ファミリー']),
            'special_requests' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
        ];
    }
}
