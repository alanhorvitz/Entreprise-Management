<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\ProjectMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call role seeder first to ensure roles exist
        $this->call(RoleSeeder::class);
        
        // Create users
        $users = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'director',
                'username' => 'adminuser'
            ],
            [
                'first_name' => 'Project',
                'last_name' => 'Manager',
                'email' => 'pm@example.com',
                'password' => Hash::make('password'),
                'role' => 'project_manager',
                'username' => 'projmanager'
            ],
            [
                'first_name' => 'Team',
                'last_name' => 'Member1',
                'email' => 'member1@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'username' => 'teammember1'
            ],
            [
                'first_name' => 'Team',
                'last_name' => 'Member2',
                'email' => 'member2@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'username' => 'teammember2'
            ]
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $createdUsers[] = User::create($userData);
        }

        // Create projects
        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Revamp the company website with new branding and improved UX',
                'status' => 'in_progress',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60)
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Build a new mobile app for our customers',
                'status' => 'planning',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(90)
            ],
            [
                'name' => 'Marketing Campaign',
                'description' => 'Q3 Digital Marketing Campaign planning and execution',
                'status' => 'in_progress',
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->addDays(15)
            ],
            [
                'name' => 'Product Launch',
                'description' => 'New product launch activities and coordination',
                'status' => 'planning',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(45)
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
                'status' => $projectData['status']
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
            
            // Add team members to projects
            // First team member is on all projects
            ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $createdUsers[2]->id,
                'role' => 'member',
                'joined_at' => Carbon::now()->subDays(rand(10, 30))
            ]);
            
            // Second team member is on alternating projects
            if ($index % 2 == 0) {
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
