<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class TaskList extends Component
{
    use WithPagination;

    public $search = '';
    public $projectFilter = '';
    public $priorityFilter = '';
    public $statusFilter = '';
    public $assigneeFilter = '';
    public $sortField = 'due_date';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'projectFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'assigneeFilter' => ['except' => ''],
        'sortField' => ['except' => 'due_date'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = ['taskUpdated' => '$refresh', 'taskCreated' => '$refresh', 'taskDeleted' => '$refresh'];

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
        $params = [
            'component' => 'tasks.task-create',
            'arguments' => []
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
        $tasks = Task::with(['project', 'createdBy'])
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
            ->when($this->assigneeFilter, function (Builder $query) {
                return $query->whereHas('taskAssignments', function (Builder $query) {
                    $query->where('user_id', $this->assigneeFilter);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $projects = Project::all();
        $users = User::all();

        return view('livewire.tasks.task-list', [
            'tasks' => $tasks,
            'projects' => $projects,
            'users' => $users,
        ]);
    }
} 