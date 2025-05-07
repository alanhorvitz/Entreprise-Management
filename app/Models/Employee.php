<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'cin',
        'cin_attachment',
        'profile_picture',
        'address',
        'personal_num',
        'professional_num',
        'pin',
        'puk',
        'salary',
        'hourly_salary',
        'is_project',
        'hours',
        'ice',
        'professional_email',
        'cnss',
        'assurance',
        'operator_id',
        'user_id',
        'status_id',
        'department_id'
    ];

    protected $casts = [
        'is_project' => 'boolean',
        'salary' => 'double',
        'hourly_salary' => 'double'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'employee_departments');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'type_employees');
    }

    public function freelancerProjects()
    {
        return $this->hasMany(FreelancerProject::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignments');
    }

    public function taskReminders()
    {
        return $this->hasMany(TaskReminder::class);
    }
} 