<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Create Admin endpoint
     *
     * @var string
     */
    private string $apiUrl = '/api/v1/tickets';

    /**
     * Test if user can create a ticket
     */
    public function test_user_can_create_ticket(): void
    {
        // Arrange data
        User::factory()->create();
        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('+7 days', '+8 days')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+10 days')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-2 months', '-3 days')->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween('tomorrow', $startsAt)->format($dateFormat);

        /** @var Event $event */
        $event = Event::factory()->create([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'reservation_starts_at' => $reservationStartsAt,
            'reservation_ends_at' => $reservationEndsAt,
        ]);

        // Act as new user
        Sanctum::actingAs(
            User::factory()->create()
        );

        $payload = [
            'event_uuid' => $event->uuid,
        ];

        $response = $this->post($this->apiUrl . '/create', $payload);

        // Assert
        $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'uuid',
                'user_uuid',
                'event_uuid',
            ],
        ])
        ->assertJsonFragment([
            'event_uuid' => $event->uuid
        ]);
    }

    /**
     * Test if user cannot create a ticket outside reservation date
     */
    public function test_user_cannot_create_ticket_outside_reservation_date(): void
    {
        User::factory()->create();
        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('+7 days', '+8 days')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+10 days')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-3 months', '-2months')->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween('-5 days', '-2 days')->format($dateFormat);

        /** @var Event $event */
        $event = Event::factory()->create([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'reservation_starts_at' => $reservationStartsAt,
            'reservation_ends_at' => $reservationEndsAt,
        ]);

        // Act as a new user
        Sanctum::actingAs(
            User::factory()->create()
        );

        $payload = [
            'event_uuid' => $event->uuid,
        ];

        $response = $this->post($this->apiUrl . '/create', $payload);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'message' => 'Not open for reservation.'
        ]);
    }

    /**
     * Test if user cannot create a duplicate ticket
     */
    public function test_user_cannot_create_duplicate_ticket(): void
    {
        User::factory()->create();
        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('+7 days', '+8 days')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+10 days')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-2 months', '-3 days')->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween('tomorrow', $startsAt)->format($dateFormat);


        /** @var Event $event */
        $event = Event::factory()->create([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'reservation_starts_at' => $reservationStartsAt,
            'reservation_ends_at' => $reservationEndsAt,
        ]);

        /** @var User $newUser */
        $newUser = User::factory()->create();

        Ticket::factory()->create([
            'event_uuid' => $event->uuid,
            'user_uuid' => $newUser->uuid,
        ]);

        // Act as a new user
        Sanctum::actingAs($newUser);

        $payload = [
            'event_uuid' => $event->uuid,
        ];

        $response = $this->post($this->apiUrl . '/create', $payload);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'message' => 'You are already reserved in this event.'
        ]);
    }

    /**
     * Test if user cannot create a duplicate ticket
     */
    public function test_user_cannot_create_ticket_on_own_event(): void
    {
        /** @var User $newUser */
        $newUser = User::factory()->create();
        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('+7 days', '+8 days')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+10 days')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-2 months', '-3 days')->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween('tomorrow', $startsAt)->format($dateFormat);


        /** @var Event $event */
        $event = Event::factory()->create([
            'starts_at' => $startsAt,
            'user_uuid' => $newUser->uuid,
            'ends_at' => $endsAt,
            'reservation_starts_at' => $reservationStartsAt,
            'reservation_ends_at' => $reservationEndsAt,
        ]);

        // Act as a new user
        Sanctum::actingAs($newUser);

        $payload = [
            'event_uuid' => $event->uuid,
        ];

        $response = $this->post($this->apiUrl . '/create', $payload);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'message' => 'Cannot create ticket on own event.'
        ]);
    }

    /**
     * Test if user cannot create a ticket on sold out event
     */
    public function test_user_cannot_create_ticket_on_full_event(): void
    {
        /** @var User $newUser */
        $newUser = User::factory()->create();
        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('+7 days', '+8 days')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+10 days')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-2 months', '-3 days')->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween('tomorrow', $startsAt)->format($dateFormat);


        /** @var Event $event */
        $event = Event::factory()->create([
            'starts_at' => $startsAt,
            'user_uuid' => $newUser->uuid,
            'ends_at' => $endsAt,
            'attendee_limit' => 3,
            'reservation_starts_at' => $reservationStartsAt,
            'reservation_ends_at' => $reservationEndsAt,
        ]);

        // Create 3 tickets on the event
        Ticket::factory()->count(3)->create([
            'event_uuid' => $event->uuid,
        ]);

        // Act as a new user #2
        /** @var User $newUser2 */
        $newUser2 = User::factory()->create();
        Sanctum::actingAs($newUser2);

        $payload = [
            'event_uuid' => $event->uuid,
        ];

        $response = $this->post($this->apiUrl . '/create', $payload);

        $response->assertStatus(400)
        ->assertJsonFragment([
            'message' => 'No available slots.'
        ]);
    }
}
