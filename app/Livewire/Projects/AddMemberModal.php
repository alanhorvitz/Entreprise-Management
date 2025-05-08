<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class AddMemberModal extends Component
{
    public $show = false;
    public $project;
    public $selectedMembers = [];
    public $availableMembers = [];

    protected $listeners = ['openAddMemberModal' => 'open'];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->loadAvailableMembers();
    }

    public function loadAvailableMembers()
    {
        // Get all employees in the department, excluding supervisors
        $departmentEmployees = \App\Models\Employee::whereHas('departments', function($query) {
                $query->where('department_id', $this->project->department_id);
            })
            ->whereHas('user', function($query) {
                $query->whereHas('roles', function($query) {
                    $query->where('name', 'employee');
                });
                $query->whereDoesntHave('roles', function($query) {
                    $query->where('name', 'supervisor');
                });
            })
            ->whereNotIn('id', function($query) {
                $query->select('employee_id')
                    ->from('project_members')
                    ->where('project_id', $this->project->id);
            })
            ->with('user')
            ->get();

        $this->availableMembers = $departmentEmployees->map(function($employee) {
            return $employee->user;
        });
    }

    public function open()
    {
        $this->loadAvailableMembers();
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->reset('selectedMembers');
    }

    public function addMembers()
    {
        $this->validate([
            'selectedMembers' => 'required|array|min:1',
        ]);

        foreach ($this->selectedMembers as $userId) {
            // Get the employee record for this user
            $employee = \App\Models\Employee::where('user_id', $userId)->first();
            
            if ($employee) {
                ProjectMember::create([
                    'project_id' => $this->project->id,
                    'employee_id' => $employee->id,
                    'role' => 'member',
                    'joined_at' => now(),
                ]);
                
                // Create notification for the added member
                Notification::create([
                    'user_id' => $userId,
                    'from_id' => auth()->id(),
                    'title' => 'Added to Project',
                    'message' => 'You have been added to project: ' . $this->project->name,
                    'type' => 'assignment',
                    'data' => [
                        'project_id' => $this->project->id,
                        'project_name' => $this->project->name
                    ],
                    'is_read' => false
                ]);
            }
        }

        $this->dispatch('memberAdded');
        $this->close();
    }

    public function render()
    {
        return view('livewire.projects.add-member-modal');
    }
}
