<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;
use App\Models\RepetitiveTask;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TaskCalendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $weeks = [];
    public $monthName;
    public $projectFilter = '';
    public $repetitiveOnly = false;

    public function mount()
    {
        $today = Carbon::today();
        $this->currentMonth = $today->month;
        $this->currentYear = $today->year;
        $this->generateCalendarData();
    }

    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendarData();
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendarData();
    }

    public function goToToday()
    {
        $today = Carbon::today();
        $this->currentMonth = $today->month;
        $this->currentYear = $today->year;
        $this->generateCalendarData();
    }

    public function updatedProjectFilter()
    {
        $this->generateCalendarData();
    }

    public function updatedRepetitiveOnly()
    {
        $this->generateCalendarData();
    }

    public function openTaskModal($taskId)
    {
        $params = [
            'component' => 'tasks.task-show',
            'arguments' => [
                'taskId' => $taskId
            ]
        ];
        $this->dispatch('openModal', $params);
    }

    public function openCreateModal($date = null)
    {
        $params = [
            'component' => 'tasks.task-create',
            'arguments' => [
                'due_date' => $date
            ]
        ];
        $this->dispatch('openModal', $params);
    }

    private function generateCalendarData()
    {
        $this->weeks = [];
        $firstDayOfMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
        $this->monthName = $firstDayOfMonth->format('F Y');

        // Start the calendar on the previous Sunday
        $startDate = $firstDayOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        
        // End the calendar on the Saturday after the end of the month
        $endDate = $lastDayOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        // Get all tasks within the date range
        $tasks = $this->getTasks($startDate, $endDate);
        $repetitiveTasks = $this->getRepetitiveTasks($startDate, $endDate);

        // Create calendar structure with weeks and days
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $dateKey = $currentDate->format('Y-m-d');
                $dayTasks = $tasks->filter(function ($task) use ($dateKey) {
                    return $task->due_date ? $task->due_date->format('Y-m-d') === $dateKey : false;
                });

                // Add repetitive tasks that occur on this date
                $dayRepetitiveTasks = $this->getRepetitiveTasksForDate($repetitiveTasks, $currentDate);
                
                $week[] = [
                    'date' => $currentDate->copy(),
                    'isCurrentMonth' => $currentDate->month === $this->currentMonth,
                    'isToday' => $currentDate->isToday(),
                    'tasks' => $dayTasks,
                    'repetitiveTasks' => $dayRepetitiveTasks,
                ];
                $currentDate->addDay();
            }
            $this->weeks[] = $week;
        }
    }

    private function getTasks($startDate, $endDate)
    {
        $query = Task::with(['project', 'repetitiveTask'])
            ->whereBetween('due_date', [$startDate, $endDate]);
            
        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }
        
        if ($this->repetitiveOnly) {
            $query->whereHas('repetitiveTask');
        }
        
        return $query->get();
    }

    private function getRepetitiveTasks($startDate, $endDate)
    {
        $query = RepetitiveTask::with(['task', 'task.project'])
            ->whereHas('task', function($query) {
                if ($this->projectFilter) {
                    $query->where('project_id', $this->projectFilter);
                }
            });

        return $query->get();
    }

    private function getRepetitiveTasksForDate($repetitiveTasks, $date)
    {
        $matchingTasks = collect();
        
        foreach ($repetitiveTasks as $repetitiveTask) {
            if ($this->isTaskOccurringOn($repetitiveTask, $date)) {
                $matchingTasks->push($repetitiveTask->task);
            }
        }
        
        return $matchingTasks;
    }

    private function isTaskOccurringOn($repetitiveTask, $date)
    {
        $task = $repetitiveTask->task;
        
        // Check if the task's base starting date is after this date
        $startDate = Carbon::createFromTimestamp($repetitiveTask->start_date);
        if ($date->lt($startDate)) {
            return false;
        }
        
        // Check if we're past the end date (if one exists)
        if ($repetitiveTask->end_date > 0 && $date->gt(Carbon::createFromTimestamp($repetitiveTask->end_date))) {
            return false;
        }
        
        switch ($repetitiveTask->repetition_rate) {
            case 'daily':
                return true;
                
            case 'weekly':
                // Check if the current day of week is in the bitmask
                $dayOfWeek = $date->dayOfWeek;
                return ($repetitiveTask->recurrence_days & (1 << $dayOfWeek)) !== 0;
                
            case 'monthly':
                // Check if it's the right day of the month, accounting for months with fewer days
                $dayOfMonth = $date->day;
                $desiredDay = $repetitiveTask->recurrence_month_day;
                
                // If month doesn't have the specified day, check if it's the last day of the month
                if ($desiredDay > $date->daysInMonth) {
                    return $dayOfMonth === $date->daysInMonth;
                }
                
                return $dayOfMonth === $desiredDay;
                
            case 'yearly':
                // Check if both month and day match the original due date
                $dueDate = Carbon::parse($task->due_date);
                return $date->month === $dueDate->month && $date->day === $dueDate->day;
                
            default:
                return false;
        }
    }

    public function render()
    {
        $projects = Project::all();
        
        return view('livewire.tasks.task-calendar', [
            'projects' => $projects,
        ]);
    }
} 