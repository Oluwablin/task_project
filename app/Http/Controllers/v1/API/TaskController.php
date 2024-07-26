<?php

namespace App\Http\Controllers\v1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\v1\API\CreateTaskRequest;
use App\Http\Requests\v1\API\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Traits\ApiResponse;
use App\Enums\HttpStatusCode;

class TaskController extends Controller
{
    use ApiResponse;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return $this->success(
            data: TaskResource::collection($tasks)->toArray(request()),
            message: 'Tasks retrieved successfully.',
            code: HttpStatusCode::SUCCESSFUL->value
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request)
    {
        $task = $this->taskService->create($request->validated());
        return $this->success(
            data: (new TaskResource($task))->toArray($request),
            message: 'Task created successfully.',
            code: HttpStatusCode::CREATED->value
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $this->success(
            data: (new TaskResource($task))->toArray(request()),
            message: 'Task retrieved successfully.',
            code: HttpStatusCode::SUCCESSFUL->value
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $updatedTask = $this->taskService->update($task, $request->validated());
        return $this->success(
            data: (new TaskResource($updatedTask))->toArray($request),
            message: 'Task updated successfully.',
            code: HttpStatusCode::SUCCESSFUL->value
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->taskService->delete($task);
        return $this->success(
            data: [],
            message: 'Task deleted successfully.',
            code: HttpStatusCode::SUCCESSFUL->value
        );
    }
}
