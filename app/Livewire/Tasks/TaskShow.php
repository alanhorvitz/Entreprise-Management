<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskShow extends Component
{
    public $taskId;
    public $task;
    public $comment = '';
    
    protected $rules = [
        'comment' => 'required|string|max:1000',
    ];
    
    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $this->loadTask();
    }
    
    public function loadTask()
    {
        $this->task = Task::with(['project', 'createdBy', 'taskAssignments.user', 'taskComments.user', 'repetitiveTask'])
            ->findOrFail($this->taskId);
    }
    
    public function addComment()
    {
        $this->validate();
        
        TaskComment::create([
            'task_id' => $this->taskId,
            'user_id' => Auth::id(),
            'comment' => $this->comment,
        ]);
        
        $this->comment = '';
        $this->loadTask();
    }
    
    public function updateStatus($status)
    {
        $task = Task::findOrFail($this->taskId);
        $task->update(['current_status' => $status]);
        
        $this->loadTask();
        $this->dispatch('taskUpdated');
        $this->dispatch('notify', [
            'message' => 'Task status updated successfully!',
            'type' => 'success',
        ]);
    }
    
    public function openEditModal()
    {
        $params = [
            'component' => 'tasks.task-edit',
            'arguments' => [
                'taskId' => $this->taskId
            ]
        ];
        $this->dispatch('openModal', $params);
    }
    
    public function render()
    {
        return view('livewire.tasks.task-show');
    }
} 