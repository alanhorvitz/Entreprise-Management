<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\User;
use App\Models\Department;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
    public $team_manager_id;
    public $budget;
    public $selectedTeamMembers = [];
    public $send_notifications = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'department_id' => 'required|exists:departments,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'status' => 'required|in:planning,in_progress,completed,on_hold',
        'supervised_by' => 'required|exists:users,id',
        'team_manager_id' => 'required|exists:users,id',
        'budget' => 'nullable|numeric|min:0',
        'selectedTeamMembers' => 'required|array|min:1',
        'selectedTeamMembers.*' => 'exists:users,id'
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->department_id = $project->department_id;
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
        $this->status = $project->status;
        $this->budget = $project->budget;
        
        // Load supervisor
        $this->supervised_by = $project->supervised_by;
        
        // Load team manager (project_manager from project_members)
        $teamManager = $project->members()
            ->where('project_members.role', 'project_manager')
            ->first();
        $this->team_manager_id = $teamManager?->id;
        
        // Load regular team members
        $this->selectedTeamMembers = $project->members()
            ->where('project_members.role', 'member')
            ->pluck('users.id')
            ->toArray();
    }

    public function updatedDepartmentId($value)
    {
        // Reset selections when department changes
        $this->selectedTeamMembers = [];
        $this->supervised_by = null;
        $this->team_manager_id = null;
    }

    public function update()
    {
        $this->validate();

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

        // Prepare member data with roles
        $memberData = collect($this->selectedTeamMembers)->mapWithKeys(function ($id) {
            return [$id => [
                'role' => 'member',
                'joined_at' => now()
            ]];
        })->toArray();

        // Add team manager as project_manager in project_members
        if ($this->team_manager_id) {
            $memberData[$this->team_manager_id] = [
                'role' => 'project_manager',
                'joined_at' => now()
            ];
        }

        // Sync all members with their roles
        $this->project->members()->sync($memberData);

        if ($this->send_notifications) {
            // Send notifications to team members
            // Implement notification logic here
        }

        session()->flash('message', 'Project updated successfully.');
        
        return redirect()->route('projects.show', $this->project);
    }

    public function render()
    {
        $departments = Department::all();
        $departmentMembers = [];
        
        if ($this->department_id) {
            $departmentMembers = User::where('department_id', $this->department_id)
                ->where('role', 'supervisor')
                ->get();
        }
        

        return view('livewire.edit-project', [
            'departments' => $departments,
            'departmentMembers' => $departmentMembers,
        ]);
    }
} 