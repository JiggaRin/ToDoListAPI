<?php

namespace App\Enums;

class PriorityEnum
{
    const LOW = 1;
    const MEDIUM_LOW = 2;
    const MEDIUM = 3;
    const MEDIUM_HIGH = 4;
    const HIGH = 5;

    /**
     * Get all priority values.
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::LOW,
            self::MEDIUM_LOW,
            self::MEDIUM,
            self::MEDIUM_HIGH,
            self::HIGH,
        ];
    }
}

