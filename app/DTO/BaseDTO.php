<?php

namespace App\DTO;

class BaseDTO
{
    public function toArray(): array
    {
        $data = (array)$this;

        if (! config('app.debug')) {
            if (isset($data['error'])) {
                unset($data['error']);
            }

            if (isset($data['trace'])) {
                unset($data['trace']);
            }
        }

        return $data;
    }
}
