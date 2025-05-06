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
    
    public function updateApprovalStatus($status)
    {
        if (!auth()->user()->hasRole(['director', 'supervisor'])) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to change the approval status.'
            ]);
            return;
        }

        if ($this->task->current_status !== 'completed') {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Only completed tasks can have their approval status changed.'
            ]);
            return;
        }

        try {
            if ($status === 'in_progress') {
                // If returning to progress, update both status and current_status
                $this->task->current_status = 'in_progress';
                $this->task->status = 'pending_approval';
                $this->task->save();
                
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Task has been returned to progress.'
                ]);
            } else {
                // For approval status changes
                $this->task->status = $status;
                $this->task->save();
                
                $message = $status === 'approved' ? 'Task has been approved.' : 'Task is pending approval.';
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => $message
                ]);
            }

            // Refresh the task data
            $this->task->refresh();
            
            // Emit event for task update
            $this->dispatch('taskUpdated');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error updating task status: ' . $e->getMessage()
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.tasks.task-show');
    }
} 