<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use App\Models\ProjectMember;
use App\Models\UserDepartment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateProject extends Component
{
    public $name;
    public $description;
    public $departmentId;
    public $start_date;
    public $end_date;
    public $status;
    public $budget;
    public $team_leader_id;
    public $supervisor_id;
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
            'departmentId' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planning,in_progress,completed,on_hold',
            'budget' => 'required|numeric|min:0',
            'team_leader_id' => 'required|exists:users,id',
            'team_leader_id' => 'required|exists:users,id',
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
        $this->reset(['team_leader_id', 'team_leader_id', 'selectedTeamMembers']);
    }

    public function updatedSelectedTeamMembers($value)
    {
        // Update available team managers based on selected team members
        if (!empty($this->selectedTeamMembers)) {
            $this->availableTeamManagers = User::whereIn('id', $this->selectedTeamMembers)
                ->get(['id', 'first_name', 'last_name']);
        } 
        else {
            $this->availableTeamManagers = collect();
        }
    }

    public function loadDepartmentMembers()
    {
        if ($this->departmentId) {
            // Get all employees assigned to this department through user_departments table
            $this->departmentMembers = User::whereHas('userDepartments', function($query) {
                    $query->where('department_id', $this->departmentId);
                })
                ->where('role', 'employee')
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
        $query = User::where('role', 'supervisor');
        
        // If a department is selected, order by relevance
        if ($this->departmentId) {
            $query->orderByRaw("CASE 
                WHEN department_id = ? THEN 0 
                WHEN id IN (
                    SELECT user_id 
                    FROM user_departments 
                    WHERE department_id = ?
                ) THEN 1 
                ELSE 2 
            END", [$this->departmentId, $this->departmentId]);
        }

        $this->availableSupervisors = $query->get();
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
                'department_id' => $this->departmentId,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'status' => $this->status,
                'budget' => $this->budget,
                'supervised_by' => $this->supervisor_id,
                'created_by' => Auth::id(),
                'is_featured' => $this->is_featured
            ]);

            // Assign team_leader role to the chosen team leader
            $teamLeader = User::find($this->team_leader_id);
            if ($teamLeader) {
                $teamLeader->assignRole('team_leader');
            }

            // Attach project manager
            ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $this->team_leader_id,
                'role' => 'team_leader',
                'joined_at' => now(),
            ]);

            // Attach team members
            if (!empty($this->selectedTeamMembers)) {
                foreach ($this->selectedTeamMembers as $memberId) {
                    // Skip if member is project manager or team manager
                    if ($memberId != $this->supervisor_id && $memberId != $this->team_leader_id) {
                        ProjectMember::create([
                            'project_id' => $project->id,
                            'user_id' => $memberId,
                            'role' => 'member',
                            'joined_at' => now(),
                        ]);
                    }
                }
            }

            if ($this->send_notifications) {
                // TODO: Implement notification logic
            }

            session()->flash('success', 'Project created successfully!');
            return redirect()->route('projects.index');

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
        if ($this->supervisor_id === $this->team_leader_id) {
            $this->supervisor_id = null;
        }
    }

    public function updatedTeamLeaderId()
    {
        // Reset project manager if they're the same person
        if ($this->team_leader_id === $this->supervisor_id) {
            $this->team_leader_id = null;
        }
    }
}
