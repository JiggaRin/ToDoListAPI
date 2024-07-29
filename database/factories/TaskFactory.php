<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Замість фабрики, тут буде переданий існуючий user_id
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

    public function withSubTasks()
    {
        return $this->afterCreating(function (Task $task) {
            Task::factory()
                ->count(rand(3, 7))
                ->state(['user_id' => $task->user_id, 'parent_id' => $task->id])
                ->create();
        });
    }
}



