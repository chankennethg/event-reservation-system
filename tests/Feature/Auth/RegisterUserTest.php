<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Create Admin endpoint
     *
     * @var string
     */
    private string $apiUrl = '/api/v1/auth/register';

    /**
     * Test if user can register
     */
    public function test_user_can_register(): void
    {
        $payload = [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
        ];

        $response = $this->post($this->apiUrl, $payload);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'uuid',
                'first_name',
                'last_name',
                'email',
                'token'
                'updated_at',
                'created_at',
            ],
        ]);
    }

    /**
     * Test if user cannot register with missing fields
     */
    public function test_user_cannot_register_with_missing_fields(): void
    {
        $payload = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->post($this->apiUrl, $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'first_name', 'last_name']);
    }

    /**
     * Test if user cannot register with mismatch password
     */
    public function test_user_cannot_register_with_mismatch_password(): void
    {
        $payload = [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'password' => 'testpassword',
            'password_confirmation' => 'mismatchpassword',
        ];

        $response = $this->post($this->apiUrl, $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
