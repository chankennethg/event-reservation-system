<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('-3 months', '+3 months')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+3 months')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-3 months', $startsAt)->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween($reservationStartsAt, $startsAt)->format($dateFormat);

        return [
            'uuid' => fake()->uuid(),
            'user_uuid' => User::all()->random()->uuid,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(rand(10,100)),
            'location' => fake()->address(),
            'price' => fake()->randomFloat(2, 1, 8),
            'attendee_limit' => fake()->boolean(50) ? fake()->numberBetween() : null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'reservation_starts_at' => $reservationStartsAt,
            'reservation_ends_at' => $reservationEndsAt,
        ];
    }
}
