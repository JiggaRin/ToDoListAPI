<?php

namespace App\Enums;

class StatusEnum
{
    const TODO = 'todo';
    const DONE = 'done';

    public static function all(): array
    {
        return [self::TODO, self::DONE];
    }
}

