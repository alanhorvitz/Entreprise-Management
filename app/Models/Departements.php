<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'parent_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship to manager (User model)
    // public function manager()
    // {
    //     return $this->belongsTo(User::class, 'manager_id');
    // }

    //  Relationship to parent department
    // public function parent()
    // {
    //     return $this->belongsTo(Department::class, 'parent_id');
    // }

    // Relationship to child departments
    // public function children()
    // {
    //     return $this->hasMany(Department::class, 'parent_id');
    // }

    // Relationship to employees in this department
    // public function employees()
    // {
    //     return $this->hasMany(User::class);
    // }
}