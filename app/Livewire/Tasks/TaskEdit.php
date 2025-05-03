<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

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
    public $projectMembers = [];
    
    // Repetitive task properties
    public $is_repetitive = false;
    public $repetition_rate = 'weekly';
    public $recurrence_days = [];
    public $recurrence_month_day = 1;
    public $recurrence_end_date = '';

    protected function getListeners()
    {
        return [
            'taskUpdated' => '$refresh',
            'echo:private-task.' . $this->taskId . ',TaskUpdated' => '$refresh',
        ];
    }

    public function mount($taskId)
    {
        // Check if user has permission to edit tasks
        if (!auth()->user()->hasRole(['director', 'supervisor'])) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to edit tasks.'
            ]);
            $this->dispatch('closeModal');
            return;
        }

        $this->taskId = $taskId;
        $this->loadTask();
    }

    // Add property type casting
    protected function casts()
    {
        return [
            'is_repetitive' => 'boolean',
        ];
    }

    public function updatedIsRepetitive($value)
    {
        $this->is_repetitive = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        if ($this->is_repetitive && empty($this->recurrence_days)) {
            $this->recurrence_days = [now()->dayOfWeek];
        }
    }

    public function updatedRepetitionRate($value)
    {
        if ($value === 'weekly' && empty($this->recurrence_days)) {
            $this->recurrence_days = [now()->dayOfWeek];
        } elseif ($value === 'monthly' && !$this->recurrence_month_day) {
            $this->recurrence_month_day = now()->day;
        }
    }
    
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
        'is_repetitive' => 'boolean',
        'repetition_rate' => 'required_if:is_repetitive,true|in:daily,weekly,monthly,yearly',
        'recurrence_days' => 'required_if:repetition_rate,weekly|array',
        'recurrence_month_day' => 'required_if:repetition_rate,monthly|integer|min:1|max:31',
        'recurrence_end_date' => 'nullable|date|after_or_equal:due_date',
    ];
    
    public function loadProjectMembers()
    {
        if ($this->project_id) {
            $project = Project::find($this->project_id);
            
            if ($project) {
                $this->projectMembers = $project->members()
                    ->select('users.*', 'project_members.role')
                    ->orderBy('project_members.role', 'desc')
                    ->get();
            }
        } else {
            $this->projectMembers = collect();
        }
    }

    public function updatedProjectId($value)
    {
        $this->loadProjectMembers();
        // Clear assignees when project changes
        $this->assignees = [];
    }
    
    public function loadTask()
    {
        $task = Task::with('repetitiveTask')->findOrFail($this->taskId);
        
        $this->title = $task->title;
        $this->description = $task->description;
        $this->project_id = $task->project_id;
        $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : null;
        $this->priority = $task->priority;
        $this->current_status = $task->current_status;
        $this->start_date = $task->start_date ? $task->start_date->format('Y-m-d') : null;
        $this->status = $task->status;
        $this->is_repetitive = (bool) $task->is_repetitive;
        
        // Load repetitive task data if exists
        if ($task->repetitiveTask) {
            $this->repetition_rate = $task->repetitiveTask->repetition_rate;
            $this->recurrence_end_date = $task->repetitiveTask->end_date ? date('Y-m-d', $task->repetitiveTask->end_date) : '';
        }
        
        // Load assigned users
        $this->assignees = $task->taskAssignments->pluck('user_id')->toArray();
        
        // Load project members
        $this->loadProjectMembers();
    }
    
    public function update()
    {
        // Check if user has permission to edit tasks
        if (!auth()->user()->hasRole(['director', 'supervisor'])) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to edit tasks.'
            ]);
            $this->dispatch('closeModal');
            return;
        }

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
            'is_repetitive' => $this->is_repetitive,
        ]);
        
        // Handle repetitive task data
        if ($this->is_repetitive) {
            // Calculate binary representation of days for weekly recurrence
            $daysBinary = 0;
            if ($this->repetition_rate === 'weekly' && !empty($this->recurrence_days)) {
                foreach ($this->recurrence_days as $day) {
                    $daysBinary |= (1 << $day);
                }
            }
            
            // Update or create repetitive task record
            $task->repetitiveTask()->updateOrCreate(
                ['task_id' => $this->taskId],
                [
                    'project_id' => $this->project_id,
                    'created_by' => auth()->id(),
                    'repetition_rate' => $this->repetition_rate,
                    'recurrence_interval' => now(),
                    'recurrence_days' => $daysBinary,
                    'recurrence_month_day' => $this->recurrence_month_day,
                    'start_date' => Carbon::parse($this->start_date),
                    'end_date' => $this->recurrence_end_date ? Carbon::parse($this->recurrence_end_date) : null,
                    'next_occurrence' => Carbon::parse($this->due_date),
                ]
            );
        } else {
            // Delete repetitive task record if exists
            $task->repetitiveTask()->delete();
        }
        
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
        
        return view('livewire.tasks.task-edit', [
            'projects' => $projects,
            'weekdays' => [
                0 => 'Sunday',
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday'
            ]
        ]);
    }

    public function toggleRepetitive()
    {
        $this->is_repetitive = !$this->is_repetitive;
    }
} 