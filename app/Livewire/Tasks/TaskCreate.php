<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskCreate extends Component
{
    public $title = '';
    public $description = '';
    public $project_id = '';
    public $due_date = '';
    public $priority = 'medium';
    public $current_status = 'todo';
    public $start_date = '';
    public $status = 'pending_approval';
    public $assignees = [];
    
    protected $rules = [
        'title' => 'required|string|max:100',
        'description' => 'nullable|string',
        'project_id' => 'required|exists:projects,id',
        'due_date' => 'nullable|date',
        'priority' => 'nullable|in:low,medium,high',
        'current_status' => 'nullable|in:todo,in_progress,completed',
        'start_date' => 'nullable|date',
        'status' => 'nullable|in:pending_approval,approved',
        'assignees' => 'nullable|array',
        'assignees.*' => 'exists:users,id',
    ];
    
    public function save()
    {
        $this->validate();
        
        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'project_id' => $this->project_id,
            'created_by' => Auth::id(),
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'current_status' => $this->current_status,
            'start_date' => $this->start_date,
            'status' => $this->status,
        ]);
        
        // Assign the task to selected users
        if (!empty($this->assignees)) {
            foreach ($this->assignees as $userId) {
                TaskAssignment::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'assigned_by' => Auth::id(),
                ]);
            }
        }
        
        $this->resetForm();
        $this->dispatch('taskCreated');
        $this->dispatch('closeModal');
        $this->dispatch('notify', [
            'message' => 'Task created successfully!',
            'type' => 'success',
        ]);
    }
    
    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->project_id = '';
        $this->due_date = '';
        $this->priority = 'medium';
        $this->current_status = 'todo';
        $this->start_date = '';
        $this->status = 'pending_approval';
        $this->assignees = [];
    }
    
    public function render()
    {
        $projects = Project::all();
        $users = User::all();
        
        return view('livewire.tasks.task-create', [
            'projects' => $projects,
            'users' => $users,
        ]);
    }
} 