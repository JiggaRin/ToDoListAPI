<?php

namespace App\DTO\Task;

class UpdateTaskDTO
{
    public string|null $title;
    public string|null $status;
    public int|null $priority;
    public string|null $description;
    public int|null $parent_id;

    public string|null $completed_at;

    public function __construct(
        string|null $title = null,
        string|null $status = 'todo',
        int|null    $priority = 1,
        int|null    $parent_id = null,
        string|null $description = null,
        string|null $completed_at = null

    )
    {
        $this->title = $title ?? null;
        $this->status = $status ?? null;
        $this->priority = $priority ?? null;
        $this->parent_id = $parent_id ?? null;
        $this->description = $description ?? null;
        $this->completed_at = $completed_at ?? null;
    }
}


