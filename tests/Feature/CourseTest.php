<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_can_add_new_course(): void
    {
        $courseData = Course::factory()->make()->toArray();
        $response = $this->post(route('courses.store'), $courseData);
        $response->assertStatus(201);
    }

    public function test_show_course(): void
    {
        $course = Course::factory()->create();

        $response = $this->get(route('courses.show', $course->id));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
            ],
        ]);
    }

    public function test_index_courses(): void
    {
        Course::factory()->count(15)->create();
        $response = $this->get(route('courses.index'));
        $response->assertStatus(200);
    }

    public function test_update_course(): void
    {
        $course = Course::factory()->create();
        $updatedData = [
            'title' => 'Updated Course Title',
            'description' => 'Updated Course Description',
        ];

        $response = $this->put(route('courses.update', $course->id), $updatedData);
        $response->assertStatus(200);
        $response->assertJsonFragment($updatedData);
    }

    public function test_delete_course(): void
    {
        $course = Course::factory()->create();

        $response = $this->delete(route('courses.destroy', $course->id));
        $response->assertStatus(200);
    }

    public function test_restore_course(): void
    {
        $course = Course::factory()->create();
        $course->delete();

        $response = $this->post(route('courses.restore', $course->id));
        $response->assertStatus(200);
    }
}
