<?php

namespace App\DTO\Task;

class ChangeTaskStatusDTO
{
    public string $status;
    public string|null $completed_at;

    public function __construct(string $status, string|null $completed_at = null)
    {
        $this->status = $status;
        $this->completed_at = $completed_at;
    }

    public function setCompletedAt(string|null $completed_at): void
    {
        $this->completed_at = $completed_at;
    }
}


