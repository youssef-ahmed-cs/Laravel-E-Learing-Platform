<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_collection_index(): void
    {
        $response = $this->get('/api/collection');
        $response->assertStatus(200);
    }

    public function test_ping_index(): void
    {
        $response = $this->get('/api/ping');
        $response->assertStatus(200);
    }

    public function test_old_route_index(): void
    {
        $response = $this->get('/api/old-route');
        $response->assertStatus(301);
    }

}
