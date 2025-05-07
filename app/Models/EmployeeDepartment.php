<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDepartment extends Model
{
    protected $fillable = [
        'employee_id',
        'department_id',
    ];

    /**
     * Get the employee that belongs to this department assignment.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the department that this employee is assigned to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
} 