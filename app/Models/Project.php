<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'start_date',
        'end_date',
        'status',
        'supervised_by',
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
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function repetitiveTask(): BelongsTo
    {
        return $this->belongsTo(RepetitiveTask::class, 'id', 'project_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function supervised_by(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
