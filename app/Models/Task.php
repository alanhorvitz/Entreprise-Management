<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'created_by',
        'due_date',
        'priority',
        'current_status',
        'start_date',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'timestamp',
            'due_date' => 'date',
            'start_date' => 'date',
        ];
    }

    public function repetitiveTask(): BelongsTo
    {
        return $this->belongsTo(RepetitiveTask::class, 'id', 'task_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }
    
    public function taskComments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }
    
    public function statusHistory(): HasMany
    {
        return $this->hasMany(TaskStatusHistory::class);
    }
    
    /**
     * Get all assigned users for this task
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'user_id')
            ->withPivot('assigned_by', 'assigned_at')
            ->withTimestamps();
    }
}
