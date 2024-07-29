<?php

namespace App\DTO\Task;

use App\DTO\BaseDTO;

class TaskResponseDTO extends BaseDTO
{
    public function __construct(
        public bool $status,
        public string $message,
    ) {
    }
}
