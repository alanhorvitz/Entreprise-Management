<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Mail\ProjectStatusChangedMail;
use Illuminate\Support\Facades\Mail;

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
        if (!auth()->user()->hasPermissionTo('view all projects')) {
            // Get the employee record for the current user
            $employee = \App\Models\Employee::where('user_id', auth()->id())->first();
            
            // Check if user is a member or supervisor
            if (!$employee || 
                (!$project->members()->where('employee_id', $employee->id)->exists() && 
                $project->supervised_by !== auth()->id())) {
                abort(403, 'Unauthorized action.');
            }
        }

        $this->project = $project->load(['createdBy', 'supervisedBy', 'members', 'tasks']);
        
        // Only allow status modification for directors and project supervisor
        $user = auth()->user();
        $this->canModifyStatus = $user->hasRole('director') || $project->supervised_by === $user->id;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function confirmDelete($memberId)
    {
        $user = auth()->user();
        if (!($user->hasRole('director') || $this->project->supervised_by === $user->id)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to manage team members.'
            ]);
            return;
        }

        $this->memberToDelete = $memberId;
        $this->showDeleteModal = true;
    }

    public function confirmDeleteProject()
    {
        if (!auth()->user()->hasRole('director')) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Only directors can delete projects.'
            ]);
            return;
        }

        $this->showProjectDeleteModal = true;
    }

    public function deleteMember()
    {
        $user = auth()->user();
        if (!($user->hasRole('director') || $this->project->supervised_by === $user->id)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to manage team members.'
            ]);
            return;
        }

        try {
            // Get the project member record
            $projectMember = ProjectMember::where('project_id', $this->project->id)
                ->where('employee_id', $this->memberToDelete)
                ->first();

            if (!$projectMember) {
                throw new \Exception('Project member not found.');
            }

            // Get the user ID for notification
            $user = $projectMember->employee->user;

            // Delete the project member
            $projectMember->delete();

            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'from_id' => auth()->id(),
                'title' => 'Removed from Project',
                'message' => 'You have been removed from project: ' . $this->project->name,
                'type' => 'assignment',
                'data' => [
                    'project_id' => $this->project->id,
                    'project_name' => $this->project->name
                ],
                'is_read' => false
            ]);

            $this->project->refresh();
            $this->showDeleteModal = false;
            $this->memberToDelete = null;
            
            session()->flash('notify', [
                'type' => 'success',
                'message' => 'Member removed successfully.'
            ]);
        } catch (\Exception $e) {
            session()->flash('notify', [
                'type' => 'error',
                'message' => 'Failed to remove member: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteProject()
    {
        if (!auth()->user()->hasRole('director')) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Only directors can delete projects.'
            ]);
            return;
        }

        try {
            // Detach all members using the relationship
            $this->project->members()->detach();

            // Delete the project
            $this->project->delete();

            $this->showProjectDeleteModal = false;

            session()->flash('notify', [
                'type' => 'success',
                'message' => 'Project deleted successfully.'
            ]);

            return redirect()->route('projects.index');
        } catch (\Exception $e) {
            session()->flash('notify', [
                'type' => 'error',
                'message' => 'Failed to delete project: ' . $e->getMessage()
            ]);
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
        $user = auth()->user();
        if (!($user->hasRole('director') || $this->project->supervised_by === $user->id)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Only directors and project supervisors can create tasks.'
            ]);
            return;
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
        // Check if user has permission to modify status
        $user = auth()->user();
        if (!($user->hasRole('director') || $this->project->supervised_by === $user->id)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to update the project status.'
            ]);
            return;
        }

        if (!in_array($status, ['planning', 'in_progress', 'on_hold', 'completed'])) {
            return;
        }

        try {
            $oldStatus = $this->project->status;
            
            $this->project->update([
                'status' => $status
            ]);

            // Get all directors except current user
            $directors = User::role('director')
                ->where('id', '!=', auth()->id())
                ->get();
            
            // Send notification to each director
            foreach ($directors as $director) {
                Notification::create([
                    'user_id' => $director->id,
                    'from_id' => auth()->id(),
                    'title' => 'Project Status Updated',
                    'message' => 'Project status has been updated to ' . $status . ' for project: ' . $this->project->name,
                    'type' => 'status_change',
                    'data' => [
                        'project_id' => $this->project->id,
                        'project_name' => $this->project->name,
                        'new_status' => $status,
                        'updated_by' => auth()->user()->name
                    ],
                    'is_read' => false
                ]);
            }

            // Load the project with its relationships
            $this->project->load([
                'members' => function($query) {
                    $query->where('role', 'team_leader');
                },
                'members.user'  // Load the user relationship directly from ProjectMember
            ]);


                // Send email notification to team leader
                Mail::to('kniptodati@gmail.com')->send(new ProjectStatusChangedMail(
                    $this->project,
                    $oldStatus,
                    $status
                ));
            

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Project status updated successfully!'
            ]);

            $this->dispatch('statusUpdated');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to update project status: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $user = auth()->user();
        $isDirectorOrSupervisor = $user->hasRole('director') || $this->project->supervised_by === $user->id;

        // Get tasks query
        $tasksQuery = $this->project->tasks();
        
        // If not director/supervisor, only show assigned tasks
        if (!$isDirectorOrSupervisor) {
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $tasksQuery->whereHas('taskAssignments', function ($query) use ($employee) {
                    $query->where('employee_id', $employee->id);
                });
            }
        }

        return view('livewire.projects.project-details', [
            'project' => $this->project,
            'tasks' => $tasksQuery->paginate(5),
            'members' => $this->project->members,
            'canEdit' => $isDirectorOrSupervisor,
            'canDelete' => $user->hasRole('director'),
            'canCreateTasks' => $isDirectorOrSupervisor,
            'canManageMembers' => $isDirectorOrSupervisor,
        ]);
    }
} 