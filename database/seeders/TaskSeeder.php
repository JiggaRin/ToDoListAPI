<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Task::factory()
                ->count(5)
                ->state(['user_id' => $user->id])
                ->withSubTasks()
                ->create();
        }
    }
}

