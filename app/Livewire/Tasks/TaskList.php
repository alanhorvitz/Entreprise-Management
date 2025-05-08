<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;

class TaskList extends Component
{
    use WithPagination;

    public $search = '';
    public $projectFilter = '';
    public $priorityFilter = '';
    public $statusFilter = '';
    public $assigneeFilter = '';
    public $repetitiveFilter = '';
    public $sortField = 'due_date';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'projectFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'assigneeFilter' => ['except' => ''],
        'repetitiveFilter' => ['except' => ''],
        'sortField' => ['except' => 'due_date'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = ['taskUpdated' => '$refresh', 'taskCreated' => '$refresh', 'taskDeleted' => '$refresh'];

    public function mount()
    {
        // Check if there's a task ID in the query string to open
        $taskId = request()->query('open_task');
        $editTaskId = request()->query('edit_task');
        $newTask = request()->query('new_task');
        $dueDate = request()->query('due_date');
        
        if ($newTask) {
            // Dispatch an event to open the create modal after the component is rendered
            $this->dispatch('defer-load-task-create', ['due_date' => $dueDate]);
        } elseif ($editTaskId) {
            // Check if the task exists for editing
            $task = Task::find($editTaskId);
            if ($task) {
                // Dispatch an event to open the edit modal after the component is rendered
                $this->dispatch('defer-load-task-edit', $editTaskId);
            }
        } elseif ($taskId) {
            // Check if the task exists for viewing
            $task = Task::find($taskId);
            if ($task) {
                // Dispatch an event to open the view modal after the component is rendered
                $this->dispatch('defer-load-task', $taskId);
            }
        }
    }

    // This function will be called via JavaScript once the page is loaded
    public function openTaskFromQuery($taskId)
    {
        // Directly call the view modal open method
        $this->openViewModal($taskId);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProjectFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingAssigneeFilter()
    {
        $this->resetPage();
    }
    
    public function updatingRepetitiveFilter()
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

    public function clearFilters()
    {
        $this->search = '';
        $this->projectFilter = '';
        $this->priorityFilter = '';
        $this->statusFilter = '';
        $this->assigneeFilter = '';
        $this->repetitiveFilter = '';
        $this->resetPage();
    }

    public function deleteTask($taskId)
    {
        $params = [
            'component' => 'tasks.task-delete',
            'arguments' => [
                'taskId' => $taskId
            ]
        ];
        $this->dispatch('openModal', $params);
    }

    public function openCreateModal()
    {
        if (!auth()->user()->hasPermissionTo('create tasks')) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to create tasks.'
            ]);
            return;
        }

        $dueDate = request()->query('due_date');
        
        $params = [
            'component' => 'tasks.task-create',
            'arguments' => [
                'due_date' => $dueDate
            ]
        ];
        $this->dispatch('openModal', $params);
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

    public function openEditModal($taskId)
    {
        $params = [
            'component' => 'tasks.task-edit',
            'arguments' => [
                'taskId' => $taskId
            ]
        ];
        $this->dispatch('openModal', $params);
    }

    public function render()
    {
        $user = auth()->user();
        
        $tasks = Task::with(['project', 'createdBy', 'repetitiveTask', 'taskAssignments'])
            ->when($this->search, function (Builder $query) {
                return $query->where(function (Builder $query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->projectFilter, function (Builder $query) {
                return $query->where('project_id', $this->projectFilter);
            })
            ->when($this->priorityFilter, function (Builder $query) {
                return $query->where('priority', $this->priorityFilter);
            })
            ->when($this->statusFilter, function (Builder $query) {
                return $query->where('current_status', $this->statusFilter);
            })
            ->when($this->assigneeFilter, function (Builder $query) use ($user) {
                return $query->whereHas('taskAssignments', function (Builder $query) use ($user) {
                    $query->whereHas('employee', function (Builder $subQuery) use ($user) {
                        $subQuery->where('user_id', $user->id);
                    });
                });
            })
            ->when($this->repetitiveFilter !== '', function (Builder $query) {
                if ($this->repetitiveFilter === 'yes') {
                    return $query->whereHas('repetitiveTask');
                } else {
                    return $query->whereDoesntHave('repetitiveTask');
                }
            });

        // Apply role-based visibility rules
        if ($user->hasRole('director')) {
            // Directors can see all tasks - no additional filtering needed
        } elseif ($user->hasRole('supervisor')) {
            // Supervisors can see all tasks in projects they supervise
            $tasks->whereHas('project', function($query) use ($user) {
                $query->where('supervised_by', $user->id);
            });
        } else {
            // Both team leaders and regular employees can only see tasks assigned to them in their projects
            $tasks->whereHas('project', function($query) use ($user) {
                $query->whereHas('members', function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id);
                });
            })->whereHas('taskAssignments', function($query) use ($user) {
                $query->whereHas('employee', function($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id);
                });
            });
        }

        $tasks = $tasks->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Get projects based on role
        if ($user->hasRole('director')) {
            $projects = Project::all();
        } elseif ($user->hasRole('supervisor')) {
            $projects = Project::where('supervised_by', $user->id)->get();
        } else {
            // Both team leaders and regular employees see their assigned projects
            $projects = Project::whereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }

        // Get users based on role and project visibility
        if ($user->hasRole('director')) {
            $users = User::all();
        } elseif ($user->hasRole('supervisor')) {
            // Get users from supervised projects
            $users = User::whereHas('employee', function($query) use ($projects) {
                $query->whereHas('projects', function($subQuery) use ($projects) {
                    $subQuery->whereIn('projects.id', $projects->pluck('id'));
                });
            })->get();
        } else {
            // Both team leaders and regular employees only see themselves
            $users = User::where('id', $user->id)->get();
        }

        return view('livewire.tasks.task-list', [
            'tasks' => $tasks,
            'projects' => $projects,
            'users' => $users,
        ]);
    }

    public function updateTaskStatus($taskId, $status)
    {
        $task = Task::findOrFail($taskId);
        $oldStatus = $task->current_status;

        $task->current_status = $status;
        $task->save();

        // Notify all assigned members about the status change
        foreach ($task->taskAssignments as $assignment) {
            $employee = $assignment->employee;
            if ($employee && $employee->user_id !== auth()->id()) { // Don't notify the supervisor making the change
                Notification::create([
                    'user_id' => $employee->user_id,
                    'from_id' => auth()->id(),
                    'title' => 'Task Status Updated',
                    'message' => "Task '{$task->title}' has been " . 
                        ($status === 'approved' ? 'approved' : 
                        ($status === 'in_progress' ? 'returned to progress' : 'marked as pending approval')),
                    'type' => 'status_change',
                    'data' => [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'project_id' => $task->project_id,
                        'old_status' => $oldStatus,
                        'new_status' => $status,
                        'updated_by' => auth()->user()->name
                    ],
                    'is_read' => false
                ]);
            }
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Task status updated successfully.'
        ]);
    }
} 