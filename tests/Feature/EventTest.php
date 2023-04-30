<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Create Admin endpoint
     *
     * @var string
     */
    private string $apiUrl = '/api/v1/events';


    /**
     * Test if user can list events
     */
    public function test_user_can_create_events(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('-3 months', '+3 months')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+3 months')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-3 months', $startsAt)->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween($reservationStartsAt, $startsAt)->format($dateFormat);

        $payload = [
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

        $response = $this->post($this->apiUrl . '/create', $payload);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'uuid',
                'user_uuid',
                'title',
                'description',
                'location',
                'price',
                'attendee_limit',
                'starts_at',
                'ends_at',
                'reservation_starts_at',
                'reservation_ends_at',
            ],
        ]);
    }


    /**
     * Test if user can list events
     */
    public function test_user_can_create_events_with_missing_fields(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $payload = [];

        $response = $this->post($this->apiUrl . '/create', $payload);

        $response->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
        ])
        ->assertJsonValidationErrors(['title', 'description', 'location', 'price', 'starts_at', 'ends_at','reservation_starts_at', 'reservation_ends_at']);
    }

    /**
     * Test if user can list events
     */
    public function test_user_can_create_events_without_auth(): void
    {
        $dateFormat = 'Y-m-d H:i:s';
        $startsAt = fake()->dateTimeBetween('-3 months', '+3 months')->format($dateFormat);
        $endsAt = fake()->dateTimeBetween($startsAt, '+3 months')->format($dateFormat);
        $reservationStartsAt = fake()->dateTimeBetween('-3 months', $startsAt)->format($dateFormat);
        $reservationEndsAt = fake()->dateTimeBetween($reservationStartsAt, $startsAt)->format($dateFormat);

        $payload = [
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

        $response = $this->post($this->apiUrl . '/create', $payload);

        $response->assertUnauthorized()
        ->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    /**
     * Test if user can list events
     */
    public function test_user_can_list_events(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        Event::factory()->count(20)->create();

        $response = $this->get($this->apiUrl . '/list');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'user_uuid',
                    'title',
                    'description',
                    'location',
                    'price',
                    'attendee_limit',
                    'starts_at',
                    'ends_at',
                    'reservation_starts_at',
                    'reservation_ends_at',
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total'
            ]
        ])
        ->assertJsonFragment([
            'total' => 20,
        ]);
    }
}
