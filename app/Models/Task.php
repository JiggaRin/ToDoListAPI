<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'title',
        'description',
        'status',
        'priority',
        'created_at',
        'updated_at',
        'completed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function subTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * @return bool
     */
    public function scopeHasIncompleteSubtasks($query)
    {
        return $query->whereHas('subtasks', function ($q) {
            $q->where('status', 'todo');
        });
    }

    /**
     * @return bool
     */
    public function scopeCanBeMarkedAsDone()
    {
        return !$this->hasIncompleteSubTasks();
    }
}
