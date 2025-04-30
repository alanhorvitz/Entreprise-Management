<?php

namespace App\Livewire\Reports;

use App\Models\User;
use App\Models\DailyReport;
use App\Models\ReportTask;
use App\Models\Task;
use Livewire\Component;
use Carbon\Carbon;

class AssigneeReportModal extends Component
{
    public $userId;
    public $user;
    public $reports = [];
    public $selectedDate;
    public $dateRange = 'today';
    public $startDate;
    public $endDate;

    protected $listeners = ['closeModal' => 'close'];

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::with('departments')->find($userId);
        $this->setDateRange('today');
        $this->loadReports();
    }

    public function setDateRange($range)
    {
        $this->dateRange = $range;
        $today = Carbon::today();

        switch ($range) {
            case 'today':
                $this->startDate = $today;
                $this->endDate = $today;
                break;
            case 'yesterday':
                $this->startDate = $today->subDay();
                $this->endDate = $this->startDate;
                break;
            case 'last_7_days':
                $this->startDate = $today->subDays(6);
                $this->endDate = $today;
                break;
            case 'last_30_days':
                $this->startDate = $today->subDays(29);
                $this->endDate = $today;
                break;
            case 'this_month':
                $this->startDate = $today->startOfMonth();
                $this->endDate = $today->endOfMonth();
                break;
        }

        $this->loadReports();
    }

    public function loadReports()
    {
        $this->reports = DailyReport::with(['reportTasks.task'])
            ->where('user_id', $this->userId)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'desc')
            ->get()
            ->map(function($report) {
                $totalHours = $report->reportTasks->sum('hours_spent');
                return [
                    'id' => $report->id,
                    'date' => $report->date,
                    'summary' => $report->summary,
                    'submitted_at' => $report->submitted_at,
                    'total_hours' => $totalHours,
                    'tasks' => $report->reportTasks->map(function($reportTask) {
                        return [
                            'id' => $reportTask->task->id,
                            'title' => $reportTask->task->title,
                            'hours_spent' => $reportTask->hours_spent,
                            'progress_notes' => $reportTask->progress_notes,
                            'status' => $reportTask->task->current_status
                        ];
                    })
                ];
            });
    }

    public function close()
    {
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.reports.assignee-report-modal');
    }
} 