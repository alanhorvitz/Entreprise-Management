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
        $this->availableMembers = User::whereHas('userDepartments', function($query) {
                $query->where('department_id', $this->project->department_id);
            })
            ->whereNotIn('id', function($query) {
                $query->select('user_id')
                    ->from('project_members')
                    ->where('project_id', $this->project->id);
            })
            ->where('is_active', true)
            ->where('role', 'employee')
            ->orderByRaw("CASE 
                WHEN department_id = ? THEN 0
                ELSE 1 
            END", [$this->project->department_id])
            ->get();
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

        try {
            DB::beginTransaction();

            foreach ($this->selectedMembers as $memberId) {
                // Add member to project
                ProjectMember::create([
                    'project_id' => $this->project->id,
                    'user_id' => $memberId,
                    'role' => 'member',
                    'joined_at' => now(),
                ]);

                // Create notification for the added member
                Notification::create([
                    'user_id' => $memberId,
                    'title' => 'New Project Assignment',
                    'message' => "You have been added to the project '{$this->project->name}' by " . auth()->user()->name,
                    'from_id' => auth()->id(),
                    'type' => 'assignment',
                    'is_read' => false,
                ]);
            }

            DB::commit();
            $this->dispatch('memberAdded');
            $this->close();
            session()->flash('success', 'Members added successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to add members. ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.projects.add-member-modal');
    }
}
