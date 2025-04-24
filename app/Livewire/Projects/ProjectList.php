<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectMember;
// use App\Models\Chat;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProjectList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $projectToDelete = null;
    public $showDeleteModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($projectId)
    {
        $this->projectToDelete = $projectId;
        $this->showDeleteModal = true;
    }

    public function deleteProject()
    {
        try {
            $project = Project::findOrFail($this->projectToDelete);

            // Delete project members
            ProjectMember::where('project_id', $project->id)->delete();

            // Delete associated chat data
            // Chat::where('project_id', $project->id)->delete();

            // Delete the project
            $project->delete();

            $this->showDeleteModal = false;
            $this->projectToDelete = null;

            session()->flash('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete project.');
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->projectToDelete = null;
    }

    public function render()
    {
        $projects = Project::query()
            ->with(['createdBy', 'supervisedBy', 'members'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.projects.project-list', [
            'projects' => $projects
        ]);
    }
} 