<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateTaskPage extends Component
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
    public $is_repetitive = false;
    public $repetition_rate = 'weekly';
    public $reminders_enabled = false;
    public $reminder_days_before = 1;
    
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
        'reminders_enabled' => 'boolean',
        'reminder_days_before' => 'required_if:reminders_enabled,true|integer|min:1|max:30',
    ];
    
    public function mount()
    {
        // Set default start date to today
        $this->start_date = date('Y-m-d');
        
        // Check if there's a date parameter in the URL
        if (request()->has('date')) {
            try {
                // Validate the date format
                $date = request()->date;
                $formattedDate = date('Y-m-d', strtotime($date));
                
                if ($formattedDate) {
                    $this->due_date = $formattedDate;
                }
            } catch (\Exception $e) {
                // If date is invalid, ignore it
            }
        }
    }
    
    public function create()
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
        
        // Handle repetitive task if enabled
        if ($this->is_repetitive) {
            // Create a repetitive task record in the repetitive_tasks table
            $task->repetitiveTask()->create([
                'task_id' => $task->id,
                'project_id' => $this->project_id,
                'created_by' => Auth::id(),
                'repetition_rate' => $this->repetition_rate,
            ]);
        }
        
        // Handle reminders if enabled
        if ($this->reminders_enabled) {
            // Create a reminder for the task
            $task->taskReminders()->create([
                'user_id' => Auth::id(),
                'days_before' => $this->reminder_days_before,
            ]);
        }
        
        session()->flash('message', 'Task created successfully!');
        session()->flash('alert-type', 'success');
        
        // Check if we came from the calendar page
        if (request()->has('from') && request()->from === 'calendar') {
            return redirect()->route('calendar.index');
        }
        
        return redirect()->route('tasks.index');
    }
    
    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->project_id = '';
        $this->due_date = '';
        $this->priority = 'medium';
        $this->current_status = 'todo';
        $this->start_date = date('Y-m-d');
        $this->status = 'pending_approval';
        $this->assignees = [];
        $this->is_repetitive = false;
        $this->repetition_rate = 'weekly';
        $this->reminders_enabled = false;
        $this->reminder_days_before = 1;
    }
    
    public function render()
    {
        return view('livewire.tasks.create-task-page', [
            'projects' => Project::all(),
            'users' => User::all(),
        ]);
    }
} 