<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Chat;
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
    public $showProjectDeleteModal = false;
    public $canModifyStatus = false;
    
    protected $listeners = [
        'memberAdded' => '$refresh',
        'taskUpdated' => '$refresh',
        'statusUpdated' => '$refresh'
    ];

    public function mount(Project $project)
    {
        // Check if user has permission to view this project
        if (!auth()->user()->hasPermissionTo('view all projects') && 
            !$project->members()->where('user_id', auth()->id())->exists() &&
            $project->supervised_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->project = $project->load(['createdBy', 'supervisedBy', 'members', 'tasks']);
        $this->canModifyStatus = auth()->user()->hasPermissionTo('update project status');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function confirmDelete($memberId)
    {
        if (!auth()->user()->hasPermissionTo('edit projects')) {
            abort(403, 'Unauthorized action.');
        }

        $this->memberToDelete = $memberId;
        $this->showDeleteModal = true;
    }

    public function confirmDeleteProject()
    {
        if (!auth()->user()->hasPermissionTo('delete projects')) {
            abort(403, 'Unauthorized action.');
        }

        $this->showProjectDeleteModal = true;
    }

    public function deleteMember()
    {
        if (!auth()->user()->hasPermissionTo('edit projects')) {
            abort(403, 'Unauthorized action.');
        }

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

    public function deleteProject()
    {
        if (!auth()->user()->hasPermissionTo('delete projects')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete project members
            ProjectMember::where('project_id', $this->project->id)->delete();

            // Delete associated chat data
            // Chat::where('project_id', $this->project->id)->delete();

            // Delete the project
            $this->project->delete();

            $this->showProjectDeleteModal = false;

            session()->flash('success', 'Project deleted successfully.');

            return redirect()->route('projects.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete project.');
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->memberToDelete = null;
    }

    public function closeProjectDeleteModal()
    {
        $this->showProjectDeleteModal = false;
    }

    public function openViewModal($taskId)
    {
        $params = [
            'component' => 'tasks.task-show',
            'arguments' => [
                'taskId' => $taskId
            ]
        ];
        $this->dispatch('openModal', $params);
    }

    public function openCreateModal()
    {
        if (!auth()->user()->hasPermissionTo('create tasks')) {
            abort(403, 'Unauthorized action.');
        }

        $params = [
            'component' => 'tasks.task-create',
            'arguments' => [
                'project_id' => $this->project->id
            ]
        ];
        $this->dispatch('openModal', $params);
    }

    public function updateStatus($status)
    {
        if (!$this->canModifyStatus) {
            return;
        }

        if (!in_array($status, ['planning', 'in_progress', 'on_hold', 'completed'])) {
            return;
        }

        $this->project->update([
            'status' => $status
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Project status updated successfully!'
        ]);

        $this->dispatch('statusUpdated');
    }

    public function render()
    {
        $user = auth()->user();
        $isDirectorOrSupervisor = $user->hasPermissionTo('view all projects') || 
                                 $this->project->supervised_by === $user->id;

        // Get tasks query
        $tasksQuery = $this->project->tasks();
        
        // If not director/supervisor, only show assigned tasks
        if (!$isDirectorOrSupervisor) {
            $tasksQuery->whereHas('taskAssignments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return view('livewire.projects.project-details', [
            'project' => $this->project,
            'tasks' => $tasksQuery->paginate(5),
            'members' => $this->project->members,
            'canEdit' => $user->hasPermissionTo('edit projects'),
            'canDelete' => $user->hasPermissionTo('delete projects'),
            'canCreateTasks' => $user->hasPermissionTo('create tasks'),
            'canManageMembers' => $user->hasPermissionTo('edit projects'),
        ]);
    }
} 