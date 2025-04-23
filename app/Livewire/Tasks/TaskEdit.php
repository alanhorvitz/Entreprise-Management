<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskEdit extends Component
{
    public $taskId;
    public $title;
    public $description;
    public $project_id;
    public $due_date;
    public $priority;
    public $current_status;
    public $start_date;
    public $status;
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
    
    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $this->loadTask();
    }
    
    public function loadTask()
    {
        $task = Task::findOrFail($this->taskId);
        
        $this->title = $task->title;
        $this->description = $task->description;
        $this->project_id = $task->project_id;
        $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : null;
        $this->priority = $task->priority;
        $this->current_status = $task->current_status;
        $this->start_date = $task->start_date ? $task->start_date->format('Y-m-d') : null;
        $this->status = $task->status;
        
        // Load assigned users
        $this->assignees = $task->taskAssignments->pluck('user_id')->toArray();
    }
    
    public function update()
    {
        $this->validate();
        
        $task = Task::findOrFail($this->taskId);
        
        $task->update([
            'title' => $this->title,
            'description' => $this->description,
            'project_id' => $this->project_id,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'current_status' => $this->current_status,
            'start_date' => $this->start_date,
            'status' => $this->status,
        ]);
        
        // Update task assignments - remove existing and add new ones
        TaskAssignment::where('task_id', $this->taskId)->delete();
        
        if (!empty($this->assignees)) {
            foreach ($this->assignees as $userId) {
                TaskAssignment::create([
                    'task_id' => $this->taskId,
                    'user_id' => $userId,
                    'assigned_by' => Auth::id(),
                ]);
            }
        }
        
        $this->dispatch('taskUpdated');
        $this->dispatch('closeModal');
        $this->dispatch('notify', [
            'message' => 'Task updated successfully!',
            'type' => 'success',
        ]);
    }
    
    public function render()
    {
        $projects = Project::all();
        $users = User::all();
        
        return view('livewire.tasks.task-edit', [
            'projects' => $projects,
            'users' => $users,
        ]);
    }
} 