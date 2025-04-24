<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProjectDetails extends Component
{
    use WithPagination;

    public Project $project;
    public $activeTab = 'overview';
    
    protected $listeners = ['memberAdded' => '$refresh'];

    public function mount(Project $project)
    {
        $this->project = $project->load(['createdBy', 'supervisedBy', 'members', 'tasks']);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
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