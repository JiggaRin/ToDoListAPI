<?php

namespace App\Http\Controllers\API\Task;

use App\DTO\Task\ChangeTaskStatusDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\ChangeTaskStatusRequest;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\TaskFiltersRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display all (filtered) tasks.
     *
     * @param TaskFiltersRequest $request
     * @return JsonResponse
     */
    public function index(TaskFiltersRequest $request): JsonResponse
    {
        $filters = $request->only(['status', 'priority', 'search', 'sort']);
        $tasks = $this->taskService->getTasks($filters);

        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Display the specified task.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $responseDTO = $this->taskService->showTask($id);
        return response()->json($responseDTO);
    }

    /**
     * Store a newly created task.
     *
     * @param CreateTaskRequest $request
     * @return JsonResponse
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $taskDTO = new CreateTaskDTO(
            user_id: auth()->id(),
            title: $request->get('title'),
            status: $request->get('status'),
            priority: $request->get('priority'),
            parent_id: $request->get('parent_id'),
            description: $request->get('description'),
            completed_at: $request->get('status') === 'done' ? now()->toDateTimeString() : null
        );

        $responseDTO = $this->taskService->createTask($taskDTO);

        return response()->json($responseDTO);
    }

    /**
     * Update the specified task.
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $taskDTO = new UpdateTaskDTO(
            title: $request->get('title'),
            status: $request->get('status'),
            priority: $request->get('priority'),
            parent_id: $request->get('parent_id'),
            description: $request->get('description'),
            completed_at: $request->get('status') === 'done' ? now()->toDateTimeString() : null
        );

        $responseDTO = $this->taskService->updateTask($task, $taskDTO);

        return response()->json($responseDTO);
    }

    /**
     * Change the status of the specified task.
     *
     * @param ChangeTaskStatusRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function changeStatus(ChangeTaskStatusRequest $request, Task $task): JsonResponse
    {
        $taskDTO = new ChangeTaskStatusDTO(
            status: $request->get('status'),
            completed_at: $request->get('status') === 'done' ? now()->toDateTimeString() : null
        );

        $responseDTO = $this->taskService->changeStatus($task, $taskDTO);

        return response()->json($responseDTO);
    }

    /**
     * Remove the specified task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $responseDTO = $this->taskService->deleteTask($task);

        return response()->json($responseDTO);
    }
}
