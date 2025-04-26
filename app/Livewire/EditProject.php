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
    public $teamMembers = [];
    public $send_notifications = true;
    public $departmentMembers = [];
    public $availableSupervisors = [];

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
        
        // Load all team members for the team manager dropdown
        $this->teamMembers = $project->members()
            ->select('users.*')
            ->get();

        // Load team manager (project_manager from project_members)
        $teamManager = $project->members()
            ->where('project_members.role', 'project_manager')
            ->first();
        $this->team_manager_id = $teamManager?->id;

        // Load selected team members (both regular members and project manager)
        $this->selectedTeamMembers = $project->members()
            ->pluck('users.id')
            ->toArray();

        // Load department members and supervisors for selection
        if ($this->department_id) {
            $this->loadDepartmentMembers();
            $this->loadSupervisors();
        }
    }
 
    public function updatedSelectedTeamMembers()
    {
        $this->teamMembers = User::whereIn('id', $this->selectedTeamMembers)
            ->select('id', 'first_name', 'last_name')
            ->get();
    }

    public function updatedDepartmentId($value)
    {
        $this->selectedTeamMembers = [];
        // Reset all member-related fields
        $this->reset([
            'team_manager_id',
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
        $this->departmentMembers = User::whereHas('departments', function($query) {
                $query->where('departments.id', $this->department_id);
            })
            ->where('role', 'employee')
            ->get();
    }

    /**
     * Load all supervisors, prioritizing those from the current department
     */
    private function loadSupervisors()
    {
        // Get all supervisors
        $this->availableSupervisors = User::where('role', 'supervisor')
            ->orderByRaw("CASE 
                WHEN department_id = ? THEN 0 
                WHEN id IN (
                    SELECT user_id 
                    FROM user_departments 
                    WHERE department_id = ?
                ) THEN 1 
                ELSE 2 
            END", [$this->department_id, $this->department_id])
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
        $this->validate();

        try {
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

            // Prepare member data with roles
            $memberData = [];

            // Add team manager as project_manager
            if ($this->team_manager_id) {
                $memberData[$this->team_manager_id] = [
                    'role' => 'project_manager',
                    'joined_at' => now()
                ];
            }

            // Add regular team members
            foreach ($this->selectedTeamMembers as $memberId) {
                // Skip if this member is already set as team manager
                if ($memberId != $this->team_manager_id) {
                    $memberData[$memberId] = [
                        'role' => 'member',
                        'joined_at' => now()
                    ];
                }
            }

            // Sync all members with their roles
            $this->project->members()->sync($memberData);

            if ($this->send_notifications) {
                // TODO: Implement notification logic
            }

            session()->flash('success', 'Project updated successfully!');
            return redirect()->route('projects.show', $this->project);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update project: ' . $e->getMessage());
            return null;
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