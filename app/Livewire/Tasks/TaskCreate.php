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
    public $projectMembers = [];
    
    // New repetitive task fields
    public $is_repetitive = false;
    public $repetition_rate = 'weekly';
    public $recurrence_days = []; 
    public $recurrence_month_day = 1; 
    public $recurrence_end_date = ''; 

    // Listen for project selection changes
    protected $listeners = ['projectSelected' => 'loadProjectMembers'];

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
        $this->assignees = [];
    }

    public function updatedProjectId($value)
    {
        logger()->info('Project ID Updated', ['new_value' => $value]);
        $this->loadProjectMembers();
    }

    public function mount($project_id = null, $due_date = null)
    {
        if ($project_id) {
            $this->project_id = $project_id;
            $this->loadProjectMembers();
        }

        if ($due_date) {
            $this->due_date = $due_date;
            $this->start_date = $due_date;
            
            $dayOfWeek = Carbon::parse($due_date)->dayOfWeek;
            $this->recurrence_days = [$dayOfWeek];
            
            $this->recurrence_month_day = Carbon::parse($due_date)->day;
        } else {
            $today = Carbon::today()->format('Y-m-d');
            $this->start_date = $today;
            $this->due_date = $today;
            
            $dayOfWeek = Carbon::today()->dayOfWeek;
            $this->recurrence_days = [$dayOfWeek];
            
            $this->recurrence_month_day = Carbon::today()->day;
        }
    }
    
    public function create()
    {
        // Check if user has permission to create tasks
        if (!auth()->user()->hasPermissionTo('create tasks')) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to create tasks.'
            ]);
            $this->dispatch('closeModal');
            return;
        }

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
                'recurrence_interval' => Carbon::now(),
                'recurrence_days' => $daysBinary,
                'recurrence_month_day' => $this->recurrence_month_day,
                'start_date' => Carbon::parse($this->start_date),
                'end_date' => $this->recurrence_end_date ? Carbon::parse($this->recurrence_end_date) : null,
                'next_occurrence' => Carbon::parse($this->due_date),
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
        return view('livewire.tasks.task-create', [
            'projects' => Project::all(),
            'projectMembers' => $this->projectMembers,
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
} 