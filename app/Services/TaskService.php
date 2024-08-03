<?php

namespace App\Services;

use App\DTO\Task\ChangeTaskStatusDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\ShowTaskDTO;
use App\DTO\Task\TaskResponseDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Collection;

class TaskService
{
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get list of tasks
     *
     * @param array $filters
     * @return Collection
     */
    public function getTasks(array $filters): Collection
    {
        $parentTasks = $this->taskRepository->getTasksByFilters($filters);

        return $parentTasks->map(function ($task) use ($filters) {
            return $this->getSubTask($task, $filters);
        });
    }

    /**
     * Get specific task
     *
     * @param int $taskId
     * @return ShowTaskDTO
     */
    public function showTask(int $taskId): ShowTaskDTO
    {
        $task = $this->taskRepository->showTaskWithSubtasks($taskId);
        return $this->getSubTask($task);
    }

    /**
     * Create task
     *
     * @param CreateTaskDTO $taskDTO
     * @return TaskResponseDTO
     */
    public function createTask(CreateTaskDTO $taskDTO): TaskResponseDTO
    {
        $task = $this->taskRepository->create((array) $taskDTO);

        return new TaskResponseDTO(
            $task->wasRecentlyCreated,
            $task->wasRecentlyCreated ? 'Task is created.' : 'Task is not created.'
        );
    }

    /**
     * Update specific task
     *
     * @param Task $task
     * @param UpdateTaskDTO $taskDTO
     * @return TaskResponseDTO
     */
    public function updateTask(Task $task, UpdateTaskDTO $taskDTO): TaskResponseDTO
    {
        if (isset($taskDTO->status) && $taskDTO->status === 'done') {
            if ($task->hasIncompleteSubtasks()->exists()) {
                return new TaskResponseDTO(false, 'Cannot set status Done for a task with uncompleted subtasks.');
            }
        }

        $taskData = array_filter((array) $taskDTO, function ($value) {
            return !is_null($value);
        });

        $updated = $this->taskRepository->update($task, $taskData);

        return new TaskResponseDTO(
            $updated,
            $updated ? 'Task is updated.' : 'Task is not updated.'
        );
    }

    /**
     * Change status of specific task
     *
     * @param Task $task
     * @param ChangeTaskStatusDTO $taskDTO
     * @return TaskResponseDTO
     */
    public function changeStatus(Task $task, ChangeTaskStatusDTO $taskDTO): TaskResponseDTO
    {
        if ($taskDTO->status === 'done') {
            if ($task->hasIncompleteSubtasks()->exists()) {
                return new TaskResponseDTO(false, 'Cannot set status Done for a task with uncompleted subtasks.');
            }

            $taskDTO->setCompletedAt(now()->toDateTimeString());
        } else {
            $taskDTO->setCompletedAt(null);
        }

        $updated = $this->taskRepository->update($task, (array) $taskDTO);

        return new TaskResponseDTO(
            $updated,
            $updated ? 'Task Status is updated.' : 'Task Status is not updated.'
        );
    }

    /**
     * Delete specific task
     *
     * @param int $task_id
     * @return TaskResponseDTO
     */
    public function deleteTask(int $task_id): TaskResponseDTO
    {
        $task = Task::findOrFail($task_id);

        if ($task->status === 'done') {
            return new TaskResponseDTO(false, 'Cannot delete a task that is already done.');
        }

        if ($task->subtasks()->hasIncompleteSubtasks()->exists()) {
            return new TaskResponseDTO(false, 'Cannot delete a task with completed subtasks.');
        }

        $deleted = $this->taskRepository->delete($task);

        return new TaskResponseDTO(
            $deleted,
            $deleted ? 'Task is deleted.' : 'Task could not be deleted.'
        );
    }

    /**
     * Get subtasks of specific task
     *
     * @param Task $task
     * @param array|null $filters
     * @return ShowTaskDTO
     */
    private function getSubTask(Task $task, array|null $filters = null): ShowTaskDTO
    {
        $taskDTO = new ShowTaskDTO($task);

        $subtasksQuery = $task->subtasks()->status($filters['status'] ?? null)
            ->priority($filters['priority'] ?? null)
            ->search($filters['search'] ?? null);

        if (!empty($filters['sort'])) {
            foreach ($filters['sort'] as $sortField => $sortDirection) {
                $subtasksQuery->orderBy($sortField, $sortDirection);
            }
        }

        $subtasks = $subtasksQuery->get();

        if ($subtasks->count()) {
            foreach ($subtasks as $subTask) {
                $taskDTO->subTasks[] = $this->getSubTask($subTask, $filters);
            }
        } else {
            unset($taskDTO->subTasks);
        }

        return $taskDTO;
    }
}
