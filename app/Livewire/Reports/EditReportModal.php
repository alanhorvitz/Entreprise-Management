<?php

namespace App\Livewire\Reports;

use App\Models\DailyReport;
use App\Models\Task;
use App\Models\ReportTask;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EditReportModal extends Component
{
    public $reportId;
    public $report;
    public $date;
    public $summary;
    public $reportTasks = [];
    public $availableTasks = [];

    protected $rules = [
        'date' => 'required|date',
        'summary' => 'nullable|string',
        'reportTasks.*.task_id' => 'required|exists:tasks,id'
    ];

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = DailyReport::with(['reportTasks.task'])->find($reportId);
        
        if ($this->report) {
            $this->date = $this->report->date->format('Y-m-d');
            $this->summary = $this->report->summary;
            
            // Load existing report tasks
            $this->reportTasks = $this->report->reportTasks->map(function($reportTask) {
                return [
                    'id' => $reportTask->id,
                    'task_id' => $reportTask->task_id
                ];
            })->toArray();
        }
        
        $this->loadAvailableTasks();
    }

    public function loadAvailableTasks()
    {
        $this->availableTasks = Task::whereHas('taskAssignments', function($query) {
            $query->where('user_id', $this->report->user_id);
        })->get();
    }

    public function addTask()
    {
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

        try {
            DB::beginTransaction();

            // Update the report
            $this->report->update([
                'date' => $this->date,
                'summary' => $this->summary,
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