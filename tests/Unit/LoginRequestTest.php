<?php

namespace Tests\Unit;

use App\Http\Requests\AuthRequests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    /** @test */
    public function it_passes_validation_with_valid_data()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => 'password123'
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_validation_when_email_is_missing()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'password' => 'password123'
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_email_is_invalid()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'invalid-email',
            'password' => 'password123'
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_email_is_empty()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => '',
            'password' => 'password123'
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_password_is_missing()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com'
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_password_is_empty()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => ''
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_password_is_too_short()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => '12345' // 5 characters, minimum is 6
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_passes_validation_when_password_meets_minimum_length()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => '123456' // Exactly 6 characters
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_validation_when_password_is_not_string()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => 123456 // Integer instead of string
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /** @test */
    public function it_fails_validation_when_email_is_not_string()
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 12345, // Integer instead of string
            'password' => 'password123'
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    /** @test */
    public function authorization_returns_true()
    {
        $request = new LoginRequest();
        $this->assertTrue($request->authorize());
    }

    /** @test */
    public function it_has_correct_validation_rules()
    {
        $request = new LoginRequest();
        $expectedRules = [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ];

        $this->assertEquals($expectedRules, $request->rules());
    }
}
