<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Database\Factories\TicketFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();

        foreach ($events as $event) {

            $eventHasNoLimit = $event->attendee_limit === null;

            /** If event is maxed out, skip */
            if ($event->tickets()->count() >= $event->attendee_limit && !$eventHasNoLimit) {
                continue;
            }

            /** If event has limit, create max 10 tickets, if not get the remaining available ticket as max */
            $limit = $eventHasNoLimit ? 10 : $event->attendee_limit - $event->tickets()->count();
            $limit = $limit > 10 ? 10 : $limit;

            Ticket::factory()->count(rand(1, $limit))->create([
                'user_uuid' => User::factory()->create()->uuid,
                'event_uuid' => $event->uuid,
            ]);
        }


        TicketFactory::new()->count(100)->create();
    }
}
