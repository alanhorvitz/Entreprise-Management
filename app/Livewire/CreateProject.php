<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use App\Models\ProjectMember;
use App\Models\UserDepartment;
use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class CreateProject extends Component
{
    public $name;
    public $description;
    public $department_id;
    public $start_date;
    public $end_date;
    public $status;
    public $budget;
    public $team_leader_id;
    public $supervised_by;
    public $selectedTeamMembers = [];
    public $send_notifications = false;
    public $is_featured = false;

    public $departmentMembers = [];
    public $availableSupervisors = [];
    public $availableTeamManagers = [];
    
    // Add message properties
    public $showMessage = false;
    public $message = '';
    public $messageType = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planning,in_progress,completed,on_hold',
            'budget' => 'required|numeric|min:0',
            'team_leader_id' => 'required|exists:users,id',
            'supervised_by' => 'required|exists:users,id',
            'selectedTeamMembers' => 'required|array|min:1',
            'selectedTeamMembers.*' => 'exists:users,id'
        ];
    }

    protected $listeners = ['departmentSelected' => 'loadDepartmentMembers'];

    public function mount()
    {
        if (!auth()->user()->hasPermissionTo('create projects')) {
            abort(403, 'You do not have permission to create projects.');
        }
        
        $this->status = 'planning';
        $this->selectedTeamMembers = [];
        $this->availableTeamManagers = collect();
        $this->loadSupervisors();
    }
    
    public function updatedDepartmentId($value)
    {
        $this->loadDepartmentMembers();
        $this->loadSupervisors();
        $this->reset(['team_leader_id', 'selectedTeamMembers']);
    }

    public function updatedSelectedTeamMembers($value)
    {
        // If team leader is unselected from team members, reset team leader
        if ($this->team_leader_id && !in_array($this->team_leader_id, $this->selectedTeamMembers)) {
            $this->team_leader_id = null;
        }

        // Update available team managers based on selected team members
        if (!empty($this->selectedTeamMembers)) {
            $this->availableTeamManagers = User::whereIn('id', $this->selectedTeamMembers)
                ->get(['id', 'first_name', 'last_name']);
        } 
        else {
            $this->availableTeamManagers = collect();
            $this->team_leader_id = null; // Reset team leader if no members selected
        }
    }

    public function loadDepartmentMembers()
    {
        if ($this->department_id) {
            // Get all employees assigned to this department through employee_departments table
            $this->departmentMembers = User::role('employee')
                ->whereHas('employee', function($query) {
                    $query->whereHas('departments', function($query) {
                        $query->where('department_id', $this->department_id);
                    });
                })
                ->where('is_active', true)
                ->get();

            // Reset team members and manager when department changes
            $this->selectedTeamMembers = [];
            $this->team_leader_id = null;
            $this->availableTeamManagers = collect();
        } else {
            $this->departmentMembers = [];
            $this->selectedTeamMembers = [];
            $this->team_leader_id = null;
            $this->availableTeamManagers = collect();
        }
    }

    /**
     * Load all supervisors, prioritizing those from the selected department
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

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create projects')) {
            $this->showMessage('You do not have permission to create projects.', 'error');
            return;
        }

        $this->validate();

        try {
            $project = Project::create([
                'name' => $this->name,
                'description' => $this->description,
                'department_id' => $this->department_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'status' => $this->status,
                'budget' => $this->budget,
                'supervised_by' => $this->supervised_by,
                'created_by' => Auth::id(),
                'is_featured' => $this->is_featured
            ]);

            // Get employee IDs
            $teamLeaderEmployee = Employee::where('user_id', $this->team_leader_id)->first();

            // Attach team leader
            if ($teamLeaderEmployee) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'employee_id' => $teamLeaderEmployee->id,
                    'role' => 'team_leader',
                    'joined_at' => now(),
                ]);
            }

            // Attach team members
            if (!empty($this->selectedTeamMembers)) {
                foreach ($this->selectedTeamMembers as $memberId) {
                    $employee = Employee::where('user_id', $memberId)->first();
                    if ($employee) {
                        // Skip if already added as team leader
                        if ($employee->id !== $teamLeaderEmployee?->id) {
                            ProjectMember::create([
                                'project_id' => $project->id,
                                'employee_id' => $employee->id,
                                'role' => 'member',
                                'joined_at' => now(),
                            ]);
                        }
                    }
                }
            }

            if ($this->send_notifications) {
                // Notify directors about new project
                $directors = User::role('director')
                    ->where('id', '!=', auth()->id())
                    ->get();

                foreach ($directors as $director) {
                    Notification::create([
                        'user_id' => $director->id,
                        'from_id' => auth()->id(),
                        'title' => 'New Project Created',
                        'message' => 'A new project has been created by ' . auth()->user()->name . ': ' . $project->name,
                        'type' => 'status_change',
                        'data' => [
                            'project_id' => $project->id,
                            'project_name' => $project->name,
                            'created_by' => auth()->user()->name
                        ],
                        'is_read' => false
                    ]);
                }

                // Notify team leader
                Notification::create([
                    'user_id' => $this->team_leader_id,
                    'from_id' => auth()->id(),
                    'title' => 'Project Role Assignment',
                    'message' => "You have been assigned as Team Leader for project: {$project->name}",
                    'type' => 'assignment',
                    'data' => [
                        'project_id' => $project->id,
                        'project_name' => $project->name,
                        'role' => 'team_leader',
                        'created_by' => auth()->user()->name
                    ],
                    'is_read' => false
                ]);

                // Notify team members
                foreach ($this->selectedTeamMembers as $memberId) {
                    if ($memberId != $this->supervised_by && $memberId != $this->team_leader_id) {
                        Notification::create([
                            'user_id' => $memberId,
                            'from_id' => auth()->id(),
                            'title' => 'Project Role Assignment', 
                            'message' => "You have been assigned as Team Member for project: {$project->name}",
                            'type' => 'assignment',
                            'data' => [
                                'project_id' => $project->id,
                                'project_name' => $project->name,
                                'role' => 'member',
                                'created_by' => auth()->user()->name
                            ],
                            'is_read' => false
                        ]);
                    }
                }
            }

            session()->flash('notify', [
                'type' => 'success',
                'message' => 'Project created successfully!'
            ]);

            return $this->redirect(route('projects.index'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create project: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.create-project', [
            'departments' => Department::all(),
            'supervisors' => $this->availableSupervisors,
            'availableTeamManagers' => $this->selectedTeamMembers ? 
                User::whereIn('id', $this->selectedTeamMembers)->get() : 
                collect(),
        ]);
    }

    public function updatedSupervisorId()
    {
        // Reset team manager if they're the same person
        if ($this->supervised_by === $this->team_leader_id) {
            $this->supervised_by = null;
        }
    }

    public function updatedTeamLeaderId()
    {
        // Reset project manager if they're the same person
        if ($this->team_leader_id === $this->supervised_by) {
            $this->team_leader_id = null;
        }

        // If team leader is selected, add them to selectedTeamMembers if not already there
        if ($this->team_leader_id && !in_array($this->team_leader_id, $this->selectedTeamMembers)) {
            $this->selectedTeamMembers[] = $this->team_leader_id;
        }
    }
}
