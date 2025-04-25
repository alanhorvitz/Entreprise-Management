<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\RepetitiveTask;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Carbon\Carbon;
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
    
    // New repetitive task fields
    public $is_repetitive = false;
    public $repetition_rate = 'weekly';
    public $recurrence_days = []; // Will hold day numbers (0-6 for Sunday-Saturday)
    public $recurrence_month_day = 1; // Day of month for monthly repetition
    public $recurrence_end_date = ''; // Optional end date for repetition
    
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
    
    public function mount($due_date = null)
    {
        if ($due_date) {
            $this->due_date = $due_date;
            
            // Also set start date to the same day by default
            $this->start_date = $due_date;
            
            // If it's a specific day of the week, preselect that day for weekly recurrence
            $dayOfWeek = Carbon::parse($due_date)->dayOfWeek;
            $this->recurrence_days = [$dayOfWeek];
            
            // Set recurrence_month_day to the day of month in the due date
            $this->recurrence_month_day = Carbon::parse($due_date)->day;
        } else {
            // Default to today for both dates if none provided
            $today = Carbon::today()->format('Y-m-d');
            $this->start_date = $today;
            $this->due_date = $today;
            
            // Default to current day of week for recurrence
            $dayOfWeek = Carbon::today()->dayOfWeek;
            $this->recurrence_days = [$dayOfWeek];
            
            // Default to current day of month for monthly recurrence
            $this->recurrence_month_day = Carbon::today()->day;
        }
    }
    
    public function save()
    {
        $this->validate();
        
        // Create the task
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
            'is_repetitive' => $this->is_repetitive,
        ]);
        
        // If task is repetitive, create the repetitive task record
        if ($this->is_repetitive) {
            // Calculate next occurrence based on repetition rate
            $nextOccurrence = $this->calculateNextOccurrence();
            
            // Create binary representation of days for weekly recurrence
            $daysBinary = 0;
            if ($this->repetition_rate === 'weekly' && !empty($this->recurrence_days)) {
                foreach ($this->recurrence_days as $day) {
                    $daysBinary |= (1 << $day); // Set bit for each selected day
                }
            }
            
            // Create the repetitive task record
            RepetitiveTask::create([
                'task_id' => $task->id,
                'project_id' => $this->project_id,
                'created_by' => Auth::id(),
                'repetition_rate' => $this->repetition_rate,
                'recurrence_interval' => Carbon::now(), // Store current time as base interval
                'recurrence_days' => $daysBinary, // Store days as binary for weekly recurrence
                'recurrence_month_day' => $this->recurrence_month_day,
                'start_date' => strtotime($this->start_date),
                'end_date' => $this->recurrence_end_date ? strtotime($this->recurrence_end_date) : 0,
                'next_occurrence' => $nextOccurrence,
            ]);
        }
        
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
    
    private function calculateNextOccurrence()
    {
        $dueDate = Carbon::parse($this->due_date);
        
        switch ($this->repetition_rate) {
            case 'daily':
                return strtotime($dueDate->addDay()->format('Y-m-d'));
                
            case 'weekly':
                if (empty($this->recurrence_days)) {
                    // If no days selected, default to same day next week
                    return strtotime($dueDate->addWeek()->format('Y-m-d'));
                }
                
                // Find the next occurrence based on selected days
                $nextDate = clone $dueDate;
                $nextDate->addDay(); // Start from the day after due date
                
                // Loop until we find the next day that matches our recurrence pattern
                for ($i = 0; $i < 7; $i++) {
                    if (in_array($nextDate->dayOfWeek, $this->recurrence_days)) {
                        return strtotime($nextDate->format('Y-m-d'));
                    }
                    $nextDate->addDay();
                }
                return strtotime($dueDate->addWeek()->format('Y-m-d')); // Fallback
                
            case 'monthly':
                // Get the specified day in the next month
                $nextMonth = $dueDate->copy()->addMonth();
                $day = min($this->recurrence_month_day, $nextMonth->daysInMonth);
                $nextMonth->day($day);
                return strtotime($nextMonth->format('Y-m-d'));
                
            case 'yearly':
                return strtotime($dueDate->addYear()->format('Y-m-d'));
                
            default:
                return strtotime($dueDate->format('Y-m-d'));
        }
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
        $this->is_repetitive = false;
        $this->repetition_rate = 'weekly';
        $this->recurrence_days = [];
        $this->recurrence_month_day = 1;
        $this->recurrence_end_date = '';
    }
    
    public function render()
    {
        $projects = Project::all();
        $users = User::all();
        
        // Create an array of weekdays for the template
        $weekdays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
        
        return view('livewire.tasks.task-create', [
            'projects' => $projects,
            'users' => $users,
            'weekdays' => $weekdays,
        ]);
    }
} 