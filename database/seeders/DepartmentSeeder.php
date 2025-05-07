<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information Technology',
                'description' => 'Responsible for managing and maintaining technology infrastructure',
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Manages employee relations, recruitment, and workplace policies',
            ],
            [
                'name' => 'Finance',
                'description' => 'Handles financial planning, budgeting, and accounting',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Develops and implements marketing strategies',
            ],
            [
                'name' => 'Sales',
                'description' => 'Manages client relationships and revenue generation',
            ],
            [
                'name' => 'Research & Development',
                'description' => 'Focuses on innovation and product development',
            ],
            [
                'name' => 'Operations',
                'description' => 'Oversees day-to-day business operations',
            ],
            [
                'name' => 'Customer Service',
                'description' => 'Provides support and assistance to customers',
            ],
            [
                'name' => 'Legal',
                'description' => 'Handles legal matters and compliance',
            ],
            [
                'name' => 'Quality Assurance',
                'description' => 'Ensures product and service quality standards',
            ]
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        // After creating departments, we can assign supervisors
        $departments = Department::all();
        
        // foreach ($departments as $department) {
        //     // Try to find a supervisor for this department
        //     $supervisor = User::where('role', 'supervisor')
        //                  ->whereNull('department_id')
        //                  ->inRandomOrder()
        //                  ->first();
            
        //     if ($supervisor) {
        //         $department->manager_id = $supervisor->id;
        //         $department->save();
                
        //         // Assign the supervisor to this department
        //         $supervisor->department_id = $department->id;
        //         $supervisor->save();
        //     }
        // }
    }
} 