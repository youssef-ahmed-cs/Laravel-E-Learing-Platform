<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function user_can_register_with_valid_data(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'john_doe',
            'bio' => 'Test bio',
            'phone' => '1234567890'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'username',
                    'bio',
                    'phone',
                ],
                'token'
            ])
            ->assertJson([
                'message' => 'User registered successfully'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'role' => 'student',
            'username' => 'john_doe'
        ]);
    }

    /** @test */
    public function user_registration_fails_with_invalid_data()
    {
        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123', // Too short
            'role' => 'invalid_role',
            'username' => ''
        ];

        $response = $this->postJson('/api/register', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'role', 'username']);
    }

    /** @test */
    public function user_registration_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_registration_fails_with_duplicate_username()
    {
        User::factory()->create(['username' => 'existinguser']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'existinguser'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    /** @test */
    public function user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'username'
                ],
                'token'
            ])
            ->assertJson([
                'message' => 'Login successful'
            ]);
    }

    /** @test */
    public function user_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    /** @test */
    public function user_login_fails_with_nonexistent_email()
    {
        $loginData = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    /** @test */
    public function user_login_fails_with_invalid_data_format()
    {
        $invalidData = [
            'email' => 'invalid-email',
            'password' => '123' // Too short
        ];

        $response = $this->postJson('/api/login', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully'
            ]);

        // Verify all tokens are deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_logout()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_get_profile()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'student'
        ]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'username'
                ]
            ])
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'role' => 'student'
                ]
            ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_get_profile()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_refresh_token()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create an existing token
        $oldToken = $user->createToken('old_token')->plainTextToken;

        $response = $this->postJson('/api/refresh-token');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token'
            ])
            ->assertJson([
                'message' => 'Token refreshed successfully'
            ]);

        // Verify old tokens are deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'old_token'
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_refresh_token()
    {
        $response = $this->postJson('/api/refresh-token');

        $response->assertStatus(401);
    }

    /** @test */
    public function user_can_register_as_different_roles()
    {
        $roles = ['student', 'admin', 'instructor'];

        foreach ($roles as $role) {
            $userData = [
                'name' => "Test {$role}",
                'email' => "test_{$role}@example.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => $role,
                'username' => "test_{$role}"
            ];

            $response = $this->postJson('/api/register', $userData);

            $response->assertStatus(201)
                ->assertJson([
                    'message' => 'User registered successfully'
                ]);

            $this->assertDatabaseHas('users', [
                'email' => "test_{$role}@example.com",
                'role' => $role
            ]);
        }
    }

    /** @test */
    public function login_returns_sanctum_token()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200);

        $responseData = $response->json();
        $this->assertNotEmpty($responseData['token']);

        // Verify token exists in database
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'auth_token'
        ]);
    }

    /** @test */
    public function register_returns_sanctum_token()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);

        $responseData = $response->json();
        $this->assertNotEmpty($responseData['token']);

        // Verify token exists in database
        $user = User::where('email', 'john@example.com')->first();
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'auth_token'
        ]);
    }

    /** @test */
    public function password_is_hashed_during_registration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe'
        ];

        $this->postJson('/api/register', $userData);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotEquals('password123', $user->password);
    }
}
