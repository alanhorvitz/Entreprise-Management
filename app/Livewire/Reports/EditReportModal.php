<?php

namespace App\Livewire\Reports;

use App\Models\DailyReport;
use App\Models\Task;
use App\Models\ReportTask;
use App\Models\Project;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EditReportModal extends Component
{
    public $reportId;
    public $report;
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

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = DailyReport::with(['reportTasks.task'])->find($reportId);
        
        if ($this->report) {
            $this->date = $this->report->date->format('Y-m-d');
            $this->summary = $this->report->summary;
            $this->project_id = $this->report->project_id;
            
            // Load existing report tasks
            $this->reportTasks = $this->report->reportTasks->map(function($reportTask) {
                return [
                    'id' => $reportTask->id,
                    'task_id' => $reportTask->task_id
                ];
            })->toArray();
        }
        
        $this->loadAvailableProjects();
        $this->loadAvailableTasks();
    }

    public function loadAvailableProjects()
    {
        $this->availableProjects = Project::whereHas('members', function($query) {
            $query->where('user_id', $this->report->user_id);
        })->get();
    }

    public function loadAvailableTasks()
    {
        if ($this->project_id) {
            $this->availableTasks = Task::where('project_id', $this->project_id)
                ->whereHas('taskAssignments', function($query) {
                    $query->where('user_id', $this->report->user_id);
                })
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

        // Check if a report already exists for this date and project (excluding current report)
        $existingReport = DailyReport::where('user_id', $this->report->user_id)
            ->where('project_id', $this->project_id)
            ->whereDate('date', $this->date)
            ->where('id', '!=', $this->report->id)
            ->first();

        if ($existingReport) {
            $this->addError('date', 'You have already submitted a report for this project and date.');
            return;
        }

        try {
            DB::beginTransaction();

            // Update the report
            $this->report->update([
                'date' => $this->date,
                'summary' => $this->summary,
                'project_id' => $this->project_id,
            ]);

            // Delete existing report tasks
            $this->report->reportTasks()->delete();

            // Create new report tasks
            foreach ($this->reportTasks as $taskData) {
                $this->report->reportTasks()->create([
                    'task_id' => $taskData['task_id'],
                ]);
            }

            DB::commit();

            $this->dispatch('reportUpdated');
            $this->dispatch('closeEditModal');
            $this->dispatch('notify', [
                    'message' => 'Report updated successfully!',
                    'type' => 'success',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'message' => 'Failed to update report. Please try again.',
                'type' => 'error',
            ]);
        }
    }

    public function close()
    {
        $this->dispatch('closeEditModal');
    }

    public function render()
    {
        return view('livewire.reports.edit-report-modal');
    }
} 