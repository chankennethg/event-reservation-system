<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Login endpoint
     *
     * @var string
     */
    private string $loginUrl = '/api/v1/auth/login';


    /**
     * A basic login test
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->json('POST', $this->loginUrl, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                    'email',
                    'first_name',
                    'last_name',
                    'token',
                    'token_expires_at',
                ],
            ]);
    }

    /**
     * Test failed login
     *
     * @return void
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $data = [
            'email' => 'nonexistentuser@example.com',
            'password' => 'invalidpassword',
        ];

        $response = $this->json('POST', $this->loginUrl, $data);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid Username or Password.',
            ]);
    }

    /**
     * Login with missing fields
     *
     * @return void
     */
    public function test_user_cannot_login_with_missing_fields()
    {
        $data = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->json('POST', $this->loginUrl, $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
