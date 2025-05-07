<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'employee_id'
    ];

    protected $casts = [
        'price' => 'double'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
} 