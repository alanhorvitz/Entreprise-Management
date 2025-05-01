<?php

namespace App\Livewire\Reports;

use App\Models\Task;
use App\Models\DailyReport;
use App\Models\ReportTask;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class CreateReport extends Component
{
    public $date;
    public $summary;
    public $reportTasks = [];
    public $availableTasks = [];

    protected $rules = [
        'date' => 'required|date',
        'summary' => 'nullable|string',
        'reportTasks.*.task_id' => 'required|exists:tasks,id'
    ];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadAvailableTasks();
    }

    public function loadAvailableTasks()
    {
        $this->availableTasks = Task::whereHas('taskAssignments', function($query) {
            $query->where('user_id', auth()->id());
        })->whereIn('current_status', ['in_progress', 'not_started'])->get();
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

        // Check if a report already exists for this date
        $existingReport = DailyReport::where('user_id', auth()->id())
            ->whereDate('date', $this->date)
            ->first();

        if ($existingReport) {
            $this->addError('date', 'You have already submitted a report for this date.');
            return;
        }

        try {
            $report = DailyReport::create([
                'user_id' => auth()->id(),
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
                'message' => 'Unable to create report for this date. Please try again.',
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