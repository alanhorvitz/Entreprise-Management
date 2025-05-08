<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\User;
use App\Models\Department;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class EditProject extends Component
{
    public Project $project;
    
    public $name;
    public $description;
    public $department_id;
    public $start_date;
    public $end_date;
    public $status;
    public $supervised_by;
    public $team_leader_id;
    public $budget;
    public $selectedTeamMembers = [];
    public $teamMembers = [];
    public $send_notifications = true;
    public $departmentMembers = [];
    public $availableSupervisors = [];
    public $is_featured;

    // Add message properties
    public $showMessage = false;
    public $message = '';
    public $messageType = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'status' => 'required|in:planning,in_progress,completed,on_hold',
        'supervised_by' => 'required|exists:users,id',
        'team_leader_id' => 'required|exists:users,id',
        'budget' => 'nullable|numeric|min:0',
        'selectedTeamMembers' => 'required|array|min:1',
        'selectedTeamMembers.*' => 'exists:users,id'
    ];

    public function mount(Project $project)
    {
        if (!auth()->user()->hasPermissionTo('edit projects')) {
            abort(403, 'Unauthorized action.');
        }

        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->department_id = $project->department_id;
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
        $this->status = $project->status;
        $this->budget = $project->budget;
        $this->supervised_by = $project->supervised_by;

        // Load all project members including team leader
        $projectMembers = DB::table('project_members')
            ->select('users.id', 'project_members.role')
            ->join('employees', 'project_members.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('project_members.project_id', $project->id)
            ->get();

        // Set selected team members
        $this->selectedTeamMembers = $projectMembers->pluck('id')->toArray();

        // Set team leader
        $teamLeader = $projectMembers->firstWhere('role', 'team_leader');
        if ($teamLeader) {
            $this->team_leader_id = $teamLeader->id;
        }

        // Load team members for dropdown
        $this->teamMembers = User::whereIn('id', $this->selectedTeamMembers)
            ->select('id', 'first_name', 'last_name')
            ->get();

        // Load department members and supervisors for selection
        if ($this->department_id) {
            $this->loadDepartmentMembers();
            $this->loadSupervisors();
        }
    }
 
    public function updatedSelectedTeamMembers()
    {
        // If team leader is unselected, reset team leader
        if ($this->team_leader_id && !in_array($this->team_leader_id, $this->selectedTeamMembers)) {
            $this->team_leader_id = null;
        }

        // Update team members list for dropdown
        $this->teamMembers = User::whereIn('id', $this->selectedTeamMembers)
            ->select('id', 'first_name', 'last_name')
            ->get();
    }

    public function updatedDepartmentId($value)
    {
        $this->selectedTeamMembers = [];
        // Reset all member-related fields
        $this->reset([
            'team_leader_id',
            'supervised_by'
        ]);

        // Clear existing team members
        $this->teamMembers = collect();
        
        // Load new department members if a department is selected
        if ($value) {
            $this->loadDepartmentMembers();
            $this->loadSupervisors();
        } else {
            $this->departmentMembers = collect();
            $this->availableSupervisors = collect();
        }
    }

    /**
     * Load department members including those with secondary assignments
     */
    private function loadDepartmentMembers()
    {
        $this->departmentMembers = User::role('employee')
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'supervisor');
            })
            ->whereHas('employee', function($query) {
                $query->whereHas('departments', function($query) {
                    $query->where('department_id', $this->department_id);
                });
            })
            ->get();
    }

    /**
     * Load all supervisors, prioritizing those from the current department
     */
    private function loadSupervisors()
    {
        // Get all supervisors
        $this->availableSupervisors = User::whereHas('roles', function($query) {
                $query->where('name', 'supervisor');
            })
            ->orderByRaw("CASE 
                WHEN EXISTS (
                    SELECT 1 FROM employees e 
                    JOIN employee_departments ed ON e.id = ed.employee_id 
                    WHERE e.user_id = users.id 
                    AND ed.department_id = ?
                ) THEN 0 
                ELSE 1 
            END", [$this->department_id])
            ->get();
    }

    private function showMessage($message, $type)
    {
        $this->message = $message;
        $this->messageType = $type;
        $this->showMessage = true;
    }

    public function update()
    {
        if (!auth()->user()->hasPermissionTo('edit projects')) {
            $this->showMessage('You do not have permission to edit projects.', 'error');
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            // Update project basic information
            $this->project->update([
                'name' => $this->name,
                'description' => $this->description,
                'department_id' => $this->department_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'status' => $this->status,
                'budget' => $this->budget,
                'supervised_by' => $this->supervised_by,
            ]);

            // Get employee IDs for selected users
            $employeeIds = \App\Models\Employee::whereIn('user_id', $this->selectedTeamMembers)
                ->pluck('id')
                ->toArray();

            // Prepare member data
            $memberData = [];
            foreach ($employeeIds as $employeeId) {
                $userId = \App\Models\Employee::find($employeeId)->user_id;
                $memberData[$employeeId] = [
                    'role' => $userId == $this->team_leader_id ? 'team_leader' : 'member',
                    'joined_at' => now()
                ];
            }

            // Sync project members
            $this->project->members()->sync($memberData);

            // Handle notifications if enabled
            if ($this->send_notifications) {
                // Notify about role changes and new assignments
                foreach ($memberData as $employeeId => $data) {
                    $employee = \App\Models\Employee::find($employeeId);
                    $userId = $employee->user_id;
                    
                    if ($userId !== auth()->id()) {
                        $roleTitle = $data['role'] === 'team_leader' ? 'Team Manager' : 'Member';
                        
                        Notification::create([
                            'user_id' => $userId,
                            'from_id' => auth()->id(),
                            'title' => 'Project Role Assignment',
                            'message' => "You have been assigned as {$roleTitle} for project: {$this->project->name}",
                            'type' => 'assignment',
                            'data' => json_encode([
                                'project_id' => $this->project->id,
                                'project_name' => $this->project->name,
                                'role' => $data['role'],
                                'updated_by' => auth()->user()->name
                            ]),
                            'is_read' => false
                        ]);
                    }
                }

                // Notify new supervisor if changed
                if ($this->project->getOriginal('supervised_by') !== $this->supervised_by) {
                    Notification::create([
                        'user_id' => $this->supervised_by,
                        'from_id' => auth()->id(),
                        'title' => 'Project Supervision Assignment',
                        'message' => "You have been assigned as supervisor for project: {$this->project->name}",
                        'type' => 'assignment',
                        'data' => json_encode([
                            'project_id' => $this->project->id,
                            'project_name' => $this->project->name,
                            'role' => 'supervisor',
                            'updated_by' => auth()->user()->name
                        ]),
                        'is_read' => false
                    ]);
                }
            }

            DB::commit();

            session()->flash('notify', [
                'type' => 'success',
                'message' => 'Project updated successfully!'
            ]);

            return $this->redirect(route('projects.show', $this->project), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            
            session()->flash('notify', [
                'type' => 'error',
                'message' => 'Failed to update project: ' . $e->getMessage()
            ]);
            
            return null;
        }
    }

    public function updatedTeamLeaderId()
    {
        // If team leader is selected, add them to selectedTeamMembers if not already there
        if ($this->team_leader_id && !in_array($this->team_leader_id, $this->selectedTeamMembers)) {
            $this->selectedTeamMembers[] = $this->team_leader_id;
        }
    }

    public function render()
    {
        return view('livewire.edit-project', [
            'departments' => Department::all(),
            'departmentMembers' => $this->departmentMembers,
            'supervisors' => $this->availableSupervisors,
        ]);
    }
} 