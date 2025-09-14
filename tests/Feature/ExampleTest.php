<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_users_index(): void
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/users');
        $response->assertStatus(200);
    }
}
