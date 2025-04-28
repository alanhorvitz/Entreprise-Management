<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\ProjectMember;
use App\Models\UserDepartment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create director user
        $director = User::create([
            'first_name' => 'Director',
            'last_name' => 'User',
            'email' => 'director@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
            'is_active' => true,
        ]);
        $director->assignRole('director');

        // Create supervisor user
        $supervisor = User::create([
            'first_name' => 'Supervisor',
            'last_name' => 'User',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
            'is_active' => true,
        ]);
        $supervisor->assignRole('supervisor');

        // Create employee user
        $employee = User::create([
            'first_name' => 'Employee',
            'last_name' => 'User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
            'is_active' => true,
        ]);
        $employee->assignRole('employee');

        // Call department seeder before creating users
        $this->call(DepartmentSeeder::class);
        
        // Create users
        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'director',
                'department_id' => rand(1, 10)
            ],
            [
                'first_name' => 'Project',
                'last_name' => 'Manager',
                'email' => 'pm@example.com',
                'password' => Hash::make('password'),
                'role' => 'project_manager',
                'department_id' => rand(1, 10)
                ],
            [
                'first_name' => 'Team',
                'last_name' => 'Member1',
                'email' => 'member1@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department_id' => rand(1, 10)
                ],
            [
                'first_name' => 'Team',
                'last_name' => 'Member2',
                'email' => 'member2@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department_id' => rand(1, 10)
                ],
            // Adding more employees
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'department_id' => rand(1, 10)
                ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'email' => 'michael@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department_id' => rand(1, 10)
                ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Clark',
                'email' => 'emily@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department_id' => rand(1, 10)
                ],
            [
                'first_name' => 'James',
                'last_name' => 'Brown',
                'email' => 'james@example.com',
                'password' => Hash::make('password'),
                'role' => 'project_manager',
                'department_id' => rand(1, 10)
                ],
            [
                'first_name' => 'Jessica',
                'last_name' => 'Miller',
                'email' => 'jessica@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department_id' => rand(1, 10)
                ],
            [
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'department_id' => rand(1, 10)
                    ]
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = User::create($userData);
        }

        // Assign users to multiple departments
        foreach ($createdUsers as $user) {
            // Ensure user is assigned to their primary department
            UserDepartment::create([
                'user_id' => $user->id,
                'department_id' => $user->department_id
            ]);
            
            // Randomly assign additional departments (0-2 more departments)
            $additionalDepartments = rand(0, 2);
            $availableDepartments = range(1, 10);
            // Remove primary department from available departments
            $availableDepartments = array_diff($availableDepartments, [$user->department_id]);
            
            // Shuffle and take random number of departments
            shuffle($availableDepartments);
            $selectedDepartments = array_slice($availableDepartments, 0, $additionalDepartments);
            
            foreach ($selectedDepartments as $departmentId) {
                UserDepartment::create([
                    'user_id' => $user->id,
                    'department_id' => $departmentId
                ]);
            }
        }

        // Create projects
        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Revamp the company website with new branding and improved UX',
                'status' => 'in_progress',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'department_id' => rand(1, 10),
                'budget' => rand(10000, 100000),
                'supervised_by' => rand(1, 10)
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Build a new mobile app for our customers',
                'status' => 'planning',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(90),
                'department_id' => rand(1, 10),
                'budget' => rand(10000, 100000),
                'supervised_by' => rand(1, 10)
            ],
            [
                'name' => 'Marketing Campaign',
                'description' => 'Q3 Digital Marketing Campaign planning and execution',
                'status' => 'in_progress',
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->addDays(15),
                'department_id' => rand(1, 10),
                'budget' => rand(10000, 100000),
                'supervised_by' => rand(1, 10)
            ],
            [
                'name' => 'Product Launch',
                'description' => 'New product launch activities and coordination',
                'status' => 'planning',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(45),
                'department_id' => rand(1, 10),
                'budget' => rand(10000, 100000),
                'supervised_by' => rand(1, 10)
            ]
        ];

        $createdProjects = [];
        foreach ($projects as $index => $projectData) {
            // Alternate between admin and manager as creators
            $creatorUser = $index % 2 == 0 ? $createdUsers[0] : $createdUsers[1];
            
            $project = Project::create([
                'name' => $projectData['name'],
                'description' => $projectData['description'],
                'created_by' => $creatorUser->id,
                'supervised_by' => $createdUsers[1]->id, // PM supervises all projects
                'start_date' => $projectData['start_date'],
                'end_date' => $projectData['end_date'],
                'status' => $projectData['status'],
                'department_id' => $projectData['department_id'],
                'budget' => $projectData['budget']
            ]);
            
            $createdProjects[] = $project;
            
            // Add project members
            // Project manager is on all projects
            ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $createdUsers[1]->id,
                'role' => 'project_manager',
                'joined_at' => Carbon::now()->subDays(rand(30, 60))
            ]);
            
            // Add second project manager to some projects
            if ($index % 2 == 0) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $createdUsers[7]->id, // James Brown (new PM)
                    'role' => 'project_manager',
                    'joined_at' => Carbon::now()->subDays(rand(25, 55))
                ]);
            }
            
            // Add supervisor to projects
            ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $createdUsers[4]->id, // Sarah Johnson (supervisor)
                'role' => 'member',
                'joined_at' => Carbon::now()->subDays(rand(28, 58))
            ]);
            
            // Add team members to projects
            // First team member is on all projects
            ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $createdUsers[2]->id,
                'role' => 'member',
                'joined_at' => Carbon::now()->subDays(rand(10, 30))
            ]);
            
            // Add new employees to projects with different patterns
            // Michael Williams on all projects
            ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $createdUsers[5]->id, 
                'role' => 'member',
                'joined_at' => Carbon::now()->subDays(rand(15, 35))
            ]);
            
            // Emily Clark and Jessica Miller on alternating projects
            if ($index % 2 == 0) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $createdUsers[6]->id, // Emily
                    'role' => 'member',
                    'joined_at' => Carbon::now()->subDays(rand(10, 30))
                ]);
            } else {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $createdUsers[8]->id, // Jessica
                    'role' => 'member',
                    'joined_at' => Carbon::now()->subDays(rand(10, 30))
                ]);
            }
            
            // David Wilson on even-indexed projects
            if ($index % 2 == 0) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $createdUsers[9]->id, // David
                    'role' => 'member',
                    'joined_at' => Carbon::now()->subDays(rand(5, 25))
                ]);
            }
            
            // Second team member is on alternating projects
            if ($index % 2 == 1) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $createdUsers[3]->id,
                    'role' => 'member',
                    'joined_at' => Carbon::now()->subDays(rand(10, 30))
                ]);
            }
        }

        // Define task titles by project
        $taskTitles = [
            'Website Redesign' => [
                'Homepage Redesign',
                'Contact Form Implementation',
                'Mobile Responsiveness',
                'SEO Optimization',
                'Content Migration'
            ],
            'Mobile App Development' => [
                'User Authentication',
                'Payment Gateway Integration',
                'Push Notifications',
                'Offline Mode Implementation',
                'Beta Testing'
            ],
            'Marketing Campaign' => [
                'Social Media Strategy',
                'Email Newsletter Design',
                'Analytics Setup',
                'Content Calendar',
                'Influencer Outreach'
            ],
            'Product Launch' => [
                'Press Release Drafting',
                'Demo Video Production',
                'Launch Event Planning',
                'Promotional Materials',
                'Customer Feedback Collection'
            ]
        ];

        // Create tasks for each project
        $priorities = ['high', 'medium', 'low'];
        $statuses = ['pending_approval', 'approved'];
        $currentStatuses = ['todo', 'in_progress', 'completed'];
        
        foreach ($createdProjects as $project) {
            // Get the specific task titles for this project
            $projectTaskTitles = $taskTitles[$project->name] ?? ["Task for {$project->name}"];
            
            // Create tasks for this project
            foreach ($projectTaskTitles as $taskTitle) {
                // Create the task
                $task = Task::create([
                    'title' => $taskTitle,
                    'description' => "This is a detailed description for the task: {$taskTitle}",
                    'project_id' => $project->id,
                    'created_by' => $createdUsers[1]->id, // PM creates all tasks
                    'due_date' => Carbon::now()->addDays(rand(-5, 30)), // Some in past, some in future
                    'priority' => $priorities[array_rand($priorities)],
                    'current_status' => $currentStatuses[array_rand($currentStatuses)],
                    'start_date' => Carbon::now()->subDays(rand(1, 15)),
                    'status' => $statuses[array_rand($statuses)]
                ]);
                
                // Assign tasks in a distributed pattern
                // Some tasks assigned to team member 1
                if (rand(0, 1) == 1) {
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'user_id' => $createdUsers[2]->id, // Team member 1
                        'assigned_by' => $createdUsers[1]->id, // PM assigns
                        'assigned_at' => Carbon::now()->subDays(rand(1, 10))
                    ]);
                }
                
                // Some tasks assigned to team member 2
                if (rand(0, 1) == 1) {
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'user_id' => $createdUsers[3]->id, // Team member 2
                        'assigned_by' => $createdUsers[1]->id, // PM assigns
                        'assigned_at' => Carbon::now()->subDays(rand(1, 10))
                    ]);
                }
                
                // Some tasks assigned to new employees
                $newEmployeeIds = [5, 6, 8, 9]; // Michael, Emily, Jessica, David
                foreach ($newEmployeeIds as $employeeId) {
                    if (rand(0, 3) == 0) { // 25% chance for each new employee
                        TaskAssignment::create([
                            'task_id' => $task->id,
                            'user_id' => $createdUsers[$employeeId]->id,
                            'assigned_by' => rand(0, 1) == 0 ? $createdUsers[1]->id : $createdUsers[7]->id, // Assigned by either PM
                            'assigned_at' => Carbon::now()->subDays(rand(1, 10))
                        ]);
                    }
                }
                
                // A few tasks assigned to supervisor
                if (rand(0, 7) == 0) { // ~12.5% chance
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'user_id' => $createdUsers[4]->id, // Sarah (supervisor)
                        'assigned_by' => $createdUsers[1]->id, // PM assigns
                        'assigned_at' => Carbon::now()->subDays(rand(1, 10))
                    ]);
                }
                
                // A few tasks assigned to the second PM
                if (rand(0, 6) == 0) { // ~16.6% chance
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'user_id' => $createdUsers[7]->id, // James (PM)
                        'assigned_by' => $createdUsers[7]->id, // Self-assigned
                        'assigned_at' => Carbon::now()->subDays(rand(1, 10))
                    ]);
                }
                
                // A few tasks assigned to PM themselves
                if (rand(0, 5) == 0) {
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'user_id' => $createdUsers[1]->id, // PM
                        'assigned_by' => $createdUsers[1]->id, // Self-assigned
                        'assigned_at' => Carbon::now()->subDays(rand(1, 10))
                    ]);
                }
            }
        }
        
        // Create repetitive tasks
        $this->call(RepetitiveTaskSeeder::class);
    }
}
