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
        if (!auth()->user()->hasPermissionTo('edit tasks')) {
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

    public function updatedStartDate($value)
    {
        if ($this->is_repetitive) {
            // For repetitive tasks, keep due_date synchronized with start_date
            $this->due_date = $value;
        }
    }

    public function updatedIsRepetitive($value)
    {
        $this->is_repetitive = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        
        if ($this->is_repetitive) {
            // Reset due date to match start date when switching to repetitive
            $this->due_date = $this->start_date;
            
            // Initialize recurrence settings if empty
            if (empty($this->recurrence_days)) {
                $this->recurrence_days = [now()->dayOfWeek];
            }
            
            // Calculate the next occurrence based on repetition settings
            $startDate = Carbon::parse($this->start_date);
            
            switch ($this->repetition_rate) {
                case 'daily':
                    $this->due_date = $startDate->format('Y-m-d');
                    break;
                    
                case 'weekly':
                    // Find the next occurrence from the selected days
                    $nextDate = $startDate->copy();
                    while (!in_array($nextDate->dayOfWeek, $this->recurrence_days)) {
                        $nextDate->addDay();
                    }
                    $this->due_date = $nextDate->format('Y-m-d');
                    break;
                    
                case 'monthly':
                    $nextDate = $startDate->copy();
                    $day = min((int)$this->recurrence_month_day, $nextDate->daysInMonth);
                    if ($nextDate->day > $day) {
                        $nextDate->addMonth()->setDay($day);
                    } else {
                        $nextDate->setDay($day);
                    }
                    $this->due_date = $nextDate->format('Y-m-d');
                    break;
                    
                case 'yearly':
                    $this->due_date = $startDate->addYear()->format('Y-m-d');
                    break;
            }
        } else {
            // Reset repetitive task related fields when switching to normal task
            $this->repetition_rate = 'weekly';
            $this->recurrence_days = [];
            $this->recurrence_month_day = 1;
            $this->recurrence_end_date = '';
            
            // Keep the current due_date as is for normal tasks
            // The repetitive task record will be deleted in the update method
        }
    }

    public function updatedRepetitionRate($value)
    {
        if (!$this->is_repetitive) {
            return;
        }

        if ($value === 'weekly' && empty($this->recurrence_days)) {
            $this->recurrence_days = [now()->dayOfWeek];
        } elseif ($value === 'monthly' && !$this->recurrence_month_day) {
            $this->recurrence_month_day = now()->day;
        }

        // Recalculate due date based on new repetition rate
        $startDate = Carbon::parse($this->start_date);
        
        switch ($value) {
            case 'daily':
                $this->due_date = $startDate->format('Y-m-d');
                break;
                
            case 'weekly':
                $nextDate = $startDate->copy();
                while (!in_array($nextDate->dayOfWeek, $this->recurrence_days)) {
                    $nextDate->addDay();
                }
                $this->due_date = $nextDate->format('Y-m-d');
                break;
                
            case 'monthly':
                $nextDate = $startDate->copy();
                $day = min((int)$this->recurrence_month_day, $nextDate->daysInMonth);
                if ($nextDate->day > $day) {
                    $nextDate->addMonth()->setDay($day);
                } else {
                    $nextDate->setDay($day);
                }
                $this->due_date = $nextDate->format('Y-m-d');
                break;
                
            case 'yearly':
                $this->due_date = $startDate->addYear()->format('Y-m-d');
                break;
        }
    }

    public function updatedRecurrenceDays($value)
    {
        if (!$this->is_repetitive || $this->repetition_rate !== 'weekly') {
            return;
        }

        // Recalculate due date based on selected days
        $startDate = Carbon::parse($this->start_date);
        $nextDate = $startDate->copy();
        
        while (!in_array($nextDate->dayOfWeek, $this->recurrence_days)) {
            $nextDate->addDay();
        }
        
        $this->due_date = $nextDate->format('Y-m-d');
    }

    public function updatedRecurrenceMonthDay($value)
    {
        if (!$this->is_repetitive || $this->repetition_rate !== 'monthly') {
            return;
        }

        // Recalculate due date based on selected month day
        $startDate = Carbon::parse($this->start_date);
        $nextDate = $startDate->copy();
        $day = min((int)$value, $nextDate->daysInMonth);
        
        if ($nextDate->day > $day) {
            $nextDate->addMonth()->setDay($day);
        } else {
            $nextDate->setDay($day);
        }
        
        $this->due_date = $nextDate->format('Y-m-d');
    }
    
    protected $rules = [
        'title' => 'required|string|max:100',
        'description' => 'nullable|string',
        'project_id' => 'required|exists:projects,id',
        'due_date' => 'nullable|date|after_or_equal:today',
        'priority' => 'nullable|in:low,medium,high',
        'current_status' => 'nullable|in:todo,in_progress,completed',
        'start_date' => 'nullable|date|after_or_equal:today',
        'status' => 'nullable|in:pending_approval,approved',
        'assignees' => 'nullable|array',
        'assignees.*' => 'exists:users,id',
        'is_repetitive' => 'boolean',
        'repetition_rate' => 'required_if:is_repetitive,true|in:daily,weekly,monthly,yearly',
        'recurrence_days' => 'required_if:is_repetitive,true,repetition_rate,weekly|array',
        'recurrence_month_day' => 'required_if:is_repetitive,true,repetition_rate,monthly|integer|min:1|max:31',
        'recurrence_end_date' => 'nullable|date|after_or_equal:due_date',
    ];

    protected $messages = [
        'due_date.after_or_equal' => 'The due date must be today or a future date.',
        'start_date.after_or_equal' => 'The start date must be today or a future date.',
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
        $this->priority = $task->priority;
        $this->current_status = $task->current_status;
        $this->start_date = $task->start_date ? $task->start_date->format('Y-m-d') : null;
        $this->status = $task->status;
        $this->is_repetitive = (bool) $task->is_repetitive;
        
        // Load repetitive task data if exists
        if ($task->repetitiveTask) {
            $this->repetition_rate = $task->repetitiveTask->repetition_rate;
            
            // Handle weekly recurrence days
            if ($this->repetition_rate === 'weekly') {
                $daysBinary = $task->repetitiveTask->recurrence_days;
                $this->recurrence_days = [];
                for ($i = 0; $i < 7; $i++) {
                    if ($daysBinary & (1 << $i)) {
                        $this->recurrence_days[] = $i;
                    }
                }
            }
            
            // Handle monthly recurrence
            if ($this->repetition_rate === 'monthly') {
                $this->recurrence_month_day = $task->repetitiveTask->recurrence_month_day;
            }
            
            $this->recurrence_end_date = $task->repetitiveTask->end_date ? $task->repetitiveTask->end_date->format('Y-m-d') : '';
            
            // Calculate due date based on repetition settings
            $startDate = Carbon::parse($this->start_date);
            
            switch ($this->repetition_rate) {
                case 'daily':
                    $this->due_date = $startDate->format('Y-m-d');
                    break;
                    
                case 'weekly':
                    $nextDate = $startDate->copy();
                    while (!in_array($nextDate->dayOfWeek, $this->recurrence_days)) {
                        $nextDate->addDay();
                    }
                    $this->due_date = $nextDate->format('Y-m-d');
                    break;
                    
                case 'monthly':
                    $nextDate = $startDate->copy();
                    $day = min((int)$this->recurrence_month_day, $nextDate->daysInMonth);
                    if ($nextDate->day > $day) {
                        $nextDate->addMonth()->setDay($day);
                    } else {
                        $nextDate->setDay($day);
                    }
                    $this->due_date = $nextDate->format('Y-m-d');
                    break;
                    
                case 'yearly':
                    $this->due_date = $startDate->addYear()->format('Y-m-d');
                    break;
            }
        } else {
            // Set default values for repetitive task fields
            $this->repetition_rate = 'weekly';
            $this->recurrence_days = [now()->dayOfWeek];
            $this->recurrence_month_day = now()->day;
            $this->recurrence_end_date = '';
            
            // For non-repetitive tasks, use the original due date
            $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : null;
        }
        
        // Load assigned users
        $this->assignees = $task->taskAssignments->pluck('user_id')->toArray();
        
        // Load project members
        $this->loadProjectMembers();
    }
    
    public function update()
    {
        // Check if user has permission to edit tasks
        if (!auth()->user()->hasPermissionTo('edit tasks')) {
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
            
            // Calculate the next occurrence date based on repetition rate
            $startDate = Carbon::parse($this->start_date);
            $nextOccurrence = null;
            
            switch ($this->repetition_rate) {
                case 'daily':
                    $nextOccurrence = $startDate->copy()->addDay();
                    break;
                case 'weekly':
                    $nextOccurrence = $startDate->copy()->addDay();
                    // Find the next day that matches our recurrence pattern
                    while (!($daysBinary & (1 << $nextOccurrence->dayOfWeek))) {
                        $nextOccurrence->addDay();
                    }
                    break;
                case 'monthly':
                    $nextOccurrence = $startDate->copy();
                    // If we haven't passed the recurrence day this month, use it
                    if ($startDate->day < $this->recurrence_month_day) {
                        $day = min((int)$this->recurrence_month_day, $startDate->daysInMonth);
                        $nextOccurrence->setDay($day);
                    } else {
                        // Otherwise, go to next month
                        $nextOccurrence->addMonth();
                        $day = min((int)$this->recurrence_month_day, $nextOccurrence->daysInMonth);
                        $nextOccurrence->setDay($day);
                    }
                    break;
                case 'yearly':
                    $nextOccurrence = $startDate->copy()->addYear();
                    break;
                default:
                    $nextOccurrence = $startDate->copy()->addDay();
            }
            
            // Update or create repetitive task record
            $task->repetitiveTask()->updateOrCreate(
                ['task_id' => $this->taskId],
                [
                    'project_id' => $this->project_id,
                    'created_by' => auth()->id(),
                    'repetition_rate' => $this->repetition_rate,
                    'recurrence_interval' => Carbon::now(),
                    'recurrence_days' => $daysBinary,
                    'recurrence_month_day' => $this->recurrence_month_day,
                    'start_date' => Carbon::parse($this->start_date),
                    'end_date' => $this->recurrence_end_date ? Carbon::parse($this->recurrence_end_date) : null,
                    'next_occurrence' => $nextOccurrence,
                ]
            );
        } else {
            // Delete repetitive task record if exists
            $task->repetitiveTask()->delete();
        }
        
        // Update task assignments
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