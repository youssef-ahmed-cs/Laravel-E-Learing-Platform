<?php

namespace Tests\Feature\Users;

use App\Models\Category;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_get_all_categories(): void
    {
        $categories = Category::factory(3)->create();
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200);
    }
}
