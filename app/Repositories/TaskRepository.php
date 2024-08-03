<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get (filtered) tasks
     * @param array $filters
     * @return Collection
     */
    public function getTasksByFilters(array $filters): Collection
    {
        $query = Task::where('user_id', auth()->id())
//            ->whereNull('parent_id')
            ->status($filters['status'] ?? null)
            ->priority($filters['priority'] ?? null)
            ->search($filters['search'] ?? null);

        if (!empty($filters['sort'])) {
            foreach ($filters['sort'] as $sortField => $sortDirection) {
                $query->orderBy($sortField, $sortDirection);
            }
        }

        return $query->with(['subtasks' => function ($query) use ($filters) {
            if (!empty($filters['sort'])) {
                foreach ($filters['sort'] as $sortField => $sortDirection) {
                    $query->orderBy($sortField, $sortDirection);
                }
            }
        }])->get();
    }

    /**
     * Show specific task with it's subtasks
     * @param int $taskId
     * @return Task|null
     */
    public function showTaskWithSubtasks(int $taskId): ?Task
    {
        return Task::with('subTasks.subTasks')->find($taskId);
    }

    /**
     * Create task.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        $task = new Task();
        $task->fill($data);
        $task->save();

        return $task;
    }

    /**
     *  Update the specified task.
     *
     * @param Task $task
     * @param array $data
     * @return bool
     */
    public function update(Task $task, array $data): bool
    {
        $task->fill($data);
        return $task->save();
    }

    /**
     *  Delete the specified task.
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}

