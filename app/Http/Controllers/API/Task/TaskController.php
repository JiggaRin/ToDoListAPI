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

/**
 * @OA\Info(title="ToDo List API", version="1.0")
 */
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
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get a list of tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Filter tasks by status",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"todo", "done"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="priority",
     *          in="query",
     *          description="Filter tasks by priority",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              enum={1, 2, 3, 4, 5}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Search tasks by title or description",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="sort[completed_at]",
     *          in="query",
     *          description="Sort direction for the 'completed_at' field.",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"asc", "desc"}
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="sort[created_at]",
     *          in="query",
     *          description="Sort direction for the 'created_at' field.",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"asc", "desc"}
     *          )
     *      ),
     *     @OA\Parameter(
     *           name="sort[priority]",
     *           in="query",
     *           description="Sort direction for the 'priority' field.",
     *           required=false,
     *           @OA\Schema(
     *               type="string",
     *               enum={"asc", "desc"}
     *           )
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of tasks",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="tasks",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Task")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(TaskFiltersRequest $request): JsonResponse
    {
        $filters = $request->only(['status', 'priority', 'search', 'sort']);
        $tasks = $this->taskService->getTasks($filters);

        return response()->json(['tasks' => $tasks]);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{task}",
     *     summary="Show a specific task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="task",
     *          in="path",
     *          description="ID of the task to retrieve",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Task retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Task")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid request"),
     *     @OA\Response(response=404, description="Task not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $responseDTO = $this->taskService->showTask($id);
        return response()->json($responseDTO);
    }

    /**
     * @OA\POST(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         description="Create Task Request",
     *         required=true,
     *         @OA\MediaType(
     *               mediaType="application/x-www-form-urlencoded",
     *               @OA\Schema(ref="#/components/schemas/CreateTaskRequest")
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
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
     * @OA\PUT(
     *     path="/api/tasks/{task}",
     *     summary="Update specified task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID of the task to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Update Task Request",
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(ref="#/components/schemas/UpdateTaskRequest")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
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
     * @OA\PUT(
     *     path="/api/tasks/{task}/status",
     *     summary="Change status for specified task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="task",
     *          in="path",
     *          description="ID of the task to update",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\RequestBody(
     *         description="Change Task Status",
     *         required=true,
     *           @OA\MediaType(
     *                mediaType="application/x-www-form-urlencoded",
     *                @OA\Schema(ref="#/components/schemas/ChangeTaskStatusRequest")
     *           ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
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
     * @OA\DELETE(
     *     path="/api/tasks/{task}",
     *     summary="Delete specified task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID of the task to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Indicates if the operation was successful"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Operation message"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $responseDTO = $this->taskService->deleteTask($id);

        return response()->json($responseDTO);
    }
}
