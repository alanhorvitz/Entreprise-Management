<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TaskComment;
use App\Models\Notification;
use App\Models\Status;
use App\Models\Type;
use App\Models\Reason;
use App\Models\Leave;
use App\Models\Operator;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\EmployeeDepartment;
use App\Models\TaskReminder;
use App\Models\TaskStatusHistory;
use App\Models\ProjectsChatMessage;
use App\Models\DailyReport;
use App\Models\EmailReminder;
use App\Models\RepetitiveTask;
use App\Models\ProjectAttachment;
use App\Models\Attachment;
use App\Models\Event;
use App\Models\Enterprise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MassDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleAndPermissionSeeder::class);

        // Create status
        $status = Status::create([
            'status' => 'active'
        ]);

        // Create types
        $types = [
            ['type' => 'Full Time'],
            ['type' => 'Part Time'],
            ['type' => 'Contract'],
            ['type' => 'Intern']
        ];
        foreach ($types as $type) {
            Type::create($type);
        }

        // Create payment types
        $paymentTypes = [
            ['type' => 'Monthly'],
            ['type' => 'Hourly'],
            ['type' => 'Project']
        ];
        foreach ($paymentTypes as $type) {
            PaymentType::create($type);
        }

        // Create operators
        $operators = [
            ['operator' => 'Maroc Telecom'],
            ['operator' => 'Orange'],
            ['operator' => 'INWI']
        ];
        foreach ($operators as $operator) {
            Operator::create($operator);
        }

        // Create reasons
        $reasons = [
            ['reason' => 'Sick Leave'],
            ['reason' => 'Vacation'],
            ['reason' => 'Personal'],
            ['reason' => 'Emergency']
        ];
        foreach ($reasons as $reason) {
            Reason::create($reason);
        }

        // Create departments
        $departments = [
            ['name' => 'IT', 'description' => 'Information Technology Department'],
            ['name' => 'HR', 'description' => 'Human Resources Department'],
            ['name' => 'Finance', 'description' => 'Finance Department'],
            ['name' => 'Marketing', 'description' => 'Marketing Department'],
            ['name' => 'Operations', 'description' => 'Operations Department']
        ];
        foreach ($departments as $department) {
            Department::create($department);
        }

        // // Create enterprise
        // $enterprise = Enterprise::create([
        //     'name' => 'Test Enterprise',
        //     'description' => 'Test Enterprise Description',
        //     'address' => 'Test Address',
        //     'phone' => '0612345678',
        //     'email' => 'test@enterprise.com',
        //     'website' => 'www.test.com',
        //     'logo' => 'default_logo.png'
        // ]);

        // Create users with roles
        $users = [];
        
        // Create 100 users with proper role distribution
        // 5 directors (5%)
        // 15 supervisors (15%)
        // 80 employees (80%)
        
        // Create directors
        for ($i = 0; $i < 5; $i++) {
            $user = User::create([
                'email' => "director{$i}@example.com",
                'password' => Hash::make('password'),
                'first_name' => "Director",
                'last_name' => "User{$i}",
                'email_verified_at' => Carbon::now(),
            ]);
            $user->assignRole('director');
            $users[] = $user;
        }

        // Create supervisors
        for ($i = 0; $i < 15; $i++) {
            $user = User::create([
                'email' => "supervisor{$i}@example.com",
                'password' => Hash::make('password'),
                'first_name' => "Supervisor",
                'last_name' => "User{$i}",
                'email_verified_at' => Carbon::now(),
            ]);
            $user->assignRole('supervisor');
            $users[] = $user;
        }

        // Create employees
        for ($i = 0; $i < 80; $i++) {
            $user = User::create([
                'email' => "employee{$i}@example.com",
                'password' => Hash::make('password'),
                'first_name' => "Employee",
                'last_name' => "User{$i}",
                'email_verified_at' => Carbon::now(),
            ]);
            $user->assignRole('employee');
            $users[] = $user;
        }

        // Create employee records for all users
        foreach ($users as $user) {
            $employee = Employee::create([
                'employee_code' => 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'cin' => 'CIN' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'cin_attachment' => 'default_cin.pdf',
                'profile_picture' => 'default_profile.jpg',
                'address' => 'Default Address',
                'personal_num' => '06' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                'professional_num' => null,
                'pin' => null,
                'puk' => null,
                'salary' => rand(5000, 20000),
                'hourly_salary' => rand(50, 200),
                'is_project' => rand(0, 1),
                'is_anapec' => rand(0, 1),
                'hours' => rand(20, 40),
                'ice' => null,
                'professional_email' => $user->email,
                'cnss' => null,
                'training_type' => null,
                'school' => null,
                'assurance' => null,
                'operator_id' => Operator::inRandomOrder()->first()->id,
                'user_id' => $user->id,
                'status_id' => $status->id
            ]);

            // Assign departments based on role
            if ($user->hasRole('director')) {
                // Directors have access to all departments
                foreach (Department::all() as $department) {
                    EmployeeDepartment::create([
                        'employee_id' => $employee->id,
                        'department_id' => $department->id,
                    ]);
                }
            } else {
                // Others get assigned to 1-3 random departments
                $departmentCount = rand(1, 3);
                $randomDepartments = Department::inRandomOrder()->limit($departmentCount)->get();
                foreach ($randomDepartments as $department) {
                    EmployeeDepartment::create([
                        'employee_id' => $employee->id,
                        'department_id' => $department->id,
                    ]);
                }
            }

            // Create some leaves
            if (rand(0, 1)) {
                Leave::create([
                    'employee_id' => $employee->id,
                    'reason_id' => Reason::inRandomOrder()->first()->id,
                    'start_date' => Carbon::now()->subDays(rand(1, 30)),
                    'end_date' => Carbon::now()->addDays(rand(1, 14)),
                    'status' => ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])],
                ]);
            }

            // Create some payments
            if (rand(0, 1)) {
                Payment::create([
                    'employee_id' => $employee->id,
                    'payment_type_id' => PaymentType::inRandomOrder()->first()->id,
                    'gross' => rand(1000, 20000),
                    'cnss' => Carbon::now()->subDays(rand(1, 30)),
                    'tax_rate' => rand(1, 10),
                    'income_tax' => rand(1, 10),
                    'net' => rand(1, 10),
                ]);
            }
        }

        // Create projects
        $projects = [];
        for ($i = 0; $i < 30; $i++) {
            $project = Project::create([
                'name' => "Project {$i}",
                'description' => "Description for project {$i}",
                'created_by' => $users[array_rand($users)]->id,
                'supervised_by' => $users[array_rand($users)]->id,
                'department_id' => Department::inRandomOrder()->first()->id,
                'start_date' => Carbon::now()->subDays(rand(1, 30)),
                'end_date' => Carbon::now()->addDays(rand(30, 90)),
                'status' => ['planning', 'in_progress', 'completed', 'on_hold'][array_rand(['planning', 'in_progress', 'completed', 'on_hold'])],
                'budget' => rand(10000, 100000)
            ]);
            $projects[] = $project;

            // Add project members
            $memberCount = rand(3, 8);
            $randomEmployees = Employee::inRandomOrder()->limit($memberCount)->get();
            foreach ($randomEmployees as $employee) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'employee_id' => $employee->id,
                    'role' => ['member', 'team_leader'][array_rand(['member', 'team_leader'])],
                    'joined_at' => Carbon::now()->subDays(rand(1, 30))
                ]);
            }

            // Add project attachments
            // if (rand(0, 1)) {
            //     ProjectAttachment::create([
            //         'project_id' => $project->id,
            //         'file_name' => "attachment_{$i}.pdf",
            //         'file_path' => "attachments/attachment_{$i}.pdf",
            //         'file_type' => 'application/pdf',
            //         'file_size' => rand(1000, 10000)
            //     ]);
            // }

            // Add project chat messages
            $messageCount = rand(5, 20);
            for ($j = 0; $j < $messageCount; $j++) {
                ProjectsChatMessage::create([
                    'project_id' => $project->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'message' => "Message {$j} for project {$i}",
                    'created_at' => Carbon::now()->subDays(rand(1, 30))
                ]);
            }
        }

        // Create tasks
        foreach ($projects as $project) {
            $taskCount = rand(10, 30);
            for ($i = 0; $i < $taskCount; $i++) {
                $task = Task::create([
                    'title' => "Task {$i} for Project {$project->id}",
                    'description' => "Description for task {$i}",
                    'project_id' => $project->id,
                    'created_by' => $users[array_rand($users)]->id,
                    'due_date' => Carbon::now()->addDays(rand(1, 30)),
                    'priority' => ['high', 'medium', 'low'][array_rand(['high', 'medium', 'low'])],
                    'current_status' => ['todo', 'in_progress', 'completed'][array_rand(['todo', 'in_progress', 'completed'])],
                    'start_date' => Carbon::now()->subDays(rand(1, 15)),
                    'status' => ['pending_approval', 'approved'][array_rand(['pending_approval', 'approved'])],
                    'is_repetitive' => rand(0, 1)
                ]);

                // Add task assignments
                $assignmentCount = rand(1, 3);
                $randomEmployees = Employee::inRandomOrder()->limit($assignmentCount)->get();
                foreach ($randomEmployees as $employee) {
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'employee_id' => $employee->id,
                        'assigned_by' => $users[array_rand($users)]->id,
                        'assigned_at' => Carbon::now()->subDays(rand(1, 10))
                    ]);
                }

                // Add task comments
                $commentCount = rand(1, 5);
                for ($j = 0; $j < $commentCount; $j++) {
                    TaskComment::create([
                        'task_id' => $task->id,
                        'user_id' => $users[array_rand($users)]->id,
                        'text' => "Comment {$j} for task {$task->id}",
                        'created_at' => Carbon::now()->subDays(rand(1, 5))
                    ]);
                }

                // Add task status history
                TaskStatusHistory::create([
                    'task_id' => $task->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'old_status' => 'todo',
                    'new_status' => $task->current_status,
                    'changed_at' => Carbon::now(),
                    'notes' => 'Initial status'
                ]);

                // Add task reminders
                // if (rand(0, 1)) {
                //     TaskReminder::create([
                //         'task_id' => $task->id,
                //         'user_id' => $users[array_rand($users)]->id,
                //         'reminder_date' => Carbon::now()->addDays(rand(1, 7)),
                //         'is_sent' => rand(0, 1)
                //     ]);
                // }

                // Add repetitive tasks
                if ($task->is_repetitive) {
                    RepetitiveTask::create([
                        'task_id' => $task->id,
                        'project_id' => $project->id,
                        'created_by' => $task->created_by,
                        'repetition_rate' => ['daily', 'weekly', 'monthly', 'yearly'][array_rand(['daily', 'weekly', 'monthly', 'yearly'])],
                        'recurrence_interval' => Carbon::now()->addDays(rand(1, 30)),
                        'recurrence_days' => rand(1, 7),
                        'recurrence_month_day' => rand(1, 28),
                        'start_date' => Carbon::now()->subDays(rand(0, 30))->timestamp,
                        'end_date' => Carbon::now()->addDays(rand(30, 90))->timestamp,
                        'next_occurrence' => Carbon::now()->addDays(rand(1, 7))->timestamp
                    ]);
                }
            }
        }

        // Create daily reports
        foreach ($users as $user) {
            $reportCount = rand(5, 15);
            $usedDates = [];
            for ($i = 0; $i < $reportCount; $i++) {
                $date = Carbon::now()->subDays(rand(1, 30))->format('Y-m-d');
                $project = $projects[array_rand($projects)];
                
                // Create a unique key for this combination
                $uniqueKey = "{$user->id}-{$project->id}-{$date}";
                
                // Skip if this combination already exists
                if (in_array($uniqueKey, $usedDates)) {
                    continue;
                }
                
                $usedDates[] = $uniqueKey;
                
                DailyReport::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'date' => $date,
                    'summary' => "Daily report for user {$user->id}"
                ]);
            }
        }

        // Create email reminders
        // foreach ($users as $user) {
        //     if (rand(0, 1)) {
        //         EmailReminder::create([
        //             'user_id' => $user->id,
        //             'reminder_date' => Carbon::now()->addDays(rand(1, 30)),
        //             'is_sent' => rand(0, 1),
        //             'type' => ['task', 'project', 'general'][array_rand(['task', 'project', 'general'])],
        //             'message' => "Reminder for user {$user->id}"
        //         ]);
        //     }
        // }

        // Create events
        // for ($i = 0; $i < 20; $i++) {
        //     Event::create([
        //         'title' => "Event {$i}",
        //         'description' => "Description for event {$i}",
        //         'start_date' => Carbon::now()->addDays(rand(1, 30)),
        //         'end_date' => Carbon::now()->addDays(rand(31, 60)),
        //         'location' => "Location {$i}",
        //         'created_by' => $users[array_rand($users)]->id
        //     ]);
        // }

        // Create notifications
        foreach ($users as $user) {
            $notificationCount = rand(5, 15);
            for ($i = 0; $i < $notificationCount; $i++) {
                Notification::create([
                    'user_id' => $user->id,
                    'from_id' => $users[array_rand($users)]->id,
                    'title' => "Notification {$i}",
                    'message' => "Message for notification {$i}",
                    'type' => ['assignment','reminder','status_change','mention','approval'][array_rand(['assignment','reminder','status_change','mention','approval'])],
                    'is_read' => rand(0, 1)
                ]);
            }
        }
    }
} 