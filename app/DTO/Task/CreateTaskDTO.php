<?php

namespace App\DTO\Task;

class CreateTaskDTO
{
    public int $user_id;
    public string $title;
    public string $status;
    public int $priority;
    public int|null $parent_id;
    public string|null $description;
    public string|null $completed_at;

    public function __construct(
        int         $user_id,
        string      $title,
        string|null $status = null,
        int|null    $priority = null,
        int         $parent_id = null,
        string|null $description = null,
        string|null $completed_at = null,
    )
    {
        $this->user_id = $user_id;
        $this->title = $title;
        $this->status = $status ?? 'todo';
        $this->priority = $priority ?? 1;
        $this->parent_id = $parent_id ?? null;
        $this->description = $description ?? null;
        $this->completed_at = $completed_at ?? null;
    }
}


