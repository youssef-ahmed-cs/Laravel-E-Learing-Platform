<?php

namespace Tests\Unit;

use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_passes_validation_with_valid_data()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
            'bio' => 'Test bio',
            'phone' => '1234567890',
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_passes_validation_with_minimal_required_data()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_validation_when_name_is_missing()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_name_exceeds_max_length()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => str_repeat('a', 256), // 256 characters, max is 255
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_email_is_missing()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_email_is_invalid()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_email_exceeds_max_length()
    {
        $request = new RegisterRequest;
        $longEmail = str_repeat('a', 250).'@example.com'; // Over 255 characters
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => $longEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_email_already_exists()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_password_is_missing()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_password_is_too_short()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '12345', // 5 characters, minimum is 6
            'password_confirmation' => '12345',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_password_confirmation_does_not_match()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_role_is_missing()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('role', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_role_is_invalid()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'invalid_role',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('role', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_validation_with_valid_roles()
    {
        $request = new RegisterRequest;
        $validRoles = ['student', 'admin', 'instructor'];

        foreach ($validRoles as $role) {
            $validator = Validator::make([
                'name' => 'John Doe',
                'email' => "john_{$role}@example.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => $role,
                'username' => "johndoe_{$role}",
            ], $request->rules());

            $this->assertTrue($validator->passes(), "Failed for role: {$role}");
        }
    }

    /** @test */
    public function it_fails_validation_when_username_is_missing()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('username', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_username_exceeds_max_length()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => str_repeat('a', 256), // 256 characters, max is 255
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('username', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_username_already_exists()
    {
        User::factory()->create(['username' => 'existinguser']);

        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'existinguser',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('username', $validator->errors()->toArray());
    }

    /** @test */
    public function bio_field_is_optional()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
            // bio is omitted
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function phone_field_is_optional()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
            // phone is omitted
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_validation_when_name_is_not_string()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 12345, // Integer instead of string
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_bio_is_not_string()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
            'bio' => 12345, // Integer instead of string
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('bio', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_phone_is_not_string()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
            'phone' => 12345, // Integer instead of string
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }

    /** @test */
    public function authorization_returns_true()
    {
        $request = new RegisterRequest;
        $this->assertTrue($request->authorize());
    }

    /** @test */
    public function it_has_correct_validation_rules()
    {
        $request = new RegisterRequest;
        $expectedRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:student,admin,instructor',
            'username' => 'required|string|max:255|unique:users',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string',
        ];

        $this->assertEquals($expectedRules, $request->rules());
    }

    /** @test */
    public function it_fails_validation_when_multiple_fields_are_invalid()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123', // Too short
            'password_confirmation' => 'different', // Doesn't match
            'role' => 'invalid_role',
            'username' => '', // Empty
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $errors = $validator->errors()->toArray();
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('password', $errors);
        $this->assertArrayHasKey('role', $errors);
        $this->assertArrayHasKey('username', $errors);
    }

    /** @test */
    public function it_passes_validation_when_bio_is_null()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
            'bio' => null,
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_passes_validation_when_phone_is_null()
    {
        $request = new RegisterRequest;
        $validator = Validator::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
            'username' => 'johndoe',
            'phone' => null,
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }
}
