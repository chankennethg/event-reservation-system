<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random event
        $event = Event::all()->random();

        // Make sure user who created the ticket is not the event creator
        $user = User::where('uuid', '!=', $event->uuid)->get()->random();

        return [
            'uuid' => fake()->uuid(),
            'user_uuid' => $user->uuid,
            'event_uuid' =>  $event->uuid,
        ];
    }
}
