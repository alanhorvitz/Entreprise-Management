<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use App\Models\ProjectMember;
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
    public $project_manager_id;
    public $team_manager_id;
    public $selectedTeamMembers = [];
    public $send_notifications = false;
    public $is_featured = false;

    public $departmentMembers = [];
    public $supervisors = [];
    public $availableTeamManagers = [];

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
            'project_manager_id' => 'required|exists:users,id',
            'team_manager_id' => 'required|exists:users,id',
            'selectedTeamMembers' => 'required|array|min:1',
            'selectedTeamMembers.*' => 'exists:users,id'
        ];
    }

    protected $listeners = ['departmentSelected' => 'loadDepartmentMembers'];

    public function mount()
    {
        $this->status = 'planning';
        $this->supervisors = User::where('role', 'supervisor')->get();
        $this->selectedTeamMembers = [];
        $this->availableTeamManagers = collect();
    }
    
    public function updatedDepartmentId($value)
    {
        $this->loadDepartmentMembers();
        $this->reset(['project_manager_id', 'team_manager_id', 'selectedTeamMembers']);
    }

    public function updatedSelectedTeamMembers($value)
    {
        // Reset team manager when team members change
        // $this->team_manager_id = null;
        
        
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
            // Get all employees in this department
            $this->departmentMembers = User::where('department_id', $this->departmentId)
                ->where('role', 'employee')
                ->get();
            // Reset team members and manager when department changes
            $this->selectedTeamMembers = [];
            $this->team_manager_id = null;
            $this->availableTeamManagers = collect();
        } else {
            $this->departmentMembers = [];
            $this->selectedTeamMembers = [];
            $this->team_manager_id = null;
            $this->availableTeamManagers = collect();
        }
    }

    public function create()
    {
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
                'supervised_by' => $this->project_manager_id,
                'created_by' => Auth::id(),
                'is_featured' => $this->is_featured
            ]);

            // Attach project manager
            ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $this->team_manager_id,
                'role' => 'project_manager',
                'joined_at' => now(),
            ]);

            // Attach team members
            if (!empty($this->selectedTeamMembers)) {
                foreach ($this->selectedTeamMembers as $memberId) {
                    // Skip if member is project manager or team manager
                    if ($memberId != $this->project_manager_id && $memberId != $this->team_manager_id) {
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
            session()->flash('error', 'Failed to create project. ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.create-project', [
            'departments' => Department::all(),
            'availableTeamManagers' => $this->selectedTeamMembers ? 
                User::whereIn('id', $this->selectedTeamMembers)->get() : 
                collect(),
        ]);
    }

    public function updatedProjectManagerId()
    {
        // Reset team manager if they're the same person
        if ($this->project_manager_id === $this->team_manager_id) {
            $this->team_manager_id = null;
        }
    }

    public function updatedTeamManagerId()
    {
        // Reset project manager if they're the same person
        if ($this->team_manager_id === $this->project_manager_id) {
            $this->project_manager_id = null;
        }
    }
}
