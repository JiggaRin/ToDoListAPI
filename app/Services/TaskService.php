<?php

namespace App\Services;

use App\DTO\Task\ChangeTaskStatusDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\ShowTaskDTO;
use App\DTO\Task\TaskResponseDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Models\Task;

class TaskService
{
    /**
     * @param int $taskId
     * @return ShowTaskDTO
     */
    public function showTask(int $taskId): ShowTaskDTO
    {
        $task = Task::with('subTasks.subTasks')->findOrFail($taskId);
        return $this->getSubTask($task);
    }

    /**
     * @param CreateTaskDTO $taskDTO
     * @return TaskResponseDTO
     */
    public function createTask(CreateTaskDTO $taskDTO): TaskResponseDTO
    {
        $task = new Task();
        $task->fill((array)$taskDTO);
        $task->save();

        return new TaskResponseDTO(
            $task->wasRecentlyCreated,
            $task->wasRecentlyCreated ? 'Task is created.' : 'Task is not created.'
        );
    }

    /**
     * @param Task $task
     * @param UpdateTaskDTO $taskDTO
     * @return TaskResponseDTO
     */
    public function updateTask(Task $task, UpdateTaskDTO $taskDTO): TaskResponseDTO
    {
        if (isset($taskDTO->status) && $taskDTO->status === 'done') {
            if ($task->subtasks()->hasIncompleteSubtasks()->exists()) {
                return new TaskResponseDTO(false, 'Cannot set status Done for a task with uncompleted subtasks.');
            }
        }

        $taskData = array_filter((array)$taskDTO, function ($value) {
            return !is_null($value);
        });

        $task->fill($taskData);
        $updated = $task->save();

        return new TaskResponseDTO(
            $updated,
            $updated ? 'Task is updated.' : 'Task is not updated.'
        );
    }

    /**
     * @param Task $task
     * @param ChangeTaskStatusDTO $taskDTO
     * @return TaskResponseDTO
     */
    public function changeStatus(Task $task, ChangeTaskStatusDTO $taskDTO): TaskResponseDTO
    {
        if ($taskDTO->status === 'done') {
            if ($task->subtasks()->hasIncompleteSubtasks()->exists()) {
                return new TaskResponseDTO(false, 'Cannot set status Done for a task with uncompleted subtasks.');
            }

            $taskDTO->setCompletedAt(now()->toDateTimeString());
        } else {
            $taskDTO->setCompletedAt(null);
        }

        $task->fill((array)$taskDTO);
        $updated = $task->save();

        return new TaskResponseDTO(
            $updated,
            $updated ? 'Task Status is updated.' : 'Task Status is not updated.'
        );
    }

    /**
     * @param Task $task
     * @return TaskResponseDTO
     */
    public function deleteTask(Task $task): TaskResponseDTO
    {
        if ($task->status === 'done') {
            return new TaskResponseDTO(false, 'Cannot delete a task that is already done.');
        }

        if ($task->subtasks()->hasIncompleteSubtasks()->exists()) {
            return new TaskResponseDTO(false, 'Cannot delete a task with completed subtasks.');
        }

        $deleted = $task->delete();

        return new TaskResponseDTO(
            $deleted,
            $deleted ? 'Task is deleted.' : 'Task could not be deleted.'
        );
    }

    /**
     * @param Task $task
     * @return ShowTaskDTO
     */
    private function getSubTask(Task $task): ShowTaskDTO
    {
        $taskDTO = new ShowTaskDTO($task);

        if(count($task->subTasks)) {
            foreach ($task->subTasks as $subTask) {
                $taskDTO->subTasks[] = $this->getSubTask($subTask);
            }
        } else {
            unset($taskDTO->subTasks);
        }

        return $taskDTO;
    }
}


