<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Mail\TaskCompletedMail;
use Illuminate\Support\Facades\Mail;

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
            'text' => $this->comment,
        ]);
        
        $this->comment = '';
        $this->loadTask();
    }
    
    public function deleteComment($commentId)
    {
        $comment = TaskComment::findOrFail($commentId);
        
        // Only allow deletion if the user is the comment author or has special permissions
        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            $this->loadTask();
            
            $this->dispatch('notify', [
                'message' => 'Comment deleted successfully!',
                'type' => 'success',
            ]);
        }
    }
    
    public function updateStatus($status)
    {
        $task = Task::findOrFail($this->taskId);
        $task->update(['current_status' => $status]);
        
        // Send email when task is marked as completed
        if ($status === 'completed') {
            Mail::to('kniptodati@gmail.com')->send(new TaskCompletedMail($task));
        }
        
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