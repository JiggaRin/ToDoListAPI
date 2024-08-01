<?php

namespace App\DTO\Task;

class ShowTaskDTO
{
    public int $id;
    public int $user_id;
    public ?int $parent_id;
    public string $title;
    public string|null $description;
    public string $status;
    public int $priority;
    public ?string $created_at;
    public ?string $updated_at;
    public ?string $completed_at;
    public array $subTasks;

    public function __construct($task)
    {
        $this->id = $task->id;
        $this->user_id = $task->user_id;
        $this->parent_id = $task->parent_id;
        $this->title = $task->title;
        $this->description = $task->description ?? null;
        $this->status = $task->status;
        $this->priority = $task->priority;
        $this->created_at = $task->created_at;
        $this->updated_at = $task->updated_at;
        $this->completed_at = $task->completed_at;
        $this->subTasks = [];
    }
}
