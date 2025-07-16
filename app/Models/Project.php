<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, mixed>
     */
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'department_id',
        'start_date',
        'end_date',
        'status',
        'supervised_by',
        'budget',
        'has_confirmations'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($project) {
            // Detach all members
            $project->members()->detach();
            
            // Delete all tasks
            $project->tasks()->delete();
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'budget' => 'decimal:2',
        'status' => 'string',
        'has_confirmations' => 'boolean'
    ];

    /**
     * Get the department that owns the project.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Alias for creator relationship.
     */
    public function createdBy(): BelongsTo
    {
        return $this->creator();
    }

    /**
     * Get the user who supervises the project.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervised_by');
    }

    /**
     * Alias for supervisor relationship.
     */
    public function supervisedBy(): BelongsTo
    {
        return $this->supervisor();
    }

    /**
     * Get the members of the project.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'project_members')
            ->using(ProjectMember::class)
            ->withPivot('role', 'joined_at')
            ->withTimestamps()
            ->with('user');
    }

    /**
     * Get the tasks associated with the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ProjectsChatMessage::class);
    }

    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    /**
     * Get the order confirmations associated with the project.
     */
    public function orderConfirmations(): HasMany
    {
        return $this->hasMany(OrderConfirmation::class);
    }
}
