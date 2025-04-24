<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectMember;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProjectDetails extends Component
{
    use WithPagination;

    public Project $project;
    public $activeTab = 'overview';
    public $memberToDelete = null;
    public $showDeleteModal = false;
    
    protected $listeners = ['memberAdded' => '$refresh'];

    public function mount(Project $project)
    {
        $this->project = $project->load(['createdBy', 'supervisedBy', 'members', 'tasks']);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function confirmDelete($memberId)
    {
        $this->memberToDelete = $memberId;
        $this->showDeleteModal = true;
    }

    public function deleteMember()
    {
        try {
            ProjectMember::where('project_id', $this->project->id)
                ->where('user_id', $this->memberToDelete)
                ->delete();

            $this->project->refresh();
            $this->showDeleteModal = false;
            $this->memberToDelete = null;
            
            session()->flash('success', 'Member removed successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove member.');
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->memberToDelete = null;
    }

    public function render()
    {
        return view('livewire.projects.project-details', [
            'project' => $this->project,
            'tasks' => $this->project->tasks()->paginate(5),
            'members' => $this->project->members,
        ]);
    }
} 