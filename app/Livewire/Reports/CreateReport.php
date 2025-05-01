<?php

namespace App\Livewire\Reports;

use App\Models\Task;
use App\Models\DailyReport;
use App\Models\ReportTask;
use App\Models\Project;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class CreateReport extends Component
{
    public $date;
    public $summary;
    public $project_id;
    public $reportTasks = [];
    public $availableTasks = [];
    public $availableProjects = [];

    protected $rules = [
        'date' => 'required|date',
        'summary' => 'nullable|string',
        'project_id' => 'required|exists:projects,id',
        'reportTasks.*.task_id' => 'required|exists:tasks,id'
    ];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadAvailableProjects();
        $this->loadAvailableTasks();
    }

    public function loadAvailableProjects()
    {
        $this->availableProjects = Project::whereHas('members', function($query) {
            $query->where('user_id', auth()->id());
        })->get();
    }

    public function loadAvailableTasks()
    {
        if ($this->project_id) {
            $this->availableTasks = Task::where('project_id', $this->project_id)
                ->whereHas('taskAssignments', function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->whereIn('current_status', ['in_progress', 'not_started'])
                ->get();
        } else {
            $this->availableTasks = collect();
        }
    }

    public function updatedProjectId()
    {
        $this->reportTasks = [];
        $this->loadAvailableTasks();
    }

    public function addTask()
    {
        if (!$this->project_id) {
            $this->addError('project_id', 'Please select a project first.');
            return;
        }
        $this->reportTasks[] = [
            'task_id' => ''
        ];
    }

    public function removeTask($index)
    {
        unset($this->reportTasks[$index]);
        $this->reportTasks = array_values($this->reportTasks);
    }

    public function save()
    {
        $this->validate();

        // Check if a report already exists for this date (regardless of project)
        $existingReport = DailyReport::where('user_id', auth()->id())
            ->whereDate('date', $this->date)
            ->first();

        if ($existingReport) {
            $this->addError('date', 'You have already submitted a report for today. Only one report per day is allowed.');
            return;
        }

        try {
            $report = DailyReport::create([
                'user_id' => auth()->id(),
                'project_id' => $this->project_id,
                'date' => $this->date,
                'summary' => $this->summary,
                'submitted_at' => now()
            ]);

            foreach ($this->reportTasks as $taskData) {
                ReportTask::create([
                    'report_id' => $report->id,
                    'task_id' => $taskData['task_id'],
                ]);
            }

            $this->dispatch('notify', [
                'message' => 'Report created successfully!',
                'type' => 'success',
            ]);
            return redirect()->route('reports.index');
        } catch (QueryException $e) {
            $this->dispatch('notify', [
                'message' => 'Unable to create report. Please try again.',
                'type' => 'error',
            ]);
            return;
        }
    }

    public function render()
    {
        return view('livewire.reports.create-report');
    }
} 