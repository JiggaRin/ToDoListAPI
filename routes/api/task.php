<?php

use App\Http\Controllers\API\Task\TaskController;
use Illuminate\Support\Facades\Route;



Route::prefix('tasks')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');

    Route::middleware('userCheck:App\Models\Task')->group(function () {
        Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::put('/{task}/status', [TaskController::class, 'changeStatus'])->name('tasks.change-status');
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
});
