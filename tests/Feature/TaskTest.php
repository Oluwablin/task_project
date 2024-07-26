<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/v1/task/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'due_date' => now()->addWeek()->toDateString(),
            'is_completed' => false,
        ]);

        $response->assertStatus(201)->assertJson([
            'data' => [
                'title' => 'Test Task',
                'description' => 'Test Description',
                'due_date' => now()->addWeek()->toDateString(),
                'is_completed' => false,
            ],
            'message' => 'Task created successfully.',
            'code' => 201,
        ]);
    }

    public function test_user_can_view_tasks()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/task/tasks');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'description', 'due_date', 'is_completed', 'created_at', 'updated_at']
            ],
            'message',
            'code'
        ])->assertJsonCount(3, 'data');
    }

    public function test_user_can_update_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create();

        $response = $this->putJson("/api/v1/task/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'due_date' => now()->addWeek()->toDateString(),
            'is_completed' => true,
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'title' => 'Updated Task',
                'description' => 'Updated Description',
                'due_date' => now()->addWeek()->toDateString(),
                'is_completed' => true,
            ],
            'message' => 'Task updated successfully.',
            'code' => 200,
        ]);
    }

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/v1/task/tasks/{$task->id}");

        $response->assertStatus(200)->assertJson([
            'data' => null,
            'message' => 'Task deleted successfully.',
            'code' => 200,
        ]);
    }
}
