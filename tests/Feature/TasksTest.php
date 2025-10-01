<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_tasks_route_returns_json(): void
    {
        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function test_tasks_route_returns_tasks_list(): void # test the structure of the returned JSON
    {
        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'completed',
                    'priority',
                    'dateline',
                    'user_id',
                    'lesson_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    public function test_can_create_task(): void
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $payload = [
            'title' => 'New Task',
            'content' => 'Task description',
            'completed' => false,
            'priority' => 'low',
            'dateline' => now()->addDay()->toDateString(),
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Task']);
    }


    public function test_can_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Title'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }





}
