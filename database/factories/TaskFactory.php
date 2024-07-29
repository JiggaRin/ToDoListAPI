<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'parent_id' => null,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'todo',
            'priority' => $this->faker->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
            'completed_at' => null,
        ];
    }
}



