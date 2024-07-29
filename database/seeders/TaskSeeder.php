<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->createTasksWithSubtasks($user->id,5, null);
        }
    }

    private function createTasksWithSubtasks($userId, $count, $parentId = null): void
    {
        $tasks = Task::factory()->count($count)->state(['user_id' => $userId])->make(['parent_id' => $parentId]);

        foreach ($tasks as $task) {
            $task->save();
            if (rand(0, 1)) {
                $subtaskCount = rand(1, 3);
                $this->createTasksWithSubtasks($userId, $subtaskCount, $task->id);
            }
        }
    }
}

